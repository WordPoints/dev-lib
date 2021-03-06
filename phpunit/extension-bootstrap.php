<?php

/**
 * PHPUnit tests bootstrap for an extension.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.7.0
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
 * We're running tests for an extension.
 *
 * We need to tell WordPoints' tests bootstrap this so that it won't load it's plugin
 * uninstall tester.
 *
 * @since 1.0.0 As RUNNING_WORDPOINTS_MODULE_TESTS
 * @since 2.7.0
 */
define( 'RUNNING_WORDPOINTS_EXTENSION_TESTS', true ); // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound

/**
 * @since 1.0.0
 * @deprecated 2.7.0
 */
define( 'RUNNING_WORDPOINTS_MODULE_TESTS', true ); // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound

/**
 * The path to the tests of the extension being tested.
 *
 * @since 1.0.0 As WORDPOINTS_MODULE_TESTS_DIR
 * @since 2.7.0
 */
define( 'WORDPOINTS_EXTENSION_TESTS_DIR', dirname( dirname( dirname( __FILE__ ) ) ) . '/tests/phpunit' );

/**
 * @since 1.0.0
 * @deprecated 2.7.0
 */
define( 'WORDPOINTS_MODULE_TESTS_DIR', WORDPOINTS_EXTENSION_TESTS_DIR );

/**
 * The class autoloader for the PHPUnit helper classes.
 *
 * @since 2.4.0
 */
require_once dirname( __FILE__ ) . '/classes/class/autoloader.php';

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
require_once WORDPOINTS_DEV_LIB_PHPUNIT_DIR . '/functions.php';

$wordpoints_extension = new WordPoints_PHPUnit_Extension(
	WORDPOINTS_EXTENSION_TESTS_DIR . '/../../src'
);

if ( is_dir( WORDPOINTS_EXTENSION_TESTS_DIR . '/classes/' ) ) {

	$wordpoints_namespace = $wordpoints_extension->get_header( 'namespace' );

	if ( $wordpoints_namespace ) {
		WordPoints_PHPUnit_Class_Autoloader::register_dir(
			WORDPOINTS_EXTENSION_TESTS_DIR . '/classes/'
			, "WordPoints_{$wordpoints_namespace}_PHPUnit_"
		);
	}

	unset( $wordpoints_namespace );
}

// This is mainly left here for back-compat with pre 2.5.0 behavior.
$wordpoints_has_uninstall_tester = is_dir( WORDPOINTS_EXTENSION_TESTS_DIR . '/../../vendor/wordpoints/module-uninstall-tester/' );

if ( $wordpoints_has_uninstall_tester ) {

	/**
	 * The plugin uninstall testing functions.
	 *
	 * @since 1.1.0
	 */
	require_once WORDPOINTS_EXTENSION_TESTS_DIR . '/../../vendor/jdgrimes/wp-plugin-uninstall-tester/includes/functions.php';

	/**
	 * The WordPoints modules uninstall testing functions.
	 *
	 * @since 1.1.0
	 */
	require_once WORDPOINTS_EXTENSION_TESTS_DIR . '/../../vendor/wordpoints/module-uninstall-tester/includes/functions.php';
}

/**
 * The WordPress tests functions.
 *
 * We are loading this for legacy reasons. We used to have to include it to load the
 * extension and WordPoints using tests_add_filter().
 *
 * @since 1.0.0
 */
require_once getenv( 'WP_TESTS_DIR' ) . '/includes/functions.php';

if ( file_exists( WORDPOINTS_EXTENSION_TESTS_DIR . '/../../vendor/autoload_52.php' ) ) {

	/**
	 * The PHP 5.2 compatible Composer autoloader for the extension's dependencies.
	 *
	 * @since 2.5.0
	 */
	require_once WORDPOINTS_EXTENSION_TESTS_DIR . '/../../vendor/autoload_52.php';

} elseif ( file_exists( WORDPOINTS_EXTENSION_TESTS_DIR . '/../../vendor/autoload.php' ) ) {

	/**
	 * The Composer generated autoloader for the extension's dependencies.
	 *
	 * @since 2.5.0
	 */
	require_once WORDPOINTS_EXTENSION_TESTS_DIR . '/../../vendor/autoload.php';
}

if ( class_exists( 'WPPPB_Loader' ) ) {

	/**
	 * WordPoints's PHPUnit loader class.
	 *
	 * @since 2.5.0
	 */
	require_once WORDPOINTS_DEV_LIB_PHPUNIT_DIR . '/classes/bootstrap/loader.php';
}

if ( file_exists( WORDPOINTS_EXTENSION_TESTS_DIR . '/includes/functions.php' ) ) {

	/**
	 * The extensions's testing functions.
	 *
	 * @since 1.0.0
	 */
	require_once WORDPOINTS_EXTENSION_TESTS_DIR . '/includes/functions.php';
}

if (
	class_exists( 'WordPoints_PHPUnit_Bootstrap_Loader' )
	&& ! defined( 'WORDPOINTS_MODULE_TESTS_LOADER' )
) {

	$wordpoints_loader = WordPoints_PHPUnit_Bootstrap_Loader::instance();
	$wordpoints_loader->add_extension(
		$wordpoints_extension->get_basename()
		, (
			getenv( 'WORDPOINTS_EXTENSION_NETWORK_ACTIVE' )
			|| getenv( 'WORDPOINTS_MODULE_NETWORK_ACTIVE' ) // Back-pat.
		)
	);

} elseif ( ! $wordpoints_has_uninstall_tester || ! running_wordpoints_module_uninstall_tests() ) {

	// Hook to load WordPoints.
	tests_add_filter( 'muplugins_loaded', 'wordpointstests_manually_load_plugin' );

	// Hook to load the extension.
	tests_add_filter(
		'plugins_loaded',
		defined( 'WORDPOINTS_MODULE_TESTS_LOADER' )
			? WORDPOINTS_MODULE_TESTS_LOADER
			: 'wordpoints_dev_lib_load_the_module'
		, 14
	);
}

/**
 * The WordPoints tests bootstrap.
 *
 * @since 1.0.0
 */
require WORDPOINTS_DEV_LIB_PHPUNIT_DIR . '/includes/bootstrap.php';

if ( file_exists( getenv( 'WP_TESTS_DIR' ) . '/includes/speed-trap-listener.php' ) ) {

	/**
	 * The speed trap listener from WordPress's test suite.
	 *
	 * @since 1.0.0
	 */
	require_once getenv( 'WP_TESTS_DIR' ) . '/includes/speed-trap-listener.php';
}

if ( $wordpoints_has_uninstall_tester ) {

	/**
	 * The plugin uninstall testing bootstrap.
	 *
	 * @since 1.1.0
	 */
	require_once WORDPOINTS_EXTENSION_TESTS_DIR . '/../../vendor/jdgrimes/wp-plugin-uninstall-tester/bootstrap.php';

	/**
	 * The WordPoints modules uninstall testing bootstrap.
	 *
	 * @since 1.1.0
	 */
	require_once WORDPOINTS_EXTENSION_TESTS_DIR . '/../../vendor/wordpoints/module-uninstall-tester/bootstrap.php';
}

if (
	( ! $wordpoints_has_uninstall_tester || ! running_wordpoints_module_uninstall_tests() )
	&& ( ! isset( $wordpoints_loader ) || ! $wordpoints_loader->running_uninstall_tests() )
	&& file_exists( WORDPOINTS_EXTENSION_TESTS_DIR . '/../../src/admin/admin.php' )
) {

	/**
	 * The extension's admin-side code.
	 *
	 * @since 1.0.0
	 */
	require_once WORDPOINTS_EXTENSION_TESTS_DIR . '/../../src/admin/admin.php';
}

unset( $wordpoints_loader, $wordpoints_extension, $wordpoints_has_uninstall_tester );

if ( file_exists( WORDPOINTS_EXTENSION_TESTS_DIR . '/includes/bootstrap.php' ) ) {

	/**
	 * The extension's tests bootstrap.
	 *
	 * @since 1.0.0
	 */
	require_once WORDPOINTS_EXTENSION_TESTS_DIR . '/includes/bootstrap.php';
}

// EOF
