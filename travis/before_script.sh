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
if [ -e .ci-env.sh ]; then
    source .ci-env.sh
fi

# Set up.
if [[ $TRAVISCI_RUN == phpunit ]]; then
	setup-phpunit
elif [[ $TRAVISCI_RUN == codesniff ]]; then
	setup-codesniff
fi

set +e
