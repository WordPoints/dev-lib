#!/usr/bin/env bash

year=$(date "+%y")
main_file=$(grep -l "Version:" src/*.php)

sed -i '' -e 's/\([cC]opyright *[0-9]*\)\(-\{0,1\}[0-9]*\)/\1-'"$year"'/' "$main_file"

# EOF
