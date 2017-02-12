<?php

/**
 * PHPUnit tests bootstrap for a module.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 1.0.0
 */

if ( ! getenv( 'WP_TESTS_DIR' ) ) {
	echo( 'WP_TESTS_DIR is not set.' . PHP_EOL );
	exit( 1 );
} elseif ( ! getenv( 'WORDPOINTS_TESTS_DIR' ) ) {
	echo( 'WORDPOINTS_TESTS_DIR is not set.' . PHP_EOL );
	exit( 1 );
}

/**
 * The WordPoints Dev Lib PHPUnit bootstrap directory.
 *
 * @since 2.6.0
 *
 * @type string
 */
define( 'WORDPOINTS_DEV_LIB_PHPUNIT_DIR', dirname( __FILE__ ) );

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
 * The class autoloader for the PHPUnit helper classes.
 *
 * @since 2.4.0
 */
require_once( dirname( __FILE__ ) . '/classes/class/autoloader.php' );

WordPoints_PHPUnit_Class_Autoloader::register_dir(
	WORDPOINTS_DEV_LIB_PHPUNIT_DIR . '/classes/'
	, 'WordPoints_PHPUnit_'
);

WordPoints_PHPUnit_Class_Autoloader::register_dir(
	WORDPOINTS_DEV_LIB_PHPUNIT_DIR . '/classes-deprecated/'
	, 'WordPoints_Dev_Lib_PHPUnit_'
);

/**
 * The bootstrap's utility functions.
 *
 * @since 1.1.0
 */
require_once( WORDPOINTS_DEV_LIB_PHPUNIT_DIR . '/functions.php' );

// This is mainly left here for back-compat with pre 2.5.0 behavior.
$has_uninstall_tester = is_dir( WORDPOINTS_MODULE_TESTS_DIR . '/../../vendor/wordpoints/module-uninstall-tester/' );

if ( $has_uninstall_tester ) {

	/**
	 * The plugin uninstall testing functions.
	 *
	 * @since 1.1.0
	 */
	require_once( WORDPOINTS_MODULE_TESTS_DIR . '/../../vendor/jdgrimes/wp-plugin-uninstall-tester/includes/functions.php' );

	/**
	 * The WordPoints modules uninstall testing functions.
	 *
	 * @since 1.1.0
	 */
	require_once( WORDPOINTS_MODULE_TESTS_DIR . '/../../vendor/wordpoints/module-uninstall-tester/includes/functions.php' );
}

/**
 * The WordPress tests functions.
 *
 * We are loading this for legacy reasons. We used to have to include it to load the
 * module and WordPoints using tests_add_filter().
 *
 * @since 1.0.0
 */
require_once( getenv( 'WP_TESTS_DIR' ) . '/includes/functions.php' );

if ( file_exists( WORDPOINTS_MODULE_TESTS_DIR . '/../../vendor/autoload_52.php' ) ) {

	/**
	 * The PHP 5.2 compatible Composer autoloader for the module's dependencies.
	 *
	 * @since 2.5.0
	 */
	require_once( WORDPOINTS_MODULE_TESTS_DIR . '/../../vendor/autoload_52.php' );

} elseif ( file_exists( WORDPOINTS_MODULE_TESTS_DIR . '/../../vendor/autoload.php' ) ) {

	/**
	 * The Composer generated autoloader for the module's dependencies.
	 *
	 * @since 2.5.0
	 */
	require_once( WORDPOINTS_MODULE_TESTS_DIR . '/../../vendor/autoload.php' );
}

if ( class_exists( 'WPPPB_Loader' ) ) {

	/**
	 * WordPoints's PHPUnit loader class.
	 *
	 * @since 2.5.0
	 */
	require_once( WORDPOINTS_DEV_LIB_PHPUNIT_DIR . '/classes/bootstrap/loader.php' );
}

if ( file_exists( WORDPOINTS_MODULE_TESTS_DIR . '/includes/functions.php' ) ) {

	/**
	 * The module's testing functions.
	 *
	 * @since 1.0.0
	 */
	require_once( WORDPOINTS_MODULE_TESTS_DIR . '/includes/functions.php' );
}

if (
	class_exists( 'WordPoints_PHPUnit_Bootstrap_Loader' )
	&& ! defined( 'WORDPOINTS_MODULE_TESTS_LOADER' )
) {

	$loader = WordPoints_PHPUnit_Bootstrap_Loader::instance();
	$loader->add_module(
		wordpoints_dev_lib_the_module_basename()
		, getenv( 'WORDPOINTS_MODULE_NETWORK_ACTIVE' )
	);

} elseif ( ! $has_uninstall_tester || ! running_wordpoints_module_uninstall_tests() ) {

	// Hook to load WordPoints.
	tests_add_filter( 'muplugins_loaded', 'wordpointstests_manually_load_plugin' );

	// Hook to load the module.
	if ( defined( 'WORDPOINTS_MODULE_TESTS_LOADER' ) ) {
		$module_loader = WORDPOINTS_MODULE_TESTS_LOADER;
	} else {
		$module_loader = 'wordpoints_dev_lib_load_the_module';
	}

	tests_add_filter( 'plugins_loaded', $module_loader, 14 );
}

/**
 * The WordPoints tests bootstrap.
 *
 * @since 1.0.0
 */
require( WORDPOINTS_DEV_LIB_PHPUNIT_DIR . '/includes/bootstrap.php' );

if ( file_exists( getenv( 'WP_TESTS_DIR' ) . '/includes/speed-trap-listener.php' ) ) {

	/**
	 * The speed trap listener from WordPress's test suite.
	 *
	 * @since 1.0.0
	 */
	require_once( getenv( 'WP_TESTS_DIR' ) . '/includes/speed-trap-listener.php' );
}

if ( $has_uninstall_tester ) {

	/**
	 * The plugin uninstall testing bootstrap.
	 *
	 * @since 1.1.0
	 */
	require_once( WORDPOINTS_MODULE_TESTS_DIR . '/../../vendor/jdgrimes/wp-plugin-uninstall-tester/bootstrap.php' );

	/**
	 * The WordPoints modules uninstall testing bootstrap.
	 *
	 * @since 1.1.0
	 */
	require_once( WORDPOINTS_MODULE_TESTS_DIR . '/../../vendor/wordpoints/module-uninstall-tester/bootstrap.php' );
}

if (
	( ! $has_uninstall_tester || ! running_wordpoints_module_uninstall_tests() )
	&& ( ! isset( $loader ) || ! $loader->running_uninstall_tests() )
	&& file_exists( WORDPOINTS_MODULE_TESTS_DIR . '/../../src/admin/admin.php' )
) {

	/**
	 * The module's admin-side code.
	 *
	 * @since 1.0.0
	 */
	require_once( WORDPOINTS_MODULE_TESTS_DIR . '/../../src/admin/admin.php' );
}

if ( file_exists( WORDPOINTS_MODULE_TESTS_DIR . '/includes/bootstrap.php' ) ) {

	/**
	 * The module's tests bootstrap.
	 *
	 * @since 1.0.0
	 */
	require_once( WORDPOINTS_MODULE_TESTS_DIR . '/includes/bootstrap.php' );
}

// EOF
