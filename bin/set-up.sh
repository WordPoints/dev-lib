#!/usr/bin/env bash

# Set up the git pre-commit hook.
if [ ! -e .git/hooks/pre-commit ]; then
	echo Symlinking git pre-commit hook
	mkdir .git/hooks
	ln -s ../../"$DEV_LIB_PATH"/git/pre-commit .git/hooks
fi

# Set up the git pre-commit hook for the dev-lib.
if [ ! -e .git/modules/"$DEV_LIB_PATH"/hooks/pre-commit ]; then
	echo Symlinking git pre-commit hook for dev-lib
	mkdir .git/modules/"$DEV_LIB_PATH"/hooks
	ln -s ../../../../"$DEV_LIB_PATH"/git/pre-commit .git/modules/"$DEV_LIB_PATH"/hooks
fi

# Install composer dependencies.
composer install

# Install npm dependencies.
if which yarn; then
	yarn
else
	npm install
fi

# EOF
