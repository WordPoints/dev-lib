<?php

/**
 * Unit test class for the missing covers sniff.
 *
 * @package WordPoints_Dev_Lib\Tests
 * @since 2.3.0
 */

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the missing covers sniff.
 *
 * @since 2.3.0
 */
class WordPoints_Tests_PHPUnit_MissingCoversUnitTest extends AbstractSniffUnitTest {

	/**
	 * @since 2.3.0
	 */
	public function getErrorList() {

		return array(
			109 => 1,
			111 => 1,
			140 => 1,
			142 => 1,
		);
	}

	/**
	 * @since 2.3.0
	 */
	public function getWarningList() {
		return array();
	}
}

// EOF
