<?php

/**
 * Unit test class for the disallowed functions sniff.
 *
 * @package WordPoints_Dev_Lib\Tests
 * @since 2.1.0
 */

/**
 * Unit test class for the disallowed functions sniff.
 *
 * @since 2.1.0
 */
class WordPoints_Tests_PHP_DisallowedFunctionsUnitTest extends AbstractSniffUnitTest {

	/**
	 * @since 2.1.0
	 */
	public function getErrorList() {

		return array(
			3 => 1,
			4 => 1,
			6 => 1,
			9 => 1,
			11 => 1,
			16 => 1,
			18 => 1,
			20 => 1,
		);
	}

	/**
	 * @since 2.1.0
	 */
	public function getWarningList() {
		return array();
	}
}

// EOF
