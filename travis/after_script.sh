#!/bin/bash

set -e
shopt -s expand_aliases

if [[ $DO_CODE_COVERAGE == 1 ]]; then
	php vendor/bin/coveralls --root_dir src
fi

set +e