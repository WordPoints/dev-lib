#!/bin/bash

set -e
shopt -s expand_aliases

# Things run on success.
if [[ $TRAVIS_TEST_RESULT == 0 ]]; then
	if [[ $DO_CODE_COVERAGE == 1 ]]; then
		bash <(curl -s https://codecov.io/bash) -f $(find build/logs/ -name 'clover-*.xml')
	fi
fi

set +e
