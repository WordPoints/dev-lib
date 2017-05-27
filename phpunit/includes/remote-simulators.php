<?php

/**
 * Set up on a remote server for the HTTP tests.
 *
 * This file is intended to be symlinked as a MU plugin on the site being tested. It
 * will allow the test in question to handle remote requests from the PHPUnit tests.
 * This basically enables the test site to handle those requests by simulating a
 * particular environment. Exactly which simulator is run is determined by the
 * `x-wordpoints-tests-simulator` request header.
 *
 * This is useful when running tests on code that performs requests to a remote site.
 * By utilizing this simulator, an actual request to an environment simulating the
 * actual remote environment can be made. This is more realistic than simply mocking
 * the response that might be returned, because it allows the same code that would
 * respond on that remote environment to simulate and return the response from the
 * local test site.
 *
 * Essentially, we simulate a local version of a remote environment, that we can then
 * make "remote" requests to in our tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since   2.7.0
 */

if ( ! isset( $_SERVER['HTTP_X_WORDPOINTS_TESTS_SIMULATOR'] ) ) {
	return;
}

/**
 * Defined when we are running the remote simulator.
 *
 * @since 2.7.0
 */
define( 'WORDPOINTS_RUNNING_REMOTE_SIMULATOR', true );

/**
 * Class autoloader for PHPUnit tests and helpers from the dev lib.
 *
 * @since 2.7.0
 */
require_once( dirname( __FILE__ ) . '/../classes/class/autoloader.php' );

WordPoints_PHPUnit_Class_Autoloader::register_dir(
	dirname( __FILE__ ) . '/../classes/'
	, 'WordPoints_PHPUnit_'
);

/**
 * The Composer generated autoloader.
 *
 * @since 2.7.0
 */
require_once( dirname( __FILE__ ) . '/../../../vendor/autoload_52.php' );

$loader = new WordPoints_PHPUnit_Remote_Simulator_Bootstrap_Loader();

$simulator = sanitize_key( $_SERVER['HTTP_X_WORDPOINTS_TESTS_SIMULATOR'] );
$simulator_class = "WordPoints_PHPUnit_Remote_Simulator_{$simulator}";

/** @var WordPoints_PHPUnit_Remote_Simulator $simulator */
$simulator = new $simulator_class;
$simulator->add_dependencies( $loader );

$loader->install_plugins();

add_action( 'init', array( $simulator, 'start' ) );
add_action( 'shutdown', array( $simulator, 'stop' ) );

// EOF
