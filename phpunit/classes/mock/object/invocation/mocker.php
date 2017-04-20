<?php

/**
 * Invocation mocker class.
 *
 * @package WordPoints_Dev_Lib
 * @since   2.7.0
 */

/**
 * Invocation mocker.
 *
 * @since 2.7.0
 */
class WordPoints_PHPUnit_Mock_Object_Invocation_Mocker
	extends PHPUnit_Framework_MockObject_InvocationMocker {

	/**
	 * @since 2.7.0
	 */
	public function expects( PHPUnit_Framework_MockObject_Matcher_Invocation $matcher ) {
		return new WordPoints_PHPUnit_Mock_Object_Builder_Invocation_Mocker(
			$this
			, $matcher
		);
	}
}

// EOF
