#!/usr/bin/env bash

if [ -z $2 ];
	then echo "Usage: $0 $1 <new-version>";
	exit;
fi

new_version=$2

main_file=$(grep -l "Version:" src/*.php)

sed -i '' -e 's/\(@version *\)\([^\n ]*\)/\1'"$new_version"'/' "$main_file";
sed -i '' -e 's/\(Version: *\)\([^\n ]*\)/\1'"$new_version"'/' "$main_file";

if [ -e ./src/includes/constants.php ]; then
	sed -i '' -e 's/VERSION'"'"', '"'"'[^'"'"']*/VERSION'"'"', '"'""$new_version"'/' \
		./src/includes/constants.php
fi

if [ -e ./package.json ]; then
	sed -i '' -e 's/"version": "[^"]*"/"version": "'"${new_version}"'"/' ./package.json
fi

# Also update the copyright year.
"$DEV_LIB_PATH"/bin/update-year.sh

# EOF
