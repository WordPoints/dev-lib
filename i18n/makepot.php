<?php

/**
 * Generates a POT file for a WordPoints extension.
 *
 * @package WordPoints_Dev_Lib
 * @since 1.3.0
 */

if ( ! getenv( 'WP_TESTS_DIR' ) ) {
	exit( 'WP_TESTS_DIR is not set.' . PHP_EOL );
}

/**
 * WordPress's MakePOT class.
 *
 * @since 1.3.0
 */
require_once getenv( 'WP_TESTS_DIR' ) . '/../../tools/i18n/makepot.php';

/**
 * WordPoints's MakePOT class.
 *
 * @since 2.7.0
 */
require_once dirname( __FILE__ ) . '/class-makepot.php';

/**
 * WordPoints's PotExtMeta class.
 *
 * @since 2.7.0
 */
require_once dirname( __FILE__ ) . '/class-potextmeta.php';

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

// Run the CLI only if the file wasn't included.
$included_files = get_included_files();

// For backward compatibility with pre-2.7.0.
if ( __FILE__ === $included_files[0] ) {

	$makepot = new WordPoints_MakePOT();

	if ( count( $argv ) >= 3 && in_array( $argv[1], $makepot->projects, true ) ) {

		$result = call_user_func(
			array( $makepot, str_replace( '-', '_', $argv[1] ) )
			, realpath( $argv[2] )
			, isset( $argv[3] ) ? $argv[3] : null
			, isset( $argv[4] ) ? $argv[4] : null
		);

		if ( false === $result ) {
			$makepot->error( 'Couldn\'t generate POT file!' );
		}

	} else {

		$usage  = "Usage: php makepot.php <project> <directory> [<output> [<slug>]]\n\n";
		$usage .= "Generate POT file <output> from the files in <directory>\n";
		$usage .= 'Available projects: ' . implode( ', ', $makepot->projects ) . "\n";
		fwrite( STDERR, $usage );
		exit( 1 );
	}
}

// EOF
