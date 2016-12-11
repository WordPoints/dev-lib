<?php

/**
 * A class that can be used in the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock object to be used in the PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Object {

	/**
	 * The method calls made on this object.
	 *
	 * @since 2.6.0
	 *
	 * @var array[]
	 */
	public $calls = array();

	/**
	 * @since 2.6.0
	 */
	public function __construct() {
		$arguments = func_get_args();
		$this->__call( __FUNCTION__, $arguments );
	}

	/**
	 * Record method calls on this object.
	 *
	 * @since 2.6.0
	 */
	public function __call( $name, $arguments ) {
		$this->calls[] = array( 'name' => $name, 'arguments' => $arguments );
	}
}

// EOF
