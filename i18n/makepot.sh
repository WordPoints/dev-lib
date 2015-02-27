#!/bin/bash

# Get the project's textdomain.
text_domain=$(grep -oh "Text Domain: .*" src/*.php)
text_domain=${text_domain#"Text Domain: "}

if [[ $text_domain == '' ]]; then
	echo Please enter the textdomain for your project:
	read text_domain
fi

# Get the project type from .travis.yml
project_type=$(grep -o "WORDPOINTS_PROJECT_TYPE=.*" .travis.yml)
project_type=${text_domain#"WORDPOINTS_PROJECT_TYPE= "}

if [[ $project_type == module ]]; then
	project_type=wordpoints-module
fi

# Get the path to the makepot tool.
i18n_path=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

echo Generating POT file for "$text_domain" textdomain
php "$i18n_path/makepot.php" "$project_type" src "src/languages/$text_domain.pot" "$text_domain"

echo 'Updating po/mo files (if any)'
for file in $(find "src/languages" -name '*.po' -type f); do
	msgmerge --backup=off --update "$file" "src/languages/$text_domain.pot"
	msgfmt -o "${file%po}mo" "$file"
done

echo Done.

# EOF
