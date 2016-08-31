#!/usr/bin/env bash

if [ ! -e .wordpoints-dev-lib-config.sh ]; then
    echo 'No configuration file (.wordpoints-dev-lib-config.sh) found.'
    echo Assuming default configuration: DEV_LIB_PATH=dev-lib WORDPOINTS_PROJECT_TYPE=module
fi

cd "${DEV_LIB_PATH}"

BRANCH=$(git symbolic-ref --short HEAD)

git pull

git checkout "${BRANCH}"

cd -

"${DEV_LIB_PATH}"/run init

# EOF
