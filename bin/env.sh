#!/usr/bin/env bash

# Paths
if [[ -z $WP_DEVELOP_DIR ]]; then
	export WP_DEVELOP_DIR=/tmp/wordpress
fi

if [[ -z $WP_TESTS_DIR ]]; then
	export WP_TESTS_DIR="$WP_DEVELOP_DIR/tests/phpunit"
fi

if [[ -z $WP_CORE_DIR ]]; then
	export WP_CORE_DIR="$WP_DEVELOP_DIR/src"
fi

export PROJECT_DIR=$(pwd)/src
export PROJECT_SLUG=$(basename "$(pwd)" | sed 's/^wp-//')

# Codesniff path
CODESNIFF_PATH=(. '!' -path "./$DEV_LIB_PATH/*" '!' -path "./vendor/*" '!' -path "./.idea/*" '!' -path "./node_modules/*" '!' -path "*/.git/*")
CODESNIFF_PATH_PHP=("${CODESNIFF_PATH[@]}" '(' -name '*.php' -o -name '*.inc' ')')
CODESNIFF_PATH_PHP_AUTOLOADERS=(src -path '*/classes')
CODESNIFF_PATH_PHP_L10N_VALIDATOR=(src '(' -name '*.php' -o -name '*.inc' ')')

# Codeception requires PHP 5.4+.
if [[ $TRAVIS_PHP_VERSION == '5.2' || $TRAVIS_PHP_VERSION == '5.3' ]]; then
	CODESNIFF_PATH_PHP_SYNTAX=("${CODESNIFF_PATH_PHP[@]}" '!' -path "./tests/codeception/*")
fi

CODESNIFF_PATH_JS=("${CODESNIFF_PATH[@]}" -name '*.js')
CODESNIFF_PATH_XML=("${CODESNIFF_PATH[@]}" '(' -name '*.xml' -o -name '*.xml.dist' ')')
CODESNIFF_PATH_BASH=("${CODESNIFF_PATH[@]}" -name '*.sh')
CODESNIFF_PATH_STRINGS=("${CODESNIFF_PATH[@]}" '!' -name "*.lock" '!' -path "*/_generated/*" '!' -path "*/_output/*")
CODESNIFF_IGNORED_STRINGS=(-e http://semver.org/ -e http://keepachangelog.com/ -e http://www.php-fig.org/ -e http://127.0.0.1:8080 -e CODESNIFF_IGNORED_STRINGS -e 'grep -e')

export CODESNIFF_PATH
export CODESNIFF_PATH_JS
export CODESNIFF_PATH_PHP
export CODESNIFF_PATH_PHP_AUTOLOADERS
export CODESNIFF_PATH_PHP_L10N_VALIDATOR
export CODESNIFF_PATH_PHP_SYNTAX
export CODESNIFF_PATH_XML
export CODESNIFF_PATH_BASH
export CODESNIFF_PATH_STRINGS
export CODESNIFF_IGNORED_STRINGS

# PHPCS
export DO_PHPCS=$(if [ -e phpcs.ruleset.xml ]; then echo 1; else echo 0; fi)
export PHPCS_DIR=/tmp/phpcs
export PHPCS_GITHUB_SRC=squizlabs/PHP_CodeSniffer
export PHPCS_GIT_TREE=2.8.1

# WPCS
export WPCS_DIR=/tmp/wpcs
export WPCS_GITHUB_SRC=WordPress-Coding-Standards/WordPress-Coding-Standards
export WPCS_GIT_TREE=aa75d75f6fd5720b25330abb243a94197538e127
export WPCS_STANDARD=$(if [ -e phpcs.ruleset.xml ]; then echo phpcs.ruleset.xml; else echo WordPress; fi)

# WP L10n Validator
export DO_WPL10NV=$(if [ -e wp-l10n-validator.json ]; then echo 1; else echo 0; fi)
export WPL10NV_DIR=/tmp/wp-l10n-validator
export WPL10NV_GITHUB_SRC=JDGrimes/wp-l10n-validator
export WPL10NV_GIT_TREE=develop

# PHPUnit
export DO_PHPUNIT=$(if [ -e phpunit.xml.dist ]; then echo 1; else echo 0; fi)
export RUN_UNINSTALL_TESTS=$(if [[ $DO_PHPUNIT == 1 ]] && ([[ -e phpunit.uninstall.xml.dist ]] || grep -q '<group>ajax</group>' phpunit.xml.dist); then echo 1; else echo 0; fi)
export RUN_AJAX_TESTS=$(if [[ $DO_PHPUNIT == 1 ]] && grep -q '<group>ajax</group>' phpunit.xml.dist; then echo 1; else echo 0; fi)
export DO_CODE_COVERAGE=$(if [[ $TRAVIS_PHP_VERSION == hhvm ]] && grep -q codecov README.md; then echo 1; else echo 0; fi)

# WP Browser (Codeception)
export DO_WP_CEPT=$(if [[ $TRAVIS_PHP_VERSION == '5.6' ]] && (shopt -s nullglob; f=(tests/codeception/acceptance/*.cept.php); ((${#f[@]}))); then echo 1; else echo 0; fi)
export WP_CEPT_SERVER='127.0.0.1:8080'

# WordPoints
if [[ -z $WORDPOINTS_DEVELOP_DIR ]]; then
	export WORDPOINTS_DEVELOP_DIR=/tmp/wordpoints
fi

if [[ $WORDPOINTS_PROJECT_TYPE == wordpoints ]]; then
	export WORDPOINTS_TESTS_DIR=/home/travis/build/WordPoints/wordpoints/tests/phpunit/
else
	export WORDPOINTS_TESTS_DIR="$WORDPOINTS_DEVELOP_DIR/tests/phpunit/"
fi

if [[ -z $WORDPOINTS_VERSION ]]; then
	export WORDPOINTS_VERSION=master
fi

# WordPoints Module
if [[ $WORDPOINTS_PROJECT_TYPE == module ]]; then
	export WORDPOINTS_MODULE="${PROJECT_SLUG}\\${PROJECT_SLUG}.php"
fi

# Git Pre-commit Hook
if [[ $DOING_GIT_PRE_COMMIT == 1 ]]; then
	STAGED_FILES=$(git diff --diff-filter=AM --staged --name-only | awk '$0="./"$0')
fi

# EOF
