<?php

/**
 * Installs extensions remotely.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.7.0
 */

// The $data variable is provided by the including file.
$extensions = $data;

// Load files to be included before the extensions are installed.
if ( isset( $custom_files['before_extensions'] ) ) {
	foreach ( $custom_files['before_extensions'] as $file => $data ) {
		require $file;
	}
}

// Back-compat.
if ( isset( $custom_files['before_modules'] ) ) {
	foreach ( $custom_files['before_modules'] as $file => $data ) {
		require $file;
	}
}

// Activate the extensions.
foreach ( $extensions as $extension => $extension_info ) {

	$result = wordpoints_activate_module( $extension, '', $extension_info['network_wide'] );

	if ( is_wp_error( $result ) ) {

		echo "Error: Extension activation failed for {$extension}:" . PHP_EOL;

		foreach ( $result->get_error_messages() as $message ) {
			echo "- {$message}" . PHP_EOL;
		}

		exit( 1 );
	}
}

// Load files to be included after the extensions are installed.
if ( isset( $custom_files['after_extensions'] ) ) {
	foreach ( $custom_files['after_extensions'] as $file => $data ) {
		require $file;
	}
}

// Back-compat.
if ( isset( $custom_files['after_modules'] ) ) {
	foreach ( $custom_files['after_modules'] as $file => $data ) {
		require $file;
	}
}

// EOF
