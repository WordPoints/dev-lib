<?php

/**
 * Unit test class for the restricted assertions sniff.
 *
 * @package WordPoints_Dev_Lib\Tests
 * @since 2.6.0
 */

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the restricted assertions sniff.
 *
 * @since 2.6.0
 */
class WordPoints_Tests_PHPUnit_RestrictedAssertionsUnitTest extends AbstractSniffUnitTest {

	/**
	 * @since 2.6.0
	 */
	public function getErrorList() {

		return array(
			6  => 1,
			8  => 1,
			9  => 1,
			10 => 1,
			11 => 1,
			12 => 1,
			13 => 1,
			14 => 1,
		);
	}

	/**
	 * @since 2.6.0
	 */
	public function getWarningList() {
		return array();
	}
}

// EOF
