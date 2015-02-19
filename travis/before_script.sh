#!/bin/bash

set -e
shopt -s expand_aliases

# Paths
export WP_TESTS_DIR=/tmp/wordpress-tests/
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
export WPCS_GIT_TREE=923b63a15c4b9a61f08ab059c3f9e7efa85cb31d
export WPCS_STANDARD=$(if [ -e phpcs.ruleset.xml ]; then echo phpcs.ruleset.xml; else echo WordPress; fi)

# WP L10n Validator
export DO_WPL10NV=$(if [ -e wp-l10n-validator.json ]; then echo 1; else echo 0; fi)
export WPL10NV_DIR=/tmp/wp-l10n-validator
export WPL10NV_GITHUB_SRC=JDGrimes/wp-l10n-validator
export WPL10NV_GIT_TREE=master

# State
export RUN_UNINSTALL_TESTS=$(if grep -q '<group>uninstall</group>' phpunit.xml.dist; then echo 1; else echo 0; fi)
export RUN_AJAX_TESTS=$(if grep -q '<group>ajax</group>' phpunit.xml.dist; then echo 1; else echo 0; fi)
export DO_CODE_COVERAGE=$(if [[ $TRAVIS_PHP_VERSION == hhvm ]] && [ -e .coveralls.yml ]; then echo 1; else echo 0; fi)

# WordPoints
if [[ -z $WORDPOINTS_VERSION ]]; then
	export WORDPOINTS_VERSION=master
fi

# Load commands.
source "$DEV_LIB_PATH"/travis/commands.sh

# Load customisation.
if [ -e .ci-env.sh ]; then
    source .ci-env.sh
fi

# Set up.
if [[ $TRAVISCI_RUN == phpunit ]]; then
	setup-phpunit
elif [[ $TRAVISCI_RUN == codesniff ]]; then
	setup-codesniff
fi

set +e
