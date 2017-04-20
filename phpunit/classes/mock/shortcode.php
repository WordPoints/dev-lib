<?php

/**
 * A mock shortcode class that can be used in the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock shortcode to be used in the PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Shortcode extends WordPoints_Shortcode {

	/**
	 * @since 2.6.0
	 */
	public $shortcode = 'test_shortcode';

	/**
	 * @since 2.6.0
	 */
	public $pairs = array();

	/**
	 * @since 2.6.0
	 */
	public $atts = array();

	/**
	 * @since 2.6.0
	 */
	public $content;

	/**
	 * @since 2.6.0
	 */
	protected function generate() {
		return 'Testing';
	}
}

// EOF
