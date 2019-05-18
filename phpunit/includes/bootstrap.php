<?php

/**
 * Set up environment for WordPoints PHPUnit test suite.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

if ( ! getenv( 'WORDPOINTS_TESTS_DIR' ) ) {
	echo( 'WORDPOINTS_TESTS_DIR is not set.' . PHP_EOL );
	exit( 1 );
}

/**
 * The WordPoints tests directory.
 *
 * @since 2.6.0
 *
 * @const WORDPOINTS_TESTS_DIR
 */
define( 'WORDPOINTS_TESTS_DIR', rtrim( getenv( 'WORDPOINTS_TESTS_DIR' ), '/' ) );

/**
 * The cache directory for the WP HTTP testcase.
 *
 * @since 2.7.0
 *
 * @type string
 */
define( 'WP_HTTP_TC_CACHE_DIR', WORDPOINTS_TESTS_DIR . '/cache/wp-http-testcase' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound

if ( ! defined( 'WORDPOINTS_DEV_LIB_PHPUNIT_DIR' ) ) {

	/**
	 * The WordPoints tests directory.
	 *
	 * @since 2.6.0
	 *
	 * @type string
	 */
	define( 'WORDPOINTS_DEV_LIB_PHPUNIT_DIR', dirname( dirname( __FILE__ ) ) );

	/**
	 * Class autoloader for PHPUnit tests and helpers from the dev lib.
	 *
	 * @since 2.6.0
	 */
	require_once dirname( __FILE__ ) . '/../classes/class/autoloader.php';
}

/**
 * Miscellaneous utility functions.
 *
 * Loaded before WordPress, for backward compatibility with pre-2.2.0.
 *
 * @since 2.6.0
 */
require_once WORDPOINTS_TESTS_DIR . '/includes/functions.php';

WordPoints_PHPUnit_Class_Autoloader::register_dir(
	WORDPOINTS_TESTS_DIR . '/tests/'
	, 'WordPoints_'
);

WordPoints_PHPUnit_Class_Autoloader::register_dir(
	WORDPOINTS_TESTS_DIR . '/tests/classes/'
	, 'WordPoints_'
);

WordPoints_PHPUnit_Class_Autoloader::register_dir(
	WORDPOINTS_TESTS_DIR . '/tests/points/classes/'
	, 'WordPoints_Points_'
);

WordPoints_PHPUnit_Class_Autoloader::register_dir(
	WORDPOINTS_DEV_LIB_PHPUNIT_DIR . '/classes/'
	, 'WordPoints_PHPUnit_'
);

// We don't include the autoloader for our Composer dependencies when running the
// extension tests, because it might not have been generated, and shouldn't be needed.
if ( ! defined( 'RUNNING_WORDPOINTS_EXTENSION_TESTS' ) ) {
	/**
	 * The Composer generated autoloader.
	 *
	 * @since 2.6.0
	 */
	require_once WORDPOINTS_TESTS_DIR . '/../../vendor/autoload_52.php';
}

$wordpoints_loader = WordPoints_PHPUnit_Bootstrap_Loader::instance();
$wordpoints_loader->add_plugin(
	'wordpoints/wordpoints.php'
	, getenv( 'WORDPOINTS_NETWORK_ACTIVE' )
);

$wordpoints_loader->add_component( 'ranks' );

$wordpoints_loader->load_wordpress();

/**
 * Include the plugin's constants so that we can access the current version.
 *
 * @since 2.6.0
 */
require_once WORDPOINTS_TESTS_DIR . '/../../src/includes/constants.php';

// Autoload deprecated classes, for back-compat with pre-2.2.0.
spl_autoload_register( 'wordpoints_phpunit_deprecated_class_autoloader' );

// EOF
