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
	 * Asserts that a method should return a value.
	 *
	 * @since 2.7.0
	 *
	 * @param mixed $value The value that the method should return.
	 *
	 * @return PHPUnit_Framework_MockObject_Builder_InvocationMocker This object.
	 */
	public function willReturn( $value ) {
		return $this->will( new PHPUnit_Framework_MockObject_Stub_Return( $value ) );
	}

	/**
	 * Asserts that a method should return a value map.
	 *
	 * @param array $valueMap The values that the method should return.
	 *
	 * @return PHPUnit_Framework_MockObject_Builder_InvocationMocker This object.
	 */
	public function willReturnMap( array $valueMap ) {

		return $this->will(
			new PHPUnit_Framework_MockObject_Stub_ReturnValueMap(
				$valueMap
			)
		);
	}

	/**
	 * Asserts that a method to return a callback value.
	 *
	 * @since 2.7.0
	 *
	 * @param callable $callback The callback that will supply the value the method
	 *                           should return.
	 *
	 * @return PHPUnit_Framework_MockObject_Builder_InvocationMocker This object.
	 */
	public function willReturnCallback( $callback ) {

		return $this->will(
			new PHPUnit_Framework_MockObject_Stub_ReturnCallback(
				$callback
			)
		);
	}

	/**
	 * Validate that a parameters matcher can be defined, throw exceptions otherwise.
	 *
	 * @throws PHPUnit_Framework_Exception
	 */
	private function canDefineParameters() {

		if ( $this->matcher->methodNameMatcher === null ) {
			throw new PHPUnit_Framework_Exception(
				'Method name matcher is not defined, cannot define parameter ' .
				' matcher without one'
			);
		}

		if ( $this->matcher->parametersMatcher !== null ) {
			throw new PHPUnit_Framework_Exception(
				'Parameter matcher is already defined, cannot redefine'
			);
		}
	}

	/**
	 * Asserts that a method should be called with certain parameters.
	 *
	 * @param mixed ...$argument The parameters.
	 *
	 * @return PHPUnit_Framework_MockObject_Builder_InvocationMocker This object.
	 */
	public function with() {

		$args = func_get_args();

		$this->canDefineParameters();

		$this->matcher->parametersMatcher =
			new PHPUnit_Framework_MockObject_Matcher_Parameters( $args );

		return $this;
	}

	/**
	 * Asserts that a method should be called with certain sets of parameters.
	 *
	 * @param mixed ...$argument The sets of consecutive parameters.
	 *
	 * @return PHPUnit_Framework_MockObject_Builder_InvocationMocker This object.
	 */
	public function withConsecutive() {

		$args = func_get_args();

		$this->canDefineParameters();

		$this->matcher->parametersMatcher =
			new WordPoints_PHPUnit_Mock_Object_Matcher_Parameters_Consecutive( $args );

		return $this;
	}
}

// EOF
