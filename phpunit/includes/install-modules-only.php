<?php

/**
 * Installs modules remotely.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

$modules_info     = json_decode( $argv[1], true );
$config_file_path = $argv[2];
$is_multisite     = (bool) $argv[3];
$custom_files     = json_decode( $argv[4], true );

/**
 * The bootstrap file for loading WordPress.
 *
 * @since 2.6.0
 */
require dirname( __FILE__ ) . '/../../../vendor/jdgrimes/wpppb/src/bin/bootstrap.php';

$data = $modules_info;

/**
 * Installs modules.
 *
 * @since 2.6.0
 */
require dirname( __FILE__ ) . '/install-modules.php';

// EOF
