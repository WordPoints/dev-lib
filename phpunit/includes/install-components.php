<?php

/**
 * Installs components remotely.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

// The $data variable is provided by the including file.
$wordpoints_components = $data;

// Load files to be included before the components are installed.
if ( isset( $custom_files['before_components'] ) ) {
	foreach ( $custom_files['before_components'] as $file => $data ) { // WPCS: prefix OK.
		require $file;
	}
}

// Activate the components.
$wordpoints_components_object = WordPoints_Components::instance();

foreach ( $wordpoints_components as $wordpoints_component => $component_info ) {

	if ( ! $wordpoints_components_object->activate( $wordpoints_component ) ) {
		echo "Error: Component activation failed for {$wordpoints_component}." . PHP_EOL;
		exit( 1 );
	}
}

unset( $wordpoints_components, $wordpoints_components_object, $wordpoints_component );

// Load files to be included after the components are installed.
if ( isset( $custom_files['after_components'] ) ) {
	foreach ( $custom_files['after_components'] as $file => $data ) { // WPCS: prefix OK.
		require $file;
	}
}

// EOF
