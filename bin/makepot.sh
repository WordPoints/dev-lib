#!/usr/bin/env bash

if [[ $WORDPOINTS_PROJECT_TYPE == wordpoints ]]; then
	project_type=$WORDPOINTS_PROJECT_TYPE
else
	project_type=wordpoints-$WORDPOINTS_PROJECT_TYPE
fi

get-textdomain

echo Generating POT file for "$text_domain" textdomain
php "$DEV_LIB_PATH/i18n/makepot.php" "$project_type" src "src/languages/$text_domain.pot" "$text_domain"

echo 'Updating po/mo files (if any)'
for file in $(find "src/languages" -name '*.po' -type f); do
	msgmerge --backup=off --update "$file" "src/languages/$text_domain.pot"
	msgfmt --use-fuzzy -o "${file%po}mo" "$file"
done

echo Done.

# EOF
