<?php

/**
 * Mock entity context class for the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock entity context class for the PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Entity_Context_OutOfState
	extends WordPoints_Entity_Context {

	/**
	 * @since 2.6.0
	 */
	public function get_current_id() {
		return false;
	}
}

// EOF
