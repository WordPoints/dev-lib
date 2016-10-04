#!/usr/bin/env bash

#*
# Get the project's textdomain.
#
# @var string $text_domain The project's textdomain.
#/
get-textdomain () {

	text_domain=$(grep -oh "Text Domain: .*" src/*.php)
	text_domain=${text_domain#"Text Domain: "}

	if [[ $text_domain == '' ]]; then
		echo Please enter the textdomain for your project:
		read text_domain
	fi
}

# Run all codesniffers.
wpdl-codesniff() {
	wpdl-codesniff-php-syntax
	wpdl-codesniff-php-autoloaders
	wpdl-codesniff-phpcs
	wpdl-codesniff-l10n
	wpdl-codesniff-bash
	wpdl-codesniff-jshint
	wpdl-codesniff-xmllint
	wpdl-codesniff-symlinks
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

# Check php files for syntax errors.
wpdl-codesniff-php-syntax() {
	local path=$(wpdl-get-codesniff-path PHP SYNTAX)

	if find "${!path}" -exec php -l {} \; | grep "^Parse error"; then
		return 1;
	fi;
}

# Check php autoloader fallback files for validity.
wpdl-codesniff-php-autoloaders() {
	local path=$(wpdl-get-codesniff-path PHP AUTOLOADERS)

	if find "${!path}" \
		| while read dir; do "${DEV_LIB_PATH}"/bin/verify-php-autoloader "${dir}"/; done \
		| grep "^Fatal error"
	then
		return 1;
	fi
}

# Check php files with PHPCodeSniffer tools.
wpdl-codesniff-phpcs-base() {

	local command=${1-phpcs}

	if [ -z $2 ]; then
		local path=$(wpdl-get-codesniff-path PHP PHPCS)
		local files=$(find "${!path}")
	else
		local files=("$2")
	fi

	if [ ! -e $PHPCS_DIR ]; then
		local phpcs="$command"
	else
		local phpcs="$PHPCS_DIR"/scripts/"$command"
	fi

	"$phpcs" -ns --standard="$WPCS_STANDARD" ${files[@]}
}

# Check php files with PHPCS.
wpdl-codesniff-phpcs() {
	wpdl-codesniff-phpcs-base phpcs "${@:1}"
}

# Check php files with PHP Can Be Fixed.
wpdl-codesniff-phpcbf() {
	wpdl-codesniff-phpcs-base phpcbf "${@:1}"
}

# Check JS files with jshint.
wpdl-codesniff-jshint() {
	jshint .
}

# Check PHP files for proper localization.
wpdl-codesniff-l10n() {
	if [ ! -e $WPL10NV_DIR ]; then
		wp-l10n-validator
	else
		"$WPL10NV_DIR"/bin/wp-l10n-validator
	fi
}

# Check XML files for syntax errors.
wpdl-codesniff-xmllint() {
	local path=$(wpdl-get-codesniff-path XML XMLLINT)
	local files=$(find "${!path}" -type f)

	if [ ${#files[@]} != 0 ]; then
		xmllint --noout ${files[@]}
	fi
}

# Check bash files for syntax errors.
wpdl-codesniff-bash() {
	local path=$(wpdl-get-codesniff-path BASH SYNTAX)

	local errors=$(find "${!path}" -exec bash -n {} \; 2>&1)

	if [[ $errors != '' ]]; then
		echo "${errors}"
		return 1
	fi
}

# Check for broken symlinks.
wpdl-codesniff-symlinks() {
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
