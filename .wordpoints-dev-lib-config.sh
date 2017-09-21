#!/usr/bin/env bash

export DEV_LIB_PATH=.
export CODESNIFF_PATH=(. '!' -path "./.idea/*" '!' -path "*/.git/*")

wordpoints-dev-lib-config() {

	export WPCS_GIT_TREE=develop

	export CODESNIFF_PATH_PHP_AUTOLOADERS=(bin)
	export CODESNIFF_PATH_PHP_PHPCS=("${CODESNIFF_PATH_PHP[@]}" '!' -path "./phpcs/WordPoints/Tests/*")
}

# EOF
