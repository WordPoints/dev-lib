<?php

/**
 * Installs components remotely.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

// The $data variable is provided by the including file.
$components = $data;

// Load files to be included before the components are installed.
if ( isset( $custom_files['before_components'] ) ) {
	foreach ( $custom_files['before_components'] as $file => $data ) {
		require $file;
	}
}

// Activate the components.
$components_object = WordPoints_Components::instance();

foreach ( $components as $component => $component_info ) {

	$result = $components_object->activate( $component );

	if ( ! $result ) {
		echo "Error: Component activation failed for {$component}." . PHP_EOL;
		exit( 1 );
	}
}

// Load files to be included after the components are installed.
if ( isset( $custom_files['after_components'] ) ) {
	foreach ( $custom_files['after_components'] as $file => $data ) {
		require $file;
	}
}

// EOF
