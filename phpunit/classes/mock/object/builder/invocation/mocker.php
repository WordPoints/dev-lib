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
class WordPoints_PHPUnit_Mock_Object_Builder_Invocation_Mocker
	extends PHPUnit_Framework_MockObject_Builder_InvocationMocker {

	/**
	 * Returns an invocation mocker that requires a method should return a value.
	 *
	 * @since 2.7.0
	 *
	 * @param mixed $value The value that the method should return.
	 *
	 * @return PHPUnit_Framework_MockObject_Stub_Return Return value stub.
	 */
	public function willReturn( $value ) {
		return $this->will( new PHPUnit_Framework_MockObject_Stub_Return( $value ) );
	}
}

// EOF
