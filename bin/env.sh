#!/usr/bin/env bash

# Paths
export WP_DEVELOP_DIR=/tmp/wordpress
export WP_TESTS_DIR=/tmp/wordpress/tests/phpunit
export WP_CORE_DIR=/tmp/wordpress/src
export PROJECT_DIR=$(pwd)/src
export PROJECT_SLUG=$(basename "$(pwd)" | sed 's/^wp-//')
CODESNIFF_PATH=(. '!' -path "./$DEV_LIB_PATH/*" '!' -path "./vendor/*")
export CODESNIFF_PATH

# PHPCS
export DO_PHPCS=$(if [ -e phpcs.ruleset.xml ]; then echo 1; else echo 0; fi)
export PHPCS_DIR=/tmp/phpcs
export PHPCS_GITHUB_SRC=squizlabs/PHP_CodeSniffer
export PHPCS_GIT_TREE=master

# WPCS
export WPCS_DIR=/tmp/wpcs
export WPCS_GITHUB_SRC=WordPress-Coding-Standards/WordPress-Coding-Standards
export WPCS_GIT_TREE=e7464c59c730ec304ea8c4f74beeacdb3dc785ab
export WPCS_STANDARD=$(if [ -e phpcs.ruleset.xml ]; then echo phpcs.ruleset.xml; else echo WordPress; fi)

# WP L10n Validator
export DO_WPL10NV=$(if [ -e wp-l10n-validator.json ]; then echo 1; else echo 0; fi)
export WPL10NV_DIR=/tmp/wp-l10n-validator
export WPL10NV_GITHUB_SRC=JDGrimes/wp-l10n-validator
export WPL10NV_GIT_TREE=master

# PHPUnit
export DO_PHPUNIT=$(if [ -e phpunit.xml.dist ]; then echo 1; else echo 0; fi)
export RUN_UNINSTALL_TESTS=$(if [[ $DO_PHPUNIT == 1 ]] && grep -q '<group>uninstall</group>' phpunit.xml.dist; then echo 1; else echo 0; fi)
export RUN_AJAX_TESTS=$(if [[ $DO_PHPUNIT == 1 ]] && grep -q '<group>ajax</group>' phpunit.xml.dist; then echo 1; else echo 0; fi)
export DO_CODE_COVERAGE=$(if [[ $TRAVIS_PHP_VERSION == hhvm ]] && [ -e .coveralls.yml ]; then echo 1; else echo 0; fi)

# WordPoints
export WORDPOINTS_DEVELOP_DIR=/tmp/wordpoints
export WORDPOINTS_TESTS_DIR=/tmp/wordpoints/tests/phpunit/
if [[ -z $WORDPOINTS_VERSION ]]; then
	export WORDPOINTS_VERSION=master
fi

# EOF
