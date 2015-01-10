#!/bin/bash

set -e
shopt -s expand_aliases

if [[ $TRAVIS_PHP_VERSION == hhvm ]] && [ -e .coveralls.yml ]; then
	php vendor/bin/coveralls
fi

set +e