#!/bin/bash

# Install composer dependencies.
setup-composer() {

	# We always need to do this when collecting code coverage, even if there are no
	# composer dependencies.
	if [[ $DO_CODE_COVERAGE == 1 && $TRAVISCI_RUN == phpunit ]]; then
		composer require --prefer-source satooshi/php-coveralls:0.7.0
		mkdir -p build/logs
		return;
	fi

	# No dependencies, no need to continue.
	if [ ! -e composer.json ]; then
		return
	fi

	# Composer requires PHP 5.3.
	if [[ $TRAVIS_PHP_VERSION == '5.2' ]]; then
		phpenv global 5.3
		composer install --prefer-source
		phpenv global "$TRAVIS_PHP_VERSION"
	else
		composer install --prefer-source
	fi
}

# Install a package from GitHub.
install-from-github() {

	local GITHUB_SRC=${1}_GITHUB_SRC
	local GIT_TREE=${1}_GIT_TREE
	local DIR=${1}_DIR

    mkdir -p "${!DIR}"
    curl -L "https://github.com/${!GITHUB_SRC}/archive/${!GIT_TREE}.tar.gz" \
        | tar xvz --strip-components=1 -C "${!DIR}"
}

# Set up for the PHPUnit pass.
setup-phpunit() {

	setup-composer

	mkdir -p "$WP_DEVELOP_DIR"

	# Back-compat.
	if [[ $WP_VERSION == 'nightly' ]]; then
		WP_VERSION=master
	elif [[ $WP_VERSION == 'latest' ]]; then
		WP_VERSION=4.2
	fi

	# Clone the WordPress develop repo.
	git clone --depth=1 --branch="$WP_VERSION" git://develop.git.wordpress.org/ "$WP_DEVELOP_DIR"

	# Set up tests config.
	cd "$WP_DEVELOP_DIR"
	cp wp-tests-config-sample.php wp-tests-config.php
	sed -i "s/youremptytestdbnamehere/wordpress_test/" wp-tests-config.php
	sed -i "s/yourusernamehere/root/" wp-tests-config.php
	sed -i "s/yourpasswordhere//" wp-tests-config.php
	cd -

	# Set up database.
	mysql -e 'CREATE DATABASE wordpress_test;' -uroot

 	if [[ $RUN_AJAX_TESTS == 1 ]]; then
		sed -i 's/do_action( '"'"'admin_init'"'"' )/if ( ! isset( $GLOBALS['"'"'_did_admin_init'"'"'] ) \&\& $GLOBALS['"'"'_did_admin_init'"'"'] = true ) do_action( '"'"'admin_init'"'"' )/' \
			"$WP_TESTS_DIR"/includes/testcase-ajax.php
	fi

	if [[ $WORDPOINTS_PROJECT_TYPE == module ]]; then

		# Install WordPoints.
		mkdir -p "$WORDPOINTS_DEVELOP_DIR"
		curl -L "https://github.com/WordPoints/wordpoints/archive/$WORDPOINTS_VERSION.tar.gz" \
			| tar xvz --strip-components=1 -C "$WORDPOINTS_DEVELOP_DIR"
		ln -s  "$WORDPOINTS_DEVELOP_DIR"/src "$WP_CORE_DIR"/wp-content/plugins/wordpoints

		mkdir "$WP_CORE_DIR"/wp-content/wordpoints-modules
		ln -s "$PROJECT_DIR" "$WP_CORE_DIR"/wp-content/wordpoints-modules/"$PROJECT_SLUG"

	else
		ln -s "$PROJECT_DIR" "$WP_CORE_DIR"/wp-content/plugins/"$PROJECT_SLUG"
	fi
}

# Set up for WP Browser (Codeception) tests.
setup-wpcept() {

	if [[ $DO_WP_CEPT == 0 ]]; then
		return
	fi

	composer require --prefer-source codeception/codeception:2.1.4
	composer require --prefer-source lucatume/wp-browser:1.10.11

	# We start the server up early so that it has time to prepare.
	php -S "$WP_CEPT_SERVER" -t "$WP_CORE_DIR" >/dev/null 2>&1 &

	# Start up the webdriver so that it has time to prepare as well.
	phantomjs --webdriver=4444 >/dev/null &
}

# Set up for the codesniff pass.
setup-codesniff() {

	# Install JSHint.
	if ! command -v jshint >/dev/null 2>&1; then
		npm install -g jshint
	fi

	if [[ $DO_PHPCS == 1 ]]; then
		# Install PHP_CodeSniffer.
		install-from-github PHPCS

		# Install WordPress Coding Standards for PHPCS.
		install-from-github WPCS

		# Configure PHPCS to use WPCS.
		"$PHPCS_DIR"/scripts/phpcs --config-set installed_paths "$WPCS_DIR","$DEV_LIB_PATH"/phpcs
	fi

	if [[ $DO_WPL10NV == 1 ]]; then
		# Install WP L10n Validator.
		install-from-github WPL10NV
	fi
}

# Check php files for syntax errors.
codesniff-php-syntax() {
	if [[ $TRAVISCI_RUN == codesniff ]] || [[ $TRAVISCI_RUN == phpunit && $WP_VERSION == master && $TRAVIS_PHP_VERSION != '5.3' ]]; then
		wpdl-codesniff-php-syntax
	else
		echo 'Not running PHP syntax check.'
	fi
}

# Check php autoloader fallback files for errors.
codesniff-php-autoloaders() {
	if [[ $TRAVISCI_RUN == codesniff ]] || [[ $TRAVISCI_RUN == phpunit && $WP_VERSION == master && $TRAVIS_PHP_VERSION != '5.3' ]]; then
		wpdl-codesniff-php-autoloaders
	else
		echo 'Not running PHP autoloader fallback file check.'
	fi
}

# Check php files with PHPCodeSniffer.
codesniff-phpcs() {
	if [[ $TRAVISCI_RUN == codesniff && $DO_PHPCS == 1 ]]; then
		wpdl-codesniff-phpcs
	else
		echo 'Not running PHPCS.'
	fi
}

# Check JS files with jshint.
codesniff-jshint() {
	if [[ $TRAVISCI_RUN == codesniff ]]; then
		wpdl-codesniff-jshint
	else
		echo 'Not running jshint.'
	fi
}

# Check PHP files for proper localization.
codesniff-l10n() {
	if [[ $TRAVISCI_RUN == codesniff && $DO_WPL10NV == 1 ]]; then
		wpdl-codesniff-l10n
	else
		echo 'Not running wp-l10n-validator.'
	fi
}

# Check XML files for syntax errors.
codesniff-xmllint() {
	if [[ $TRAVISCI_RUN == codesniff ]]; then
		wpdl-codesniff-xmllint
	else
		echo 'Not running xmlint.'
	fi
}

# Check bash files for syntax errors.
codesniff-bash() {
	if [[ $TRAVISCI_RUN == codesniff ]]; then
		wpdl-codesniff-bash
	else
		echo 'Not running bash syntax check.'
	fi
}

# Check bash files for syntax errors.
codesniff-symlinks() {
	if [[ $TRAVISCI_RUN == codesniff ]]; then
		wpdl-codesniff-symlinks
	else
		echo 'Not running broken symlink check.'
	fi
}

# Run basic PHPUnit tests.
phpunit-basic() {
	if [[ $TRAVISCI_RUN != phpunit ]]; then
		echo 'Not running PHPUnit.'
		return
	fi

	wpdl-test-phpunit "${@}"
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

# Run Codeception tests with WP Browser.
wpcept-run() {

	if [[ $DO_WP_CEPT == 0 ]]; then
		echo Not running codecept tests.
		return
	fi

	# Configure WordPress for access through a web server.
	# We don't do this during set up because it can mess up the PHPUnit tests.
	cd "$WP_DEVELOP_DIR"
	sed -i "s/example.org/$WP_CEPT_SERVER/" wp-tests-config.php
	cp wp-tests-config.php wp-config.php

	echo "
		if ( ! defined( 'WP_INSTALLING' ) && ( getenv( 'WP_MULTISITE' ) || file_exists( dirname( __FILE__ ) . '/is-multisite' ) ) ) {
			define( 'MULTISITE', true );
			define( 'SUBDOMAIN_INSTALL', false );
			\$GLOBALS['base'] = '/';
		}

		require_once(ABSPATH . 'wp-settings.php');
	" >> wp-config.php

	cd -

	wpcept-basic
}

# Run basic Codeception tests.
wpcept-basic() {

	if [[ $DO_WP_CEPT == 0 ]]; then
		echo Not running codecept tests.
		return
	fi

	vendor/bin/wpcept run "${@}"
}

# EOF
