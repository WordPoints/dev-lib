#!/bin/bash

set -e
shopt -s expand_aliases

# Load environment vars.
source "$DEV_LIB_PATH"/bin/env.sh

# Load core functions.
source "$DEV_LIB_PATH"/bin/functions.sh

# Load travis commands.
source "$DEV_LIB_PATH"/travis/commands.sh

# Load customisation.
if [ -e .wordpoints-dev-lib-config.sh ]; then
    source .wordpoints-dev-lib-config.sh
fi

# Allow config vars and functions to be overridden.
if [ "$(declare -f wordpoints-dev-lib-config )" ]; then
	wordpoints-dev-lib-config
fi

# Back-compat.
if [ -e .ci-env.sh ]; then
    source .ci-env.sh
fi

# Set up.
if [[ $TRAVISCI_RUN == phpunit ]]; then
	setup-phpunit
	setup-wpcept
elif [[ $TRAVISCI_RUN == codesniff ]]; then
	setup-codesniff
fi

set +e
