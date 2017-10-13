#!/usr/bin/env bash

if [ ! -e .wordpoints-dev-lib-config.sh ]; then
    echo 'No configuration file (.wordpoints-dev-lib-config.sh) found.'
    echo Assuming default configuration: DEV_LIB_PATH=dev-lib WORDPOINTS_PROJECT_TYPE=extension
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
	ln -s "$DEV_LIB_PATH"/wpcept/"$WORDPOINTS_PROJECT_TYPE".yml codeception.dist.yml
fi

# Copy the Codeception tests scaffold.
if [ ! -e tests/codeception ]; then
	echo Copying Codeception tests scaffold
	mkdir -p tests/codeception
	cp -r "$DEV_LIB_PATH"/wpcept/scaffold/* tests/codeception
fi

# Copy the default Grunt file.
if [ ! -e Gruntfile.js ]; then
	echo Copying Grunt configuration file
	cp "$DEV_LIB_PATH"/grunt/Gruntfile.js ./

	namespace=$(grep -oh "Namespace: .*" src/*.php)
	namespace=${namespace#"Namespace: "}
	namespace=${namespace##* }
	namespace=$(echo "$namespace" | tr '[:upper:]' '[:lower:]')

	sed -i '' "s/%class_prefix%/wordpoints_${namespace}_/" Gruntfile.js

	if [ ! -e package.json ]; then
		cp "$DEV_LIB_PATH"/grunt/package.json ./
	fi
fi

# Warn about a deprecated config file.
if [ -e .ci-env.sh ]; then
	echo "$(tput setaf 1)Warning:$(tput sgr 0) found deprecated .ci-env.sh config file"
	echo Use .wordpoints-dev-lib-config.sh instead
fi

# Set up PHPUnit tests.
if [ -e composer.json ]; then
	if ! grep -q jdgrimes/wpppb composer.json; then
		echo Adding WPPPB to composer
		composer require --dev jdgrimes/wpppb
	else
		composer require --dev jdgrimes/wpppb:^0.3.0
	fi
else
	echo Copying composer.json
	cp "$DEV_LIB_PATH"/phpunit/composer.json .
fi

"$DEV_LIB_PATH"/bin/set-up.sh

if [[ $WORDPOINTS_PROJECT_TYPE == extension ]]; then
	"$DEV_LIB_PATH"/phpunit/wpppb-init
else
	vendor/bin/wpppb-init
fi

# EOF
