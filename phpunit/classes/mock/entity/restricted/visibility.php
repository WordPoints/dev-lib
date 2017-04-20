<?php

/**
 * Mock restricted visibility entity class for the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock restricted visibility entity class for the PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Entity_Restricted_Visibility
	extends WordPoints_PHPUnit_Mock_Entity
	implements WordPoints_Entity_Restricted_VisibilityI {

	/**
	 * Whether this entity can be viewed.
	 *
	 * @since 2.6.0
	 *
	 * @var bool
	 */
	public static $can_view = true;

	/**
	 * @since 2.6.0
	 */
	public function user_can_view( $user_id, $id ) {
		return self::$can_view;
	}
}

// EOF
