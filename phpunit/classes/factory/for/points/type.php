<?php

/**
 * A points type factory for use in the unit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Factory for points types.
 *
 * @since 2.6.0
 *
 * @method string create( $args = array(), $generation_definitions = null )
 * @method array create_and_get( $args = array(), $generation_definitions = null )
 * @method string[] create_many( $count, $args = array(), $generation_definitions = null )
 */
class WordPoints_PHPUnit_Factory_For_Points_Type
	extends WP_UnitTest_Factory_For_Thing
	implements WordPoints_PHPUnit_Factory_DeletingI {

	/**
	 * @since 2.6.0
	 */
	public function __construct( $factory = null ) {

		parent::__construct( $factory );

		$this->default_generation_definitions = array(
			'name' => new WP_UnitTest_Generator_Sequence( 'Points type %s' ),
		);
	}

	/**
	 * Create a points type.
	 *
	 * @since 2.6.0
	 *
	 * @param array $args {
	 *        Optional arguments to use.
	 *
	 *        @type string $name   The name of the points type.
	 *        @type string $prefix The prefix to use to format points values.
	 *        @type string $suffix The suffix to use to format points values.
	 * }
	 *
	 * @return string The slug of the points type.
	 */
	public function create_object( $args ) {

		return wordpoints_add_points_type( $args );
	}

	/**
	 * Update a points type.
	 *
	 * @since 2.6.0
	 *
	 * @param string $slug   The slug of the points type.
	 * @param array  $fields The fields to update.
	 *
	 * @return bool Whether the points type was updated successfully.
	 */
	public function update_object( $slug, $fields ) {

		return wordpoints_update_points_type( $slug, $fields );
	}

	/**
	 * Get a points type by slug.
	 *
	 * @since 2.6.0
	 *
	 * @param string $slug The points type slug.
	 *
	 * @return array The points type data.
	 */
	public function get_object_by_id( $slug ) {

		return wordpoints_get_points_type( $slug );
	}

	/**
	 * Delete a points type by slug.
	 *
	 * @since 2.6.0
	 *
	 * @param string $slug The slug of the points type to delete.
	 *
	 * @return bool Whether the points type was deleted successfully.
	 */
	public function delete( $slug ) {

		return wordpoints_delete_points_type( $slug );
	}
}

// EOF
