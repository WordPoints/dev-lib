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
if [ ! -e .jshintignore ]; then
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

# Update the .gitignore file
if [ ! -e .gitignore ]; then

	echo Symlinking .gitignore file
	ln -s "$DEV_LIB_PATH"/git/.gitignore .gitignore

elif [ ! -L .gitignore ]; then

	# Make sure that the file ends in a newline, otherwise last line won't match.
	sed -i '' -e '$a\' .gitignore

	lines=$(diff "$DEV_LIB_PATH/git/.gitignore" .gitignore | grep "<" | sed 's/^< //g')

	if [[ $lines != '' ]]; then
		echo Updating .gitignore file
		echo "$lines" >> .gitignore
	fi
fi

# Symlink the Codeception configuration for Codeception testing.
if [ ! -e codeception.dist.yml ]; then
	echo Symlinking Codeception config
	ln -s "$DEV_LIB_PATH"/wpcept/codeception.dist.yml codeception.dist.yml
fi

# Copy the Codeception tests scaffold.
if [ ! -e tests/codeception ]; then
	echo Copying Codeception tests scaffold
	mkdir -p tests/codeception
	cp -r "$DEV_LIB_PATH"/wpcept/scaffold/* tests/codeception
	ln -s ../../"$DEV_LIB_PATH"/wpcept/bootstrap.php tests/codeception/bootstrap.php
fi

# Warn about a deprecated config file.
if [ -e .ci-env.sh ]; then
	echo "$(tput setaf 1)Warning:$(tput sgr 0) found deprecated .ci-env.sh config file"
	echo Use .wordpoints-dev-lib-config.sh instead
fi

# EOF
