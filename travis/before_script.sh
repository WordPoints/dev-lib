#!/bin/bash

set -e
shopt -s expand_aliases

export WP_TESTS_DIR=/tmp/wordpress-tests/
export PROJECT_DIR=$(pwd)/src
export PROJECT_SLUG=$(basename "$(pwd)" | sed 's/^wp-//')
export PHPCS_DIR=/tmp/phpcs
export PHPCS_GITHUB_SRC=squizlabs/PHP_CodeSniffer
export PHPCS_GIT_TREE=master
export WPCS_DIR=/tmp/wpcs
export WPCS_GITHUB_SRC=WordPress-Coding-Standards/WordPress-Coding-Standards
export WPCS_GIT_TREE=master
export WPCS_STANDARD=$(if [ -e phpcs.ruleset.xml ]; then echo phpcs.ruleset.xml; else echo WordPress; fi)
CODESNIFF_PATH=(. ! -path "./dev-lib/*" ! -path "./vendor/*")
export CODESNIFF_PATH
export RUN_UNINSTALL_TESTS=$(if grep -q '<group>uninstall</group>' phpunit.xml.dist; then echo 1; else echo 0; fi)
export RUN_AJAX_TESTS=$(if grep -q '<group>ajax</group>' phpunit.xml.dist; then echo 1; else echo 0; fi)
export DO_CODE_COVERAGE=$(if [[ $TRAVIS_PHP_VERSION == hhvm ]] && [ -e .coveralls.yml ]; then echo 1; else echo 0; fi)

if [[ -z $WORDPOINTS_VERSION ]]; then
	export WORDPOINTS_VERSION=master
fi

source "$DEV_LIB_PATH"/travis/commands.sh

if [ -e .ci-env.sh ]; then
    source .ci-env.sh
fi

if [[ $TRAVISCI_RUN == phpunit ]]; then
	setup-phpunit
elif [[ $TRAVISCI_RUN == codesniff ]]; then
	setup-codesniff
fi

set +e
