<?php

/**
 * PHPUnit remote simulator bootstrap loader class.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since   2.6.0
 */

/**
 * A bootstrap loader utilized by the remote simulator.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Remote_Simulator_Bootstrap_Loader
	extends WordPoints_PHPUnit_Bootstrap_Loader {

	/**
	 * @since 2.6.0
	 */
	public function __construct() {
		// Override the parent constructor.
	}

	/**
	 * @since 2.6.0
	 */
	public function running_uninstall_tests() {
		return false;
	}

	/**
	 * @since 2.6.0
	 */
	public function locate_wp_tests_config() {
		return ABSPATH . '/../wp-tests-config.php';
	}
}

// EOF
