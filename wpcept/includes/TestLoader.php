<?php

/**
 * Test loader class.
 *
 * @package wordpoints-hooks-api
 * @since 1.0.0
 */

namespace WordPoints\Tests\Codeception;

/**
 * A child of the test loader, for modifying the parent's static properties.
 *
 * @since 1.0.0
 */
class TestLoader extends \Codeception\Lib\TestLoader {

	/**
	 * Adds support for lowercase test formats.
	 *
	 * This enables the use of lowercase filename suffixes "cept", "cest", and
	 * "test".
	 *
	 * @since 1.0.0
	 */
	public static function support_lowercase_formats() {

		$formats = parent::$formats;

		foreach ( $formats as $format ) {
			parent::$formats[] = strtolower( $format );
		}
	}
}

// EOF
