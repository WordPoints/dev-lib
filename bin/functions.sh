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

# Check php files for syntax errors.
wpdl-codesniff-php-syntax() {
	find "${CODESNIFF_PATH[@]}" \( -name '*.php' -o -name '*.inc' \) -exec php -lf {} \;
}

# Check php files with PHPCodeSniffer.
wpdl-codesniff-phpcs() {
	local files=$(find "${CODESNIFF_PATH[@]}" -name '*.php')

	if [ ! -e $PHPCS_DIR ]; then
		local phpcs=phpcs
	else
		local phpcs="$PHPCS_DIR"/scripts/phpcs
	fi

	"$phpcs" -ns --standard="$WPCS_STANDARD" ${files[@]}
}

# Check JS files with jshint.
wpdl-codesniff-jshint() {
	jshint .
}

# Check PHP files for proper localization.
wpdl-codesniff-l10n() {
	"$WPL10NV_DIR"/bin/wp-l10n-validator
}

# Check XML files for syntax errors.
wpdl-codesniff-xmllint() {
	local files=$(find "${CODESNIFF_PATH[@]}" -type f \( -name '*.xml' -o -name '*.xml.dist' \))

	if [ ${#files[@]} != 0 ]; then
		xmllint --noout "${files[@]}"
	fi
}

# Check bash files for syntax errors.
wpdl-codesniff-bash() {
	find "${CODESNIFF_PATH[@]}" -name '*.sh' -exec bash -n {} \;
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

		if [[ $TRAVIS_PHP_VERSION == '5.2' ]]; then
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