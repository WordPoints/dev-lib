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
	require_once( dirname( __FILE__ ) . '/../classes/class/autoloader.php' );
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
// module tests, because it might not have been generated, and shouldn't be needed.
if ( ! defined( 'RUNNING_WORDPOINTS_MODULE_TESTS' ) ) {
	/**
	 * The Composer generated autoloader.
	 *
	 * @since 2.6.0
	 */
	require_once( WORDPOINTS_TESTS_DIR . '/../../vendor/autoload_52.php' );
}

$loader = WordPoints_PHPUnit_Bootstrap_Loader::instance();
$loader->add_plugin(
	'wordpoints/wordpoints.php'
	, getenv( 'WORDPOINTS_NETWORK_ACTIVE' )
);

$loader->add_component( 'ranks' );

$loader->load_wordpress();

/**
 * Include the plugin's constants so that we can access the current version.
 *
 * @since 2.6.0
 */
require_once WORDPOINTS_TESTS_DIR . '/../../src/includes/constants.php';

// Autoload deprecated classes, for back-compat with pre-2.2.0.
spl_autoload_register( 'wordpoints_phpunit_deprecated_class_autoloader' );

// EOF
