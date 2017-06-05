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
 * @deprecated 2.7.0 Use WordPoints_PHPUnit_TestCase_Extension_Uninstall instead.
 */
abstract class WordPoints_PHPUnit_TestCase_Module_Uninstall
	extends WordPoints_PHPUnit_TestCase_Extension_Uninstall {

	/**
	 * Whether to uninstall only the module, or WordPoints entirely.
	 *
	 * @since 2.6.0
	 *
	 * @var bool
	 */
	protected $uninstall_module_only = false;

	/**
	 * Full path to the module to uninstall.
	 *
	 * If left undefined, it will be determined automatically from the loader.
	 *
	 * @since 2.6.0
	 *
	 * @var string
	 */
	protected $module_file;

	/**
	 * @since 2.5.0 As part of WordPoints_Dev_Lib_PHPUnit_TestCase_Module_Uninstall.
	 * @since 2.6.0
	 */
	public function setUp() {

		$this->extension_file = $this->module_file;

		parent::setUp();

		$this->uninstall_module_only = $this->uninstall_extension_only;
	}
}

// EOF
