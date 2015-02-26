#!/bin/bash

text_domain=$(grep -oh "Text Domain: .*" src/*.php)
text_domain=${text_domain#"Text Domain: "}

if [[ $text_domain == '' ]]; then
	echo Please enter the textdomain for your project:
	read text_domain
fi

echo Generating POT file for "$text_domain" textdomain
php "dev-lib/i18n/makepot.php" wordpoints-module src "src/languages/$text_domain.pot" "$text_domain"

echo 'Updating po/mo files (if any)'
for file in $(find "src/languages" -name '*.po' -type f); do
	msgmerge --backup=off --update "$file" "src/languages/$text_domain.pot"
	msgfmt -o "${file%po}mo" "$file"
done

echo Done.

# EOF
