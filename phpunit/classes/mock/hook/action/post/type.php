<?php

/**
 * Mock post type hook action class for the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * \@since 2.6.0
 */

/**
 * Mock post type hook action class for the PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Hook_Action_Post_Type
	extends WordPoints_Hook_Action_Post_Type {

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
