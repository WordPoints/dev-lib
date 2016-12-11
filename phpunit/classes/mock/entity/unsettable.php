<?php

/**
 * Mock unsettable entity class for the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock unsettable entity class for the PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Entity_Unsettable
	extends WordPoints_PHPUnit_Mock_Entity {

	/**
	 * @since 2.6.0
	 */
	public function set_the_value( $value ) {
		return false;
	}
}

// EOF
