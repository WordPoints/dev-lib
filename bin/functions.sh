#!/usr/bin/env bash

# Get the value of an extension/plugin header.
wpdl-get-extension-header () {

	local header=$1

	if [[ ! -e src ]]; then
		return;
	fi

	local value=$(grep -oh "${header}: .*" src/*.php)
	value=${value#"${header}: "}
	value=${value##* }

	echo -n "$value"
}

#*
# Get the project's textdomain.
#
# @var string $text_domain The project's textdomain.
#/
get-textdomain () {

	text_domain=$(wpdl-get-extension-header "Text Domain")

	if [[ $text_domain == '' ]]; then
		echo Please enter the textdomain for your project:
		read text_domain
	fi
}

# Run all codesniffers.
wpdl-codesniff() {

	CODESNIFF_ERROR=0

	trap 'CODESNIFF_ERROR=1' ERR

	wpdl-codesniff-php-syntax
	wpdl-codesniff-php-autoloaders
	wpdl-codesniff-phpcs
	wpdl-codesniff-strings
	wpdl-codesniff-dittography
	wpdl-codesniff-l10n
	wpdl-codesniff-bash
	wpdl-codesniff-jshint
	wpdl-codesniff-xmllint
	wpdl-codesniff-symlinks

	trap - ERR

	return "$CODESNIFF_ERROR"
}

# Get the path to sniff.
wpdl-get-codesniff-path() {

	local path_var=("CODESNIFF_PATH[@]")

	if [ ! -z "$1" ]; then

		local var="CODESNIFF_PATH_${1}"
		local other_var=

		if [ ! -z "${!var}" ]; then

			if [ ! -z "$2" ]; then
    			other_var="${var}_${2}"

    			if [ ! -z "${!other_var}" ]; then
    				var=$other_var
    			fi
    		fi

			path_var="${var}[@]"
		fi
	fi

	echo "${path_var}";
}

# Get the (\0 delimited) list of files to sniff.
wpdl-get-codesniff-files() {

	local path=$(wpdl-get-codesniff-path "${@}")

	if [[ $DOING_GIT_PRE_COMMIT == 1 ]]; then
		{ find "${!path}" -type f; echo "${STAGED_FILES}"; } | sort | uniq -d | tr '\n' '\0'
	else
		find "${!path}" -type f -print0
	fi
}

# Check php files for syntax errors.
wpdl-codesniff-php-syntax() {

	if wpdl-get-codesniff-files PHP SYNTAX \
		| xargs -0 -n1 php -l \
		| grep "^Parse error"
	then
		return 1;
	fi;
}

# Check php autoloader fallback files for validity.
wpdl-codesniff-php-autoloaders() {

	# Only run if there are staged class files.
	if [[ $DOING_GIT_PRE_COMMIT == 1 && "$(git diff --diff-filter=ACDMR --staged --name-only)" != src*/classes/* ]]; then
		return;
	fi

	local path=$(wpdl-get-codesniff-path PHP AUTOLOADERS)

	if find "${!path}" \
		| while read dir; do wpdl-codesniff-php-autoloader "${dir}"/; done \
		| grep "^Fatal error"
	then
		return 1;
	fi
}

# Check a php autoloader fallback file for validity.
wpdl-codesniff-php-autoloader() {

	local dir=$1
	local dependencies=("${CODESNIFF_PHP_AUTOLOADER_DEPENDENCIES[@]}")

	if [[ $WORDPOINTS_PROJECT_TYPE == extension ]]; then
		if [[ $dir =~ /points/ ]]; then
			dependencies+=( \
				"${WORDPOINTS_DEVELOP_DIR}/src/components/points/classes/" \
				"${WORDPOINTS_DEVELOP_DIR}/src/components/points/includes/" \
			)
		fi
	fi

	"${DEV_LIB_PATH}"/bin/verify-php-autoloader "${dir}" "${dependencies[@]}"
}

# Check php files with PHPCodeSniffer tools.
wpdl-codesniff-phpcs-base() {

	local command=${1-phpcs}

	if [ ! -e $PHPCS_DIR ]; then
		local phpcs="$command"
	else
		local phpcs="$PHPCS_DIR"/bin/"$command"
	fi

	local config
	config=()

	if [[ $PROJECT_SLUG != 'wordpoints' ]]; then
		config=(--runtime-set minimum_supported_wp_version 4.7)
	fi

	local prefix=$(wpdl-get-extension-header Namespace)

	if [[ $prefix != '' ]]; then
		config=("${config[@]}" --runtime-set prefixes "wordpoints_${prefix}")
	fi

	if [ -z $2 ]; then
		wpdl-get-codesniff-files PHP PHPCS
	else
		echo -n "${2}"
	fi \
		| xargs -0 "$phpcs" -s --standard="$WPCS_STANDARD" "${config[@]}"
}

# Check php files with PHPCS.
wpdl-codesniff-phpcs() {
	wpdl-codesniff-phpcs-base phpcs "${@}"
}

# Check php files with PHP Can Be Fixed.
wpdl-codesniff-phpcbf() {
	wpdl-codesniff-phpcs-base phpcbf "${@}"
}

# Check files for disallowed strings.
wpdl-codesniff-strings() {

	wpdl-get-codesniff-files STRINGS \
		| xargs -0 grep -H -n -v "${CODESNIFF_IGNORED_STRINGS[@]}" \
		| grep -e 'target="_blank"' -e http[^s_.-]

	# grep exits with 1 if nothing was found.
	[[ $? == '1' ]]
}

# Check files for dittography.
wpdl-codesniff-dittography() {

	wpdl-get-codesniff-files STRINGS DITTOGRAPHY \
		| xargs -0 grep -H -n -v "${CODESNIFF_IGNORED_DITTOGRAPHY[@]}" \
		| grep -iE --color=auto '[^$|/]\b([a-z].*?)\b[[:space:]*]+\1\b[^-]'

	# grep exits with 1 if nothing was found.
	[[ $? == '1' ]]
}

# Check JS files with jshint.
wpdl-codesniff-jshint() {
	wpdl-get-codesniff-files JS JSHINT | xargs -0 jshint
}

# Check PHP files for proper localization.
wpdl-codesniff-l10n() {

	local validator=wp-l10n-validator

	if [ -e $WPL10NV_DIR ]; then
		validator="$WPL10NV_DIR"/bin/wp-l10n-validator
	fi

	wpdl-get-codesniff-files PHP L10N_VALIDATOR | xargs -0 "${validator}" --
}

# Check XML files for syntax errors.
wpdl-codesniff-xmllint() {
	wpdl-get-codesniff-files XML XMLLINT | xargs -0 xmllint --noout
}

# Check bash files for syntax errors.
wpdl-codesniff-bash() {
	wpdl-get-codesniff-files BASH SYNTAX | xargs -0 -n1 bash -n 2>&1
}

# Check for broken symlinks.
wpdl-codesniff-symlinks() {

	# Only run if there are staged files being added/copied/deleted/renamed.
	if [[ $DOING_GIT_PRE_COMMIT == 1 && "$(git diff --diff-filter=ACDR --staged --name-only)" == '' ]]; then
		return;
	fi

	local path=$(wpdl-get-codesniff-path SYMLINKS)
	local files=$(find "${!path}" -type l ! -exec [ -e {} ] \; -print)

	if [[ "${files[@]}" != '' ]]; then
		echo "${files[@]}"
		return 1
	fi
}

# Run basic PHPUnit tests.
wpdl-test-phpunit() {

	local TEST_GROUP=${1-''}
	local CLOVER_FILE=${2-basic}

	local GROUP_OPTION=()
	local COVERAGE_OPTION=()

	if [[ $TEST_GROUP != '' ]]; then
		if [[ $TEST_GROUP == ajax && $RUN_AJAX_TESTS == 0 ]]; then
			echo 'Not running Ajax tests.'
			return
		elif [[ $TEST_GROUP == uninstall && $RUN_UNINSTALL_TESTS == 0 ]]; then
			echo 'Not running uninstall tests.'
			return
		fi

		if [[ $WP_VERSION == '3.8' && $TEST_GROUP == ajax && $WP_MULTISITE == 1 ]]; then
			echo 'Not running multisite Ajax tests on 3.8, see https://github.com/WordPoints/wordpoints/issues/239.'
			return
		fi

		GROUP_OPTION=(--group="$TEST_GROUP")
		CLOVER_FILE+="-$TEST_GROUP"

		if [[ $TEST_GROUP == uninstall && -e phpunit.uninstall.xml.dist ]]; then
			GROUP_OPTION=(--configuration=phpunit.uninstall.xml.dist)
		elif [[ $TRAVIS_PHP_VERSION == '5.2' ]]; then
			sed -i '' -e "s/<group>$TEST_GROUP<\/group>//" ./phpunit.xml.dist
		fi
	fi

	if [[ $DO_CODE_COVERAGE == 1 ]]; then
		COVERAGE_OPTION=(--coverage-clover "build/logs/clover-$CLOVER_FILE.xml")
	fi

	phpunit "${GROUP_OPTION[@]}" "${COVERAGE_OPTION[@]}"
}

# Run uninstall PHPUnit tests.
wpdl-phpunit-uninstall() {
	wpdl-test-phpunit uninstall
}

# Run Ajax PHPUnit tests.
wpdl-phpunit-ajax() {
	wpdl-test-phpunit ajax
}

# Run the basic tests on multisite.
wpdl-phpunit-ms() {
	WP_MULTISITE=1 wpdl-test-phpunit '' ms
}

# Run the uninstall tests on multisite.
wpdl-phpunit-ms-uninstall() {
	WP_MULTISITE=1 wpdl-test-phpunit uninstall ms
}

# Run the ajax tests on multisite.
wpdl-phpunit-ms-ajax() {
	WP_MULTISITE=1 wpdl-test-phpunit ajax ms
}

# Run basic tests for multisite in network mode.
wpdl-phpunit-ms-network() {
	WORDPOINTS_NETWORK_ACTIVE=1 WP_MULTISITE=1 wpdl-test-phpunit '' ms-network
}

# Run uninstall tests in multisite in network mode.
wpdl-phpunit-ms-network-uninstall() {
	WORDPOINTS_NETWORK_ACTIVE=1 WP_MULTISITE=1 wpdl-test-phpunit uninstall ms-network
}

# Run Ajax tests in multisite in network mode.
wpdl-phpunit-ms-network-ajax() {
	WORDPOINTS_NETWORK_ACTIVE=1 WP_MULTISITE=1 wpdl-test-phpunit ajax ms-network
}

# EOF
