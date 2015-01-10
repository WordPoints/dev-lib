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
export CODESNIFF_PATH='. ! -path "./dev-lib/*" ! -path "./vendor/*"'
export RUN_UNINSTALL_TESTS=$(grep -q '<group>uninstall<\/group>' phpunit.xml.dist)
export RUN_AJAX_TESTS=$(grep -q '<group>ajax<\/group>' phpunit.xml.dist)


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
