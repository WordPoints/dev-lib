<?php

/**
 * Simulate using a module remotely.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.5.0
 */

/**
 * The WordPoints tests functions.
 *
 * @since 2.5.0
 */
require_once( getenv( 'WORDPOINTS_TESTS_DIR' ) . '/includes/functions.php' );

/**
 * The module's usage simulator script.
 *
 * @since 2.5.0
 */
require dirname( __FILE__ ) . '/../../tests/phpunit/includes/usage-simulator.php';

// EOF
