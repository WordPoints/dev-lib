#!/bin/bash

# Set up for the PHPUnit pass.
setup-phpunit() {

	if [[ $TRAVIS_PHP_VERSION == '5.2' && $RUN_UNINSTALL_TESTS == 1 ]]; then

		mkdir -p vendor/jdgrimes/wp-plugin-uninstall-tester
		curl -L https://github.com/JDGrimes/wp-plugin-uninstall-tester/archive/master.tar.gz \
			| tar xvz --strip-components=1 -C vendor/jdgrimes/wp-plugin-uninstall-tester

		if [[ $WORDPOINTS_PROJECT_TYPE == module ]]; then
			mkdir -p vendor/wordpoints/module-uninstall-tester
			curl -L https://github.com/WordPoints/module-uninstall-tester/archive/master.tar.gz \
				| tar xvz --strip-components=1 -C vendor/wordpoints/module-uninstall-tester
		fi

	elif [[ $TRAVIS_PHP_VERSION == hhvm ]] && [ -e .coveralls.yml ]; then
		composer require satooshi/php-coveralls:dev-master
		mkdir -p build/logs
	else
		composer install
	fi

    wget -O /tmp/install-wp-tests.sh \
        https://raw.githubusercontent.com/wp-cli/wp-cli/master/templates/install-wp-tests.sh

    sed -i 's/$WP_VERSION == '"'"'latest'"'"'/$WP_VERSION == '"'"'stable'"'"'/' \
    	/tmp/install-wp-tests.sh

    bash /tmp/install-wp-tests.sh wordpress_test root '' localhost "$WP_VERSION"

 	if [[ $RUN_AJAX_TESTS == 1 ]]; then
		sed -i 's/do_action( '"'"'admin_init'"'"' )/if ( ! isset( $GLOBALS['"'"'_did_admin_init'"'"'] ) \&\& $GLOBALS['"'"'_did_admin_init'"'"'] = true ) do_action( '"'"'admin_init'"'"' )/' \
			/tmp/wordpress-tests/includes/testcase-ajax.php
	fi

	if [[ $WORDPOINTS_PROJECT_TYPE == module ]]; then

		# Install WordPoints.
		mkdir -p /tmp/wordpoints
		curl -L https://github.com/WordPoints/wordpoints/archive/master.tar.gz \
			| tar xvz --strip-components=1 -C /tmp/wordpoints
		ln -s /tmp/wordpoints/src /tmp/wordpress/wp-content/plugins/wordpoints

		export WORDPOINTS_TESTS_DIR=/tmp/wordpoints/tests/phpunit/

		mkdir /tmp/wordpress/wp-content/wordpoints-modules
		ln -s "$(pwd)/src" /tmp/wordpress/wp-content/wordpoints-modules/"$PROJECT_SLUG"

	else
		cd /tmp/wordpress/wp-content/plugins
		ln -s "$PLUGIN_DIR" "$PLUGIN_SLUG"
		cd "$PLUGIN_DIR"
	fi
}

# Set up for the codesniff pass.
setup-codesniff() {

	# Install PHP_CodeSniffer.
    mkdir -p "$PHPCS_DIR"
    curl -L "https://github.com/$PHPCS_GITHUB_SRC/archive/$PHPCS_GIT_TREE.tar.gz" \
        | tar xvz --strip-components=1 -C "$PHPCS_DIR"

	# Install WordPress Coding Standards for PHPCS.
    mkdir -p "$WPCS_DIR"
    curl -L "https://github.com/$WPCS_GITHUB_SRC/archive/$WPCS_GIT_TREE.tar.gz" \
    	| tar xvz --strip-components=1 -C "$WPCS_DIR"

    "$PHPCS_DIR"/scripts/phpcs --config-set installed_paths "$WPCS_DIR"

	# Install JSHint.
	if ! command -v jshint >/dev/null 2>&1; then
		npm install -g jshint
	fi

	if [ -e composer.json ]; then
		composer install
	fi
}

# Check php files for syntax errors.
codesniff-php-syntax() {
	if [[ $TRAVISCI_RUN == codesniff ]] || [[ $TRAVISCI_RUN == phpunit && $WP_VERSION == stable && $TRAVIS_PHP_VERSION != '5.3' ]]; then
		find $CODESNIFF_PATH \( -name '*.php' -o -name '*.inc' \) \
			-exec php -lf {} \;
	else
		echo 'Not running PHP syntax check.'
	fi
}

# Check php files with PHPCodeSniffer.
codesniff-phpcs() {
	if [[ $TRAVISCI_RUN == codesniff ]]; then
		$PHPCS_DIR/scripts/phpcs -ns --standard=$WPCS_STANDARD \
			$(find $CODESNIFF_PATH -name '*.php')
	else
		echo 'Not running PHPCS.'
	fi
}

# Check JS files with jshint.
codesniff-jshint() {
	if [[ $TRAVISCI_RUN == codesniff ]]; then
		jshint .
	else
		echo 'Not running jshint.'
	fi
}

# Check PHP files for proper localization.
codesniff-l10n() {
	if [[ $TRAVISCI_RUN == codesniff ]]; then
		./vendor/jdgrimes/wp-l10n-validator/bin/wp-l10n-validator
	else
		echo 'Not running wp-l10n-validator.'
	fi
}

# Check XML files for syntax errors.
codesniff-xmllint() {
	if [[ $TRAVISCI_RUN == codesniff ]]; then
		xmllint --noout $(find $CODESNIFF_PATH \( -name '*.xml' -o -name '*.xml.dist' \))
	else
		echo 'Not running xmlint.'
	fi
}

# Run basic PHPUnit tests.
phpunit-basic() {
	if [[ $TRAVISCI_RUN != phpunit ]]; then
		echo 'Not running PHPUnit.'
		return
	fi

	local TEST_GROUP=${1-''}
	local CLOVER_FILE=${2-basic}

	local GROUP_OPTION=''
	local COVERAGE_OPTION=''

	if [[ $TEST_GROUP != '' ]]; then
		if [[ $TEST_GROUP == ajax && $RUN_AJAX_TESTS == 0 ]]; then
			echo 'Not running Ajax tests.'
			return
		elif [[ $TEST_GROUP == uninstall && $RUN_UNINSTALL_TESTS == 0 ]]; then
			echo 'Not running uninstall tests.'
			return
		fi

		if [[ $WP_VERSION == '3.8' && $TEST_GROUP == ajax && $WP_MULTISITE == 1 ]]; then
			echo 'Not running multisite Ajax tests on 3.8, see https://github.com/WordPoints/wordpoints/issues/239.'
			return
		fi

		GROUP_OPTION="--group=$TEST_GROUP"
		CLOVER_FILE+="-$TEST_GROUP"

		if [[ $TRAVIS_PHP_VERSION == '5.2' ]]; then
			sed -i '' -e "s/<group>$TEST_GROUP<\/group>//" ./phpunit.xml.dist
		fi
	fi

	if [[ $TRAVIS_PHP_VERSION == hhvm ]]; then
		COVERAGE_OPTION="--coverage-clover build/logs/clover-$CLOVER_FILE.xml"
	fi

	phpunit $GROUP_OPTION $COVERAGE_OPTION
}

# Run uninstall PHPUnit tests.
phpunit-uninstall() {
	phpunit-basic uninstall
}

# Run Ajax PHPUnit tests.
phpunit-ajax() {
	phpunit-basic ajax
}

# Run the basic tests on multisite.
phpunit-ms() {
	WP_MULTISITE=1 phpunit-basic '' ms
}

# Run the uninstall tests on multisite.
phpunit-ms-uninstall() {
	WP_MULTISITE=1 phpunit-basic uninstall ms
}

# Run the ajax tests on multisite.
phpunit-ms-ajax() {
	WP_MULTISITE=1 phpunit-basic ajax ms
}

# Run basic tests for multisite in network mode.
phpunit-ms-network() {
	WORDPOINTS_NETWORK_ACTIVE=1 WP_MULTISITE=1 phpunit-basic '' ms-network
}

# Run uninstall tests in multisite in network mode.
phpunit-ms-network-uninstall() {
	WORDPOINTS_NETWORK_ACTIVE=1 WP_MULTISITE=1 phpunit-basic uninstall ms-network
}

# Run Ajax tests in multisite in network mode.
phpunit-ms-network-ajax() {
	WORDPOINTS_NETWORK_ACTIVE=1 WP_MULTISITE=1 phpunit-basic ajax ms-network
}
