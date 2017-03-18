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
	$headers = array(
		'name'        => 'Module Name',
		'module_uri'  => 'Module URI',
		'version'     => 'Version',
		'description' => 'Description',
		'author'      => 'Author',
		'author_uri'  => 'Author URI',
		'text_domain' => 'Text Domain',
		'domain_path' => 'Domain Path',
		'network'     => 'Network',
		'update_api'  => 'Update API',
		'channel'     => 'Channel',
		'server'      => 'Server',
		'ID'          => 'ID',
		'namespace'   => 'Namespace',
	);

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

		$module_data = wordpoints_dev_lib_get_file_data( $module_file, $headers );

		if ( empty( $module_data['name'] ) ) {
			continue;
		}

		$modules[ basename( $module_file ) ] = $module_data;
	}

	uasort( $modules, '_wordpoints_dev_lib_sort_uname_callback' );

	return $modules;
}

/**
 * Retrieve metadata from a file.
 *
 * @since 2.5.0
 *
 * @param string $file    Path to the file.
 * @param array  $headers List of headers.
 *
 * @return array Array of file headers.
 */
function wordpoints_dev_lib_get_file_data( $file, $headers ) {

	// We don't need to write to the file, so just open for reading.
	$fp = fopen( $file, 'r' );

	// Pull only the first 8kiB of the file in.
	$file_data = fread( $fp, 8192 );

	// PHP will close file handle, but we are good citizens.
	fclose( $fp );

	// Make sure we catch CR-only line endings.
	$file_data = str_replace( "\r", "\n", $file_data );

	foreach ( $headers as $field => $regex ) {

		$matched = preg_match(
			'/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi'
			, $file_data
			, $match
		);

		if ( $matched && $match[1] ) {
			$headers[ $field ] = $match[1];
		} else {
			$headers[ $field ] = '';
		}
	}

	return $headers;
}

/**
 * Callback to sort an array by 'name' key.
 *
 * @since 2.5.0
 *
 * @param array $a One item.
 * @param array $b Another item.
 *
 * @return int {@see strnatcasecmp()}.
 */
function _wordpoints_dev_lib_sort_uname_callback( $a, $b ) {

	return strnatcasecmp( $a['name'], $b['name'] );
}

/**
 * Load and activate a module.
 *
 * @since 1.1.0
 * @deprecated 2.5.0 Use WordPoints_PHPUnit_Bootstrap_Loader::add_module() instead.
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
 * @deprecated 2.5.0 Use WordPoints_PHPUnit_Bootstrap_Loader::add_module() instead.
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

/**
 * Get the basename of the module being tested.
 *
 * @since 2.5.0
 *
 * @return string The basename path of the module's main file.
 */
function wordpoints_dev_lib_the_module_basename() {

	// Find the module file.
	$modules = wordpoints_dev_lib_get_modules(
		WORDPOINTS_MODULE_TESTS_DIR . '/../../src'
	);

	$module = key( $modules );

	return substr( $module, 0, -4 /* .php */ ) . '/' . $module;
}

// EOF
