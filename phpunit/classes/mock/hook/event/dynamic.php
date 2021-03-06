<?php

/**
 * Mock dynamic hook event class for the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock dynamic hook event class for the PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Hook_Event_Dynamic extends WordPoints_Hook_Event_Dynamic {

	/**
	 * @since 2.6.0
	 */
	protected $slug = 'test';

	/**
	 * @since 2.6.0
	 */
	protected $generic_entity_slug = 'generic';

	/**
	 * @since 2.6.0
	 */
	public function get_entity_title() {
		return parent::get_entity_title();
	}

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
