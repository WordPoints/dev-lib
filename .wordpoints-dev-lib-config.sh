#!/usr/bin/env bash

export DEV_LIB_PATH=.
export CODESNIFF_PATH=(. '!' -path "./.idea/*" '!' -path "*/.git/*")

wordpoints-dev-lib-config() {

	export CODESNIFF_PATH_PHP_AUTOLOADERS=(bin)
}

# EOF
