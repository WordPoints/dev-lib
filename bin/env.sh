#!/usr/bin/env bash

# Paths
export WP_DEVELOP_DIR=/tmp/wordpress
if [[ -z $WP_TESTS_DIR ]]; then
	export WP_TESTS_DIR=/tmp/wordpress/tests/phpunit
fi
export WP_CORE_DIR=/tmp/wordpress/src
export PROJECT_DIR=$(pwd)/src
export PROJECT_SLUG=$(basename "$(pwd)" | sed 's/^wp-//')

# Codesniff path
CODESNIFF_PATH=(. '!' -path "./$DEV_LIB_PATH/*" '!' -path "./vendor/*")
CODESNIFF_PATH_PHP=("${CODESNIFF_PATH[@]}" '(' -name '*.php' -o -name '*.inc' ')')
CODESNIFF_PATH_PHP_AUTOLOADERS=("${CODESNIFF_PATH_PHP[@]}" -path './src/*/classes')

# Codeception requires PHP 5.4+.
if [[ $TRAVIS_PHP_VERSION == '5.2' || $TRAVIS_PHP_VERSION == '5.3' ]]; then
	CODESNIFF_PATH_PHP_SYNTAX=("${CODESNIFF_PATH_PHP[@]}" '!' -path "./tests/codeception/*")
fi

CODESNIFF_PATH_XML=("${CODESNIFF_PATH[@]}" '(' -name '*.xml' -o -name '*.xml.dist' ')')
CODESNIFF_PATH_BASH=("${CODESNIFF_PATH[@]}" -name '*.sh')

export CODESNIFF_PATH
export CODESNIFF_PATH_PHP
export CODESNIFF_PATH_PHP_AUTOLOADERS
export CODESNIFF_PATH_PHP_SYNTAX
export CODESNIFF_PATH_XML
export CODESNIFF_PATH_BASH

# PHPCS
export DO_PHPCS=$(if [ -e phpcs.ruleset.xml ]; then echo 1; else echo 0; fi)
export PHPCS_DIR=/tmp/phpcs
export PHPCS_GITHUB_SRC=squizlabs/PHP_CodeSniffer
export PHPCS_GIT_TREE=4122da6604e2967c257d6c81151122d08cae60cf

# WPCS
export WPCS_DIR=/tmp/wpcs
export WPCS_GITHUB_SRC=WordPress-Coding-Standards/WordPress-Coding-Standards
export WPCS_GIT_TREE=a54499411fb9ca55a35fc7003422868cdd072ef2
export WPCS_STANDARD=$(if [ -e phpcs.ruleset.xml ]; then echo phpcs.ruleset.xml; else echo WordPress; fi)

# WP L10n Validator
export DO_WPL10NV=$(if [ -e wp-l10n-validator.json ]; then echo 1; else echo 0; fi)
export WPL10NV_DIR=/tmp/wp-l10n-validator
export WPL10NV_GITHUB_SRC=JDGrimes/wp-l10n-validator
export WPL10NV_GIT_TREE=develop

# PHPUnit
export DO_PHPUNIT=$(if [ -e phpunit.xml.dist ]; then echo 1; else echo 0; fi)
export RUN_UNINSTALL_TESTS=$(if [[ $DO_PHPUNIT == 1 ]] && grep -q '<group>uninstall</group>' phpunit.xml.dist; then echo 1; else echo 0; fi)
export RUN_AJAX_TESTS=$(if [[ $DO_PHPUNIT == 1 ]] && grep -q '<group>ajax</group>' phpunit.xml.dist; then echo 1; else echo 0; fi)
export DO_CODE_COVERAGE=$(if [[ $TRAVIS_PHP_VERSION == hhvm ]] && [ -e .coveralls.yml ]; then echo 1; else echo 0; fi)

# WP Browser (Codeception)
export DO_WP_CEPT=$(if [[ $TRAVIS_PHP_VERSION == '5.6' ]]; then echo 1; else echo 0; fi)
export WP_CEPT_SERVER='127.0.0.1:8080'

# WordPoints
export WORDPOINTS_DEVELOP_DIR=/tmp/wordpoints
export WORDPOINTS_TESTS_DIR=/tmp/wordpoints/tests/phpunit/
if [[ -z $WORDPOINTS_VERSION ]]; then
	export WORDPOINTS_VERSION=master
fi

# WordPoints Module
if [[ $WORDPOINTS_PROJECT_TYPE == module ]]; then
	export WORDPOINTS_MODULE="${PROJECT_SLUG}\\${PROJECT_SLUG}.php"
fi

# EOF
