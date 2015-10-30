<?php

/**
 * Unit test class for the RequiredParentMethodCall Sniff.
 */

/**
 * Tests for the RequiredParentMethodCall sniff.
 */
class WordPoints_Tests_PHP_RequiredParentMethodCallUnitTest extends AbstractSniffUnitTest {

	public function getErrorList() {

		return array(
			11 => 1,
		);
	}

	public function getWarningList() {
		return array();
	}

}

// EOF
