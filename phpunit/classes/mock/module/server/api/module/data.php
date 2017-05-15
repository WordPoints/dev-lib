<?php

/**
 * Mock module server API module data class for the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock module server API module data class for the PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Module_Server_API_Module_Data
	implements WordPoints_Module_Server_API_Module_DataI {

	/**
	 * The ID of the module the data is for.
	 *
	 * @since 2.6.0
	 *
	 * @var string
	 */
	protected $module_id;

	/**
	 * The module's data.
	 *
	 * @since 2.6.0
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * @since 2.6.0
	 *
	 * @param string $module_id The ID of the module.
	 */
	public function __construct( $module_id = null ) {

		$this->module_id = $module_id;
	}

	/**
	 * @since 2.6.0
	 */
	public function get_id() {
		return $this->module_id;
	}

	/**
	 * @since 2.6.0
	 */
	public function get( $key ) {

		if ( ! isset( $this->data[ $key ] ) ) {
			return null;
		}

		return $this->data[ $key ];
	}

	/**
	 * @since 2.6.0
	 */
	public function set( $key, $value ) {

		$this->data[ $key ] = $value;

		return true;
	}

	/**
	 * @since 2.6.0
	 */
	public function delete( $key ) {

		unset( $this->data[ $key ] );

		return true;
	}
}

// EOF
