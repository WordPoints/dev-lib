<?php

/**
 * Simulate using a module remotely.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.5.0
 */

/**
 * The WordPoints tests functions.
 *
 * @since 2.5.0
 */
require_once( getenv( 'WORDPOINTS_TESTS_DIR' ) . '/includes/functions.php' );

/**
 * The module's usage simulator script.
 *
 * @since 2.5.0
 */
require dirname( __FILE__ ) . '/../../tests/phpunit/includes/usage-simulator.php';

if ( getenv( 'WORDPOINTS_ONLY_UNINSTALL_MODULE' ) ) {

	$network_wide = getenv( 'WORDPOINTS_MODULE_NETWORK_ACTIVE' );

	if ( $network_wide ) {
		$active_modules = array_keys(
			wordpoints_get_array_option(
				'wordpoints_sitewide_active_modules',
				'site'
			)
		);
	} else {
		$active_modules = wordpoints_get_array_option( 'wordpoints_active_modules' );
	}

	wordpoints_deactivate_modules( $active_modules, false, $network_wide );

	// Skip the plugin deactivation.
	exit;
}

// EOF
