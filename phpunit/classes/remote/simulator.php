<?php

/**
 * Remote simulator class for use in the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since   2.6.0
 */

/**
 * Simulates a particular site configuration on the remote server.
 *
 * @since 2.6.0
 */
abstract class WordPoints_PHPUnit_Remote_Simulator {

	/**
	 * Adds any dependencies needed to respond to the request.
	 *
	 * @since 2.6.0
	 */
	public function add_dependencies( WPPPB_Loader $loader ) {}

	/**
	 * Begins the simulation.
	 *
	 * @since 2.6.0
	 */
	public function start() {

		global $wpdb;

		$wpdb->query( 'START TRANSACTION' );

		$this->setup();
	}

	/**
	 * Sets up for handling the request once the simulator has started.
	 *
	 * @since 2.6.0
	 */
	public function setup() {}

	/**
	 * Ends the simulation.
	 *
	 * @since 2.6.0
	 */
	public function stop() {

		global $wpdb;

		$wpdb->query( 'ROLLBACK' );
	}
}

// EOF
