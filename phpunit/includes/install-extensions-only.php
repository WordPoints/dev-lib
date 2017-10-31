<?php

/**
 * Installs extensions remotely.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.7.0
 */

$extensions_info  = json_decode( $argv[1], true ); // WPCS: prefix OK.
$config_file_path = $argv[2]; // WPCS: prefix OK.
$is_multisite     = (bool) $argv[3]; // WPCS: prefix OK.
$custom_files     = json_decode( $argv[4], true ); // WPCS: prefix OK.

/**
 * The bootstrap file for loading WordPress.
 *
 * @since 2.7.0
 */
require dirname( __FILE__ ) . '/../../../vendor/jdgrimes/wpppb/src/bin/bootstrap.php';

$data = $extensions_info; // WPCS: prefix OK.

/**
 * Installs extensions.
 *
 * @since 2.7.0
 */
require dirname( __FILE__ ) . '/install-extensions.php';

// EOF
