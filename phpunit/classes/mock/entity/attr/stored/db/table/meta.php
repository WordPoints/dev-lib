<?php

/**
 * Mock meta db table stored entity attribute class for the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock meta db table stored entity attribute class for the PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Entity_Attr_Stored_DB_Table_Meta
	extends WordPoints_Entity_Attr_Stored_DB_Table_Meta {

	/**
	 * @since 2.6.0
	 */
	protected $meta_type = 'test';

	/**
	 * @since 2.6.0
	 */
	protected $meta_key = 'test_attr';

	/**
	 * @since 2.6.0
	 *
	 * @param string $slug      The entity slug.
	 * @param string $meta_type The meta type to set.
	 */
	public function __construct( $slug, $meta_type = null ) {

		if ( isset( $meta_type ) ) {
			$this->meta_type = $meta_type;
		}

		parent::__construct( $slug );
	}

	/**
	 * @since 2.6.0
	 */
	public function get_title() {
		return 'Test Meta DB Table Stored Attribute';
	}

	/**
	 * Get a protected property's value.
	 *
	 * @since 2.6.0
	 *
	 * @param string $var The property name.
	 *
	 * @return mixed The property value.
	 */
	public function get( $var ) {
		return $this->$var;
	}

	/**
	 * Set a protected property's value.
	 *
	 * @since 2.6.0
	 *
	 * @param string $var   The property name.
	 * @param mixed  $value The property value.
	 */
	public function set( $var, $value ) {
		$this->$var = $value;
	}
}

// EOF
