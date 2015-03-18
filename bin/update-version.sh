#!/usr/bin/env bash

if [ -z $2 ];
	then echo "Usage: $0 $1 <new-version>";
	exit;
fi

main_file=$(grep -l "Version:" src/*.php)

sed -i '' -e 's/\(\* @\{0,1\}[Vv]\{1\}ersion:\{0,1\} *\)\([^\n ]*\)/\1'"$1"'/' \
	"$main_file";

if [ -e ./src/includes/constants.php ]; then
	sed -i '' -e 's/VERSION'"'"', '"'"'[^'"'"']*/VERSION'"'"', '"'""$1"'/' \
		./src/includes/constants.php
fi

# EOF
