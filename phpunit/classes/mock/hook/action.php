<?php

/**
 * Mock hook action class for the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock hook action class for the PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Hook_Action extends WordPoints_Hook_Action {

	/**
	 * @since 2.6.0
	 */
	protected $slug = 'test_action';

	/**
	 * @since 2.6.0
	 */
	protected $arg_index = array( 'test_entity' => 0 );

	/**
	 * Set a protected property's value.
	 *
	 * @since 2.6.0
	 *
	 * @param string $var   The property name.
	 * @param mixed  $value The property value.
	 */
	public function set( $var, $value ) {
		$this->$var = $value;
	}
}

// EOF
