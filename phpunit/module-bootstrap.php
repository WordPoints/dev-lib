<?php

/**
 * PHPUnit tests bootstrap for a module.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 1.0.0
 */

if ( ! getenv( 'WP_TESTS_DIR' ) ) {
	exit( 'WP_TESTS_DIR is not set.' . PHP_EOL );
} elseif ( ! getenv( 'WORDPOINTS_TESTS_DIR' ) ) {
	exit( 'WORDPOINTS_TESTS_DIR is not set.' . PHP_EOL );
}

/**
 * We're running tests for a module.
 *
 * We need to tell WordPoints' tests bootstrap this so that it won't load it's plugin
 * uninstall tester.
 *
 * @since 1.0.0
 */
define( 'RUNNING_WORDPOINTS_MODULE_TESTS', true );

/**
 * The path to the tests of the module being tested.
 *
 * @since 1.0.0
 */
define( 'WORDPOINTS_MODULE_TESTS_DIR', dirname( dirname( dirname( __FILE__ ) ) ) . '/tests/phpunit' );

/**
 * The WordPress tests functions.
 *
 * We are loading this so that we can add our tests filter to load the module and
 * WordPoints, using tests_add_filter().
 *
 * @since 1.0.0
 */
require_once( getenv( 'WP_TESTS_DIR' ) . '/includes/functions.php');

/**
 * The module's testing functions.
 *
 * @since 1.0.0
 */
require_once( 'WORDPOINTS_MODULE_TESTS_DIR' . '/includes/functions.php' );

if (
	! function_exists( 'running_wordpoints_module_uninstall_tests' )
	|| ! running_wordpoints_module_uninstall_tests()
) {

	// Hook to load WordPoints.
	tests_add_filter( 'muplugins_loaded', 'wordpointstests_manually_load_plugin' );

	// Hook to load the module.
	tests_add_filter( 'wordpoints_modules_loaded', WORDPOINTS_MODULE_TESTS_LOADER, 5 );
}

/**
 * The WordPoints tests bootstrap.
 *
 * @since 1.0.0
 */
require( getenv( 'WORDPOINTS_TESTS_DIR' ) . '/includes/bootstrap.php' );

/**
 * The module's tests bootstrap.
 *
 * @since 1.0.0
 */
require_once( 'WORDPOINTS_MODULE_TESTS_DIR' . '/includes/bootstrap.php' );

// EOF
