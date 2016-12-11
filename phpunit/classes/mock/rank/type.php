<?php

/**
 * Class for a mock rank to use in the tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * A mock rank to use in the tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Rank_Type extends WordPoints_Rank_Type {

	/**
	 * @since 2.6.0
	 */
	protected $name = 'Test';

	/**
	 * @since 2.6.0
	 */
	protected $meta_fields = array( 'test_meta' => array() );

	//
	// Public Methods.
	//

	/**
	 * @since 2.6.0
	 */
	public function __construct( array $args ) {

		parent::__construct( $args );

		if ( isset( $args['meta_fields'] ) ) {
			$this->meta_fields = $args['meta_fields'];
		}
	}

	/**
	 * Destroy the rank type handler when this rank type is deregistered.
	 *
	 * @since 2.6.0
	 */
	public function destruct() {}

	/**
	 * Validate the metadata for a rank of this type.
	 *
	 * @since 2.6.0
	 *
	 * @param array $meta The metadata to validate.
	 *
	 * @return array|false The validated metadata or false if it shouldn't be saved.
	 */
	public function validate_rank_meta( array $meta ) {

		if ( ! isset( $meta['test_meta'] ) ) {
			return false;
		}

		return $meta;
	}

	//
	// Protected Methods.
	//

	/**
	 * Check if a user can transition to a rank of this type.
	 *
	 * @since 2.6.0
	 *
	 * @param int             $user_id The ID of the user to check.
	 * @param WordPoints_Rank $rank    The object for the rank.
	 * @param array           $args    Other arguments from the function which
	 *                                 triggered the check.
	 *
	 * @return bool Whether the user meets the requirements for this rank.
	 */
	protected function can_transition_user_rank( $user_id, $rank, array $args ) {
		return true;
	}
}

// EOF
