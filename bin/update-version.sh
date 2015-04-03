#!/usr/bin/env bash

if [ -z $2 ];
	then echo "Usage: $0 $1 <new-version>";
	exit;
fi

new_version=$2

main_file=$(grep -l "Version:" src/*.php)

sed -i '' -e 's/\(\* @\{0,1\}[Vv]\{1\}ersion:\{0,1\} *\)\([^\n ]*\)/\1'"$new_version"'/' \
	"$main_file";

if [ -e ./src/includes/constants.php ]; then
	sed -i '' -e 's/VERSION'"'"', '"'"'[^'"'"']*/VERSION'"'"', '"'""$new_version"'/' \
		./src/includes/constants.php
fi

# EOF
