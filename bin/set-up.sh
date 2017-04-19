#!/usr/bin/env bash

# Set up the git pre-commit hook.
if [ ! -e .git/hooks/pre-commit ]; then
	echo Symlinking git pre-commit hook
	mkdir .git/hooks
	ln -s ../../"$DEV_LIB_PATH"/git/pre-commit .git/hooks
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
