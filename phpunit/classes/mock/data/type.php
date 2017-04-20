<?php

/**
 * Mock data type class for the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock data type class for the PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Data_Type extends WordPoints_Data_Type {

	/**
	 * @since 2.6.0
	 */
	public function validate_value( $value ) {
		return $value;
	}
}

// EOF
