#!/usr/bin/env bash

export DEV_LIB_PATH=.
export CODESNIFF_PATH=(. '!' -path "./.idea/*" '!' -path "*/.git/*")

wordpoints-dev-lib-config() {

	export WPCS_GIT_TREE=develop

	export CODESNIFF_PATH_PHP_AUTOLOADERS=(bin)
	export CODESNIFF_PATH_PHP_PHPCS=("${CODESNIFF_PATH_PHP[@]}" '!' -path "./phpcs/WordPoints/Tests/*")

	# Ignore some strings that are expected.
	CODESNIFF_IGNORED_STRINGS=(\
		"${CODESNIFF_IGNORED_STRINGS[@]}" \
		# Needs to be processed with a non-cryptographic hashing algorithm to match
		# the value from core (in the Codeception tests loader class).
		-e 'dashboard_primary_' \
	)
}

# EOF
