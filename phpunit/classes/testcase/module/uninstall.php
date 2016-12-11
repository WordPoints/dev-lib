<?php

/**
 * WordPoints module uninstall test case.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.5.0
 */

/**
 * Test WordPoints module installation and uninstallation.
 *
 * @since 2.5.0 As WordPoints_Dev_Lib_PHPUnit_TestCase_Module_Uninstall.
 * @since 2.6.0
 */
abstract class WordPoints_PHPUnit_TestCase_Module_Uninstall
	extends WPPPB_TestCase_Uninstall {

	/**
	 * @since 2.5.0 As part of WordPoints_Dev_Lib_PHPUnit_TestCase_Module_Uninstall.
	 * @since 2.6.0
	 */
	public function setUp() {

		$usage_simulator = dirname( __FILE__ )
		    . '/../../../../../tests/phpunit/includes/usage-simulator.php';

		if ( file_exists( $usage_simulator ) ) {
			$this->simulation_file = dirname( __FILE__ )
				. '/../../../simulate-module-use.php';
		}

		parent::setUp();
	}
}

// EOF
