<?php

/**
 * Functions used by the PHPUnit bootstrap.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 1.1.0
 */

/**
 * Locate the main file for a module.
 *
 * @since 1.1.0
 *
 * @param string $module_folder The full path to the module's folder.
 *
 * @return array A list of the module files found (files with module headers),
 *               indexed by the file names.
 */
function wordpoints_dev_lib_get_modules( $module_folder = '' ) {

	$modules = array();

	// Escape pattern-matching characters in the path.
	$module_escape_path = str_replace(
		array( '*', '?', '[' )
		, array( '[*]', '[?]', '[[]' )
		, $module_folder
	);

	$module_files = glob( "{$module_escape_path}/*.php" );

	if ( false === $module_files ) {
		return $modules;
	}

	foreach ( $module_files as $module_file ) {

		if ( ! is_readable( $module_file ) ) {
			continue;
		}

		$module_data = wordpoints_get_module_data( $module_file, false, false );

		if ( empty( $module_data['name'] ) ) {
			continue;
		}

		$modules[ basename( $module_file ) ] = $module_data;
	}

	uasort( $modules, '_wordpoints_sort_uname_callback' );

	return $modules;
}

/**
 * Load and activate a module.
 *
 * @since 1.1.0
 *
 * @param string $module_file The main file of the module to load.
 */
function wordpoints_dev_lib_load_module( $module_file ) {

	WordPoints_Module_Paths::register( $module_file );

	require( $module_file );

	$module = wordpoints_module_basename( $module_file );
	$network_wide = is_multisite() && getenv( 'WORDPOINTS_NETWORK_ACTIVE' );

	/**
	 * @since 1.1.0
	 */
	do_action( "wordpoints_module_activate-{$module}", $network_wide );

	WordPoints_Installables::install(
		'module'
		, WordPoints_Modules::get_slug( $module )
		, $network_wide
	);
}

/**
 * Load and activate the module being tested.
 *
 * @since 1.1.0
 */
function wordpoints_dev_lib_load_the_module() {

	$src = WORDPOINTS_MODULE_TESTS_DIR . '/../../src';

	// Find the module file.
	$modules = wordpoints_dev_lib_get_modules( $src );

	wordpoints_dev_lib_load_module( $src . '/' . key( $modules ) );

	// Load the module's admin-side code too, if asked.
	if ( defined( 'WORDPOINTS_TESTS_LOAD_MODULE_ADMIN' ) ) {
		require( WORDPOINTS_TESTS_LOAD_MODULE_ADMIN );
	}
}

// EOF
