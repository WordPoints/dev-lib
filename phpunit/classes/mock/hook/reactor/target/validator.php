<?php

/**
 * Mock hook reactor class for the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.7.0
 */

/**
 * Mock hook reactor for the PHPUnit tests.
 *
 * @since 2.7.0
 */
class WordPoints_PHPUnit_Mock_Hook_Reactor_Target_Validator
	extends WordPoints_PHPUnit_Mock_Hook_Reactor
	implements WordPoints_Hook_Reactor_Target_ValidatorI {

	/**
	 * If set, will be returned by can_hit().
	 *
	 * @since 2.7.0
	 *
	 * @var bool
	 */
	public $target_validated;

	/**
	 * @since 2.7.0
	 */
	public function can_hit(
		WordPoints_EntityishI $target,
		WordPoints_Hook_Fire $fire
	) {

		if ( isset( $this->target_validated ) ) {
			return $this->target_validated;
		}

		return true;
	}
}

// EOF
