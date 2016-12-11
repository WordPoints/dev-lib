<?php

/**
 * Mock hook event class for the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock hook event class for the PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Hook_Event extends WordPoints_Hook_Event {

	/**
	 * @since 2.6.0
	 */
	protected $slug = 'test';

	/**
	 * @since 2.6.0
	 */
	public function get_title() {
		return 'Mock Event Title';
	}

	/**
	 * @since 2.6.0
	 */
	public function get_description() {
		return 'Mock event description.';
	}
}

// EOF
