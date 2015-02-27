#!/bin/bash

# Copy the Travis CI configuration file. This is copied rather than symlinked because
# Travis needs to be able to retrieve it from GitHub.
echo Copying Travis CI config
cp dev-lib/travis/module.yml .travis.yml

# Symlink the PHPCS configuration if you want to use PHPCS.
if [ ! -e phpcs.ruleset.xml ]; then
	echo Symlinking PHPCS config
	ln -s dev-lib/phpcs/WordPoints/ruleset.xml phpcs.ruleset.xml
fi

# Symlink the PHPUnit configuration for PHPUnit testing.
if [ ! -e phpunit.xml.dist ]; then
	echo Symlinking PHPUnit config
	ln -s dev-lib/phpunit/module.xml.dist phpunit.xml.dist
fi

# Symlink the Coveralls configuration file if you want to use code coverage.
if [ ! -e .coveralls.yml ]; then
	echo Symlinking Coveralls config
	ln -s dev-lib/travis/.coveralls.yml .
fi

# Copy the l10n validator configuration file.
if [ ! -e wp-l10n-validator.json ]; then
	echo Copying L10n Validator config
	cp dev-lib/l10n-validator/example-config.json wp-l10n-validator.json

	text_domain=$(grep -oh "Text Domain: .*" src/*.php)
	text_domain=${text_domain#"Text Domain: "}

	if [[ $text_domain == '' ]]; then
		echo Please enter the textdomain for your project:
		read text_domain
	fi

	echo Updating textdomain to $text_domain in L10n validator config
	sed -i '' s/your-textdomain/"$text_domain"/ wp-l10n-validator.json
fi

# EOF
