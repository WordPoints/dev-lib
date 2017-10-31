<?php

/**
 * Simulate using a extension remotely.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.7.0
 */

/**
 * The WordPoints tests functions.
 *
 * @since 2.7.0
 */
require_once getenv( 'WORDPOINTS_TESTS_DIR' ) . '/includes/functions.php';

/**
 * The extension's usage simulator script.
 *
 * @since 2.7.0
 */
require dirname( __FILE__ ) . '/../../tests/phpunit/includes/usage-simulator.php';

if (
	getenv( 'WORDPOINTS_ONLY_UNINSTALL_EXTENSION' )
	|| getenv( 'WORDPOINTS_ONLY_UNINSTALL_MODULE' ) // Back-pat.
) { // @codingStandardsIgnoreLine

	$wordpoints_network_wide = (
		getenv( 'WORDPOINTS_EXTENSION_NETWORK_ACTIVE' )
		|| getenv( 'WORDPOINTS_MODULE_NETWORK_ACTIVE' )
	);

	if ( $wordpoints_network_wide ) {
		$wordpoints_active_extensions = array_keys(
			wordpoints_get_array_option(
				'wordpoints_sitewide_active_modules',
				'site'
			)
		);
	} else {
		$wordpoints_active_extensions = wordpoints_get_array_option( 'wordpoints_active_modules' );
	}

	wordpoints_deactivate_modules( $wordpoints_active_extensions, false, $wordpoints_network_wide );

	unset( $wordpoints_active_extensions, $wordpoints_network_wide );

	// Skip the plugin deactivation.
	exit;
}

// EOF
