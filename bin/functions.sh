#!/usr/bin/env bash

#*
# Get the project's textdomain.
#
# @var string $text_domain The project's textdomain.
#/
get-textdomain () {

	text_domain=$(grep -oh "Text Domain: .*" src/*.php)
	text_domain=${text_domain#"Text Domain: "}

	if [[ $text_domain == '' ]]; then
		echo Please enter the textdomain for your project:
		read text_domain
	fi
}

# EOF