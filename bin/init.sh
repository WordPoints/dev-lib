#!/usr/bin/env bash

if [ ! -e .wordpoints-dev-lib-config.sh ]; then
    echo 'No configuration file (.wordpoints-dev-lib-config.sh) found.'
    echo Assuming default configuration: DEV_LIB_PATH=dev-lib WORDPOINTS_PROJECT_TYPE=module
fi

# Copy the Travis CI configuration file. This is copied rather than symlinked because
# Travis needs to be able to retrieve it from GitHub.
echo Copying Travis CI config for $WORDPOINTS_PROJECT_TYPE
cp "$DEV_LIB_PATH"/travis/"$WORDPOINTS_PROJECT_TYPE".yml .travis.yml

# Symlink the PHPCS configuration if you want to use PHPCS.
if [ ! -e phpcs.ruleset.xml ]; then
	echo Symlinking PHPCS config
	ln -s "$DEV_LIB_PATH"/phpcs/WordPoints/ruleset.xml phpcs.ruleset.xml
fi

# Symlink the jshint configuration.
if [ ! -e .jshintrc ]; then
	echo Symlinking jshint config
	ln -s "$DEV_LIB_PATH"/jshint/.jshintrc .
fi

# Symlink the jshint ignores configuration.
if [ ! -e .jshintrc ]; then
	echo Symlinking jshint ignores config
	ln -s "$DEV_LIB_PATH"/jshint/.jshintignore .
fi

# Symlink the PHPUnit configuration for PHPUnit testing.
if [ ! -e phpunit.xml.dist ]; then
	echo Symlinking PHPUnit config
	ln -s "$DEV_LIB_PATH"/phpunit/"$WORDPOINTS_PROJECT_TYPE".xml.dist phpunit.xml.dist
fi

# Symlink the Coveralls configuration file if you want to use code coverage.
if [ ! -e .coveralls.yml ]; then
	echo Symlinking Coveralls config
	ln -s "$DEV_LIB_PATH"/travis/.coveralls.yml .
fi

# Copy the l10n validator configuration file.
if [ ! -e wp-l10n-validator.json ]; then
	echo Copying L10n Validator config
	cp "$DEV_LIB_PATH"/l10n-validator/example-config.json wp-l10n-validator.json

	get-textdomain

	echo Updating textdomain to $text_domain in L10n validator config
	sed -i '' s/your-textdomain/"$text_domain"/ wp-l10n-validator.json
fi

# EOF
