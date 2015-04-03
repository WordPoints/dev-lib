#!/bin/bash

# Get the project type from .travis.yml
if [ -e .travis.yml ]; then
	project_type=$(grep -o "WORDPOINTS_PROJECT_TYPE=.*" .travis.yml)
	project_type=${project_type#"WORDPOINTS_PROJECT_TYPE="}
fi

# Ask to enter it manually if not found.
if [[ $project_type == '' ]]; then
	echo Please enter the project type: module or wordpoints?
	read project_type
fi

# Get the path to the dev_lib tool.
dev_lib_path=$( dirname "$( dirname "${BASH_SOURCE[0]}" )" )

# Copy the Travis CI configuration file. This is copied rather than symlinked because
# Travis needs to be able to retrieve it from GitHub.
echo Copying Travis CI config for $project_type
cp "$dev_lib_path"/travis/"$project_type".yml .travis.yml

# Symlink the PHPCS configuration if you want to use PHPCS.
if [ ! -e phpcs.ruleset.xml ]; then
	echo Symlinking PHPCS config
	ln -s "$dev_lib_path"/phpcs/WordPoints/ruleset.xml phpcs.ruleset.xml
fi

# Symlink the PHPUnit configuration for PHPUnit testing.
if [ ! -e phpunit.xml.dist ]; then
	echo Symlinking PHPUnit config
	ln -s "$dev_lib_path"/phpunit/"$project_type".xml.dist phpunit.xml.dist
fi

# Symlink the Coveralls configuration file if you want to use code coverage.
if [ ! -e .coveralls.yml ]; then
	echo Symlinking Coveralls config
	ln -s "$dev_lib_path"/travis/.coveralls.yml .
fi

# Copy the l10n validator configuration file.
if [ ! -e wp-l10n-validator.json ]; then
	echo Copying L10n Validator config
	cp "$dev_lib_path"/l10n-validator/example-config.json wp-l10n-validator.json

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
