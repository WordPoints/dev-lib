<?php

/**
 * Unit test class for the MissingEcho sniff.
 *
 * @package WordPoints_Dev_Lib\Tests
 * @since 1.0.0
 */

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the MissingEcho sniff.
 *
 * @since 1.0.0
 */
class WordPoints_Tests_PHP_MissingEchoUnitTest extends AbstractSniffUnitTest {

	/**
	 * @since 1.0.0
	 */
	public function getErrorList() {

		return array(
			4 => 1,
			7 => 1,
		);
	}

	/**
	 * @since 1.0.0
	 */
	public function getWarningList() {
		return array();
	}
}

// EOF
