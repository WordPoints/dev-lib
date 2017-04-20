<?php

/**
 * Mock post type points hook class.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock for the base post type points hook class.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Points_Hook_Post_Type
	extends WordPoints_Post_Type_Points_Hook_Base {

	/**
	 * @since 2.6.0
	 */
	protected $defaults = array( 'post_type' => 'ALL', 'auto_reverse' => 1 );
}

// EOF
