<?php

/**
 * A rank factory for use in the unit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Factory for ranks.
 *
 * @since 2.6.0
 *
 * @method int create( $args = array(), $generation_definitions = null )
 * @method WordPoints_Rank create_and_get( $args = array(), $generation_definitions = null )
 * @method int[] create_many( $count, $args = array(), $generation_definitions = null )
 */
class WordPoints_PHPUnit_Factory_For_Rank extends WP_UnitTest_Factory_For_Thing {

	/**
	 * Construct the factory with a factory object.
	 *
	 * Sets up the default generation definitions.
	 *
	 * @since 2.6.0
	 */
	public function __construct( $factory = null ) {

		parent::__construct( $factory );

		$this->default_generation_definitions = array(
			'name'     => new WP_UnitTest_Generator_Sequence( 'Rank name %s' ),
			'type'     => 'test_type',
			'group'    => 'test_group',
			'position' => 1,
		);
	}

	/**
	 * Create a rank.
	 *
	 * @since 2.6.0
	 *
	 * @param array $args {
	 *        Optional arguments to use.
	 *
	 *        @type string $name  The name of the rank.
	 *        @type string $type  The type of rank.
	 *        @type string $group The group to create this rank in.
	 *        @type array  $meta  Metadata for the rank.
	 * }
	 *
	 * @return int The ID of the rank.
	 */
	public function create_object( $args ) {

		if ( empty( $args['meta']['test_meta'] ) ) {
			$args['meta'] = array( 'test_meta' => true );
		}

		return wordpoints_add_rank(
			$args['name']
			, $args['type']
			, $args['group']
			, $args['position']
			, $args['meta']
		);
	}

	/**
	 * Update a rank.
	 *
	 * @since 2.6.0
	 *
	 * @param int   $id     The ID of the rank.
	 * @param array $fields The fields to update.
	 *
	 * @return bool Whether the rank was updated successfully.
	 */
	public function update_object( $id, $fields ) {

		return wordpoints_update_rank(
			$id
			, $fields['name']
			, $fields['name']
			, $fields['group']
			, $fields['position']
			, $fields['meta']
		);
	}

	/**
	 * Get a rank by ID.
	 *
	 * @since 2.6.0
	 *
	 * @param int $id The rank ID.
	 *
	 * @return WordPoints_Rank The rank object.
	 */
	public function get_object_by_id( $id ) {

		return new WordPoints_Rank( $id );
	}
}

// EOF
