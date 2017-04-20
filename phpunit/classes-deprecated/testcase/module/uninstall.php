<?php

/**
 * WordPoints module uninstall test case.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Test WordPoints module installation and uninstallation.
 *
 * @since 2.5.0
 * @deprecated 2.6.0 Use WordPoints_PHPUnit_TestCase_Module_Uninstall instead.
 */
abstract class WordPoints_Dev_Lib_PHPUnit_TestCase_Module_Uninstall
	extends WordPoints_PHPUnit_TestCase_Module_Uninstall {

	/**
	 * @since 2.6.0
	 */
	public static function setUpBeforeClass() {

		_deprecated_function(
			__CLASS__
			, '2.6.0'
			, 'WordPoints_PHPUnit_TestCase_Module_Uninstall'
		);

		parent::setUpBeforeClass();
	}
}

// EOF
