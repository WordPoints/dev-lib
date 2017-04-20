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
class WordPoints_PHPUnit_Mock_Class_Autoloader extends WordPoints_Class_Autoloader {

	/**
	 * Set the value of one of the parent class's properties.
	 *
	 * @since 2.6.0
	 *
	 * @param string $var   The property to set.
	 * @param mixed  $value The value to give this property.
	 */
	public static function set( $var, $value ) {
		parent::${$var} = $value;
	}
}

// EOF
