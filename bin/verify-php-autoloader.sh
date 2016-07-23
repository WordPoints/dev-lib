#!/usr/bin/env php
<?php

/**
 * Verifies that a PHP autoloader map file will work properly when SPL is disabled.
 *
 * When SPL is disabled all of the class files have to just be loaded immediately,
 * instead of on demand as each class is referenced. Because of this, the order of
 * the files in teh map becomes important: if those at the beginning extend classes
 * or interfaces that are later in the array, a Fatal error will occur, as those
 * classes won't be loaded yet (autoloading is disabled in this case, remember).
 *
 * @package WordPoints_Dev_Lib
 * @since 2.4.0
 */

/**
 * Simulate loading of all files in a map as when falling back when SPL is disabled.
 *
 * @since 2.4.0
 */
function simulate_autoload_fallback( $dir ) {
	$files = require( $dir . '/index.php' );

	foreach ( $files as $file ) {
		require_once( $dir . $file );
	}
}

$dir = $argv[1];

// Sometimes classes in one map depend on classes in another, so we load those first.
if ( $dir !== 'src/classes/' ) {
	simulate_autoload_fallback( 'src/classes/' );

	if ( $dir !== 'src/admin/classes/' && strpos( $dir, 'admin' ) ) {
		simulate_autoload_fallback( 'src/admin/classes/' );
	}
}

simulate_autoload_fallback( $dir );

// EOF
