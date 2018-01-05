<?php

/**
 * Installs extensions remotely.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.7.0
 */

// The $data variable is provided by the including file.
$wordpoints_extensions = $data;

// Load files to be included before the extensions are installed.
if ( isset( $custom_files['before_extensions'] ) ) {
	foreach ( $custom_files['before_extensions'] as $file => $data ) { // WPCS: prefix OK.
		require $file;
	}
}

// Back-compat.
if ( isset( $custom_files['before_modules'] ) ) {
	foreach ( $custom_files['before_modules'] as $file => $data ) { // WPCS: prefix OK.
		require $file;
	}
}

// Activate the extensions.
foreach ( $wordpoints_extensions as $wordpoints_extension => $extension_info ) {

	$wordpoints_result = wordpoints_activate_module( $wordpoints_extension, '', $extension_info['network_wide'] );

	if ( is_wp_error( $wordpoints_result ) ) {

		echo "Error: Extension activation failed for {$wordpoints_extension}:" . PHP_EOL;

		foreach ( $wordpoints_result->get_error_messages() as $wordpoints_message ) {
			echo "- {$wordpoints_message}" . PHP_EOL;
		}

		exit( 1 );
	}

	unset( $wordpoints_result );
}

unset( $wordpoints_extensions, $wordpoints_extension );

// Load files to be included after the extensions are installed.
if ( isset( $custom_files['after_extensions'] ) ) {
	foreach ( $custom_files['after_extensions'] as $file => $data ) { // WPCS: prefix OK.
		require $file;
	}
}

// Back-compat.
if ( isset( $custom_files['after_modules'] ) ) {
	foreach ( $custom_files['after_modules'] as $file => $data ) { // WPCS: prefix OK.
		require $file;
	}
}

// EOF
