#!/bin/bash

# Install composer dependencies.
setup-composer() {

	if [ ! -e composer.json ]; then
		return
	fi

	if [[ $TRAVIS_PHP_VERSION == '5.2' ]]; then
		phpenv global 5.3
		composer install
		phpenv global "$TRAVIS_PHP_VERSION"
	elif [[ $DO_CODE_COVERAGE == 1 && $TRAVISCI_RUN == phpunit ]]; then
		composer require satooshi/php-coveralls:dev-master
		mkdir -p build/logs
	else
		composer install
	fi
}

# Install a package from GitHub.
install-from-github() {

	local GITHUB_SRC=${1}_GITHUB_SRC
	local GITHUB_TREE=${1}_GITHUB_TREE
	local DIR=${1}_DIR

    mkdir -p "$DIR"
    curl -L "https://github.com/${!GITHUB_SRC}/archive/${!GITHUB_TREE}.tar.gz" \
        | tar xvz --strip-components=1 -C "${!DIR}"
}

# Set up for the PHPUnit pass.
setup-phpunit() {

	setup-composer

    wget -O /tmp/install-wp-tests.sh \
        https://raw.githubusercontent.com/wp-cli/wp-cli/master/templates/install-wp-tests.sh

	if [[ $WP_VERSION == 'nightly' ]]; then
		sed -i "s/\${ARCHIVE_NAME}.tar.gz/nightly-builds\/wordpress-latest.zip/" \
			/tmp/install-wp-tests.sh

		sed -i 's/wordpress.tar.gz/wordpress.zip/' /tmp/install-wp-tests.sh
		sed -i 's/tar --strip-components=1 -zxmf \/tmp\/wordpress.zip -C $WP_CORE_DIR/unzip \/tmp\/wordpress.zip -d \/tmp/' \
			/tmp/install-wp-tests.sh
	fi

	bash /tmp/install-wp-tests.sh wordpress_test root '' localhost "$WP_VERSION"

 	if [[ $RUN_AJAX_TESTS == 1 ]]; then
		sed -i 's/do_action( '"'"'admin_init'"'"' )/if ( ! isset( $GLOBALS['"'"'_did_admin_init'"'"'] ) \&\& $GLOBALS['"'"'_did_admin_init'"'"'] = true ) do_action( '"'"'admin_init'"'"' )/' \
			/tmp/wordpress-tests/includes/testcase-ajax.php
	fi

	if [[ $WORDPOINTS_PROJECT_TYPE == module ]]; then

		# Install WordPoints.
		mkdir -p /tmp/wordpoints
		curl -L "https://github.com/WordPoints/wordpoints/archive/$WORDPOINTS_VERSION.tar.gz" \
			| tar xvz --strip-components=1 -C /tmp/wordpoints
		ln -s /tmp/wordpoints/src /tmp/wordpress/wp-content/plugins/wordpoints

		export WORDPOINTS_TESTS_DIR=/tmp/wordpoints/tests/phpunit/

		mkdir /tmp/wordpress/wp-content/wordpoints-modules
		ln -s "$PROJECT_DIR" /tmp/wordpress/wp-content/wordpoints-modules/"$PROJECT_SLUG"

	else
		ln -s "$PROJECT_DIR" /tmp/wordpress/wp-content/plugins/"$PROJECT_SLUG"
	fi
}

# Set up for the codesniff pass.
setup-codesniff() {

	setup-composer

	# Install JSHint.
	if ! command -v jshint >/dev/null 2>&1; then
		npm install -g jshint
	fi

	if [[ $DO_PHPCS != 1 ]]; then
		return;
	fi

	# Install PHP_CodeSniffer.
	install-from-github PHPCS

	# Install WordPress Coding Standards for PHPCS.
	install-from-github WPCS

	# Configure PHPCS to use WPCS.
	"$PHPCS_DIR"/scripts/phpcs --config-set installed_paths "$WPCS_DIR","$DEV_LIB_PATH"/phpcs

	# Install WP L10n Validator.
	install-from-github WPL10NV
	
	# Install WP L10n Validator config.
	install-from-github WPL10NV_CONFIG
}

# Check php files for syntax errors.
codesniff-php-syntax() {
	if [[ $TRAVISCI_RUN == codesniff ]] || [[ $TRAVISCI_RUN == phpunit && $WP_VERSION == latest && $TRAVIS_PHP_VERSION != '5.3' ]]; then
		find "${CODESNIFF_PATH[@]}" \( -name '*.php' -o -name '*.inc' \) -exec php -lf {} \;
	else
		echo 'Not running PHP syntax check.'
	fi
}

# Check php files with PHPCodeSniffer.
codesniff-phpcs() {
	if [[ $TRAVISCI_RUN == codesniff && $DO_PHPCS == 1 ]]; then
		"$PHPCS_DIR"/scripts/phpcs -ns --standard="$WPCS_STANDARD" \
			$(find "${CODESNIFF_PATH[@]}" -name '*.php')
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
		xmllint --noout $(find "${CODESNIFF_PATH[@]}" -type f \( -name '*.xml' -o -name '*.xml.dist' \))
	else
		echo 'Not running xmlint.'
	fi
}

# Check bash files for syntax errors.
codesniff-bash() {
	if [[ $TRAVISCI_RUN == codesniff ]]; then
		find "${CODESNIFF_PATH[@]}" -name '*.sh' -exec bash -n {} \;
	else
		echo 'Not running bash syntax check.'
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

	local GROUP_OPTION=()
	local COVERAGE_OPTION=()

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

		GROUP_OPTION=(--group="$TEST_GROUP")
		CLOVER_FILE+="-$TEST_GROUP"

		if [[ $TRAVIS_PHP_VERSION == '5.2' ]]; then
			sed -i '' -e "s/<group>$TEST_GROUP<\/group>//" ./phpunit.xml.dist
		fi
	fi

	if [[ $DO_CODE_COVERAGE == 1 ]]; then
		COVERAGE_OPTION=(--coverage-clover "build/logs/clover-$CLOVER_FILE.xml")
	fi

	phpunit "${GROUP_OPTION[@]}" "${COVERAGE_OPTION[@]}"
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
