<?php

/**
 * Mock extension server API extension data class for the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.7.0
 */

/**
 * Mock extension server API extension data class for the PHPUnit tests.
 *
 * @since 2.7.0
 */
class WordPoints_PHPUnit_Mock_Extension_Server_API_Extension_Data
	implements WordPoints_Extension_Server_API_Extension_DataI {

	/**
	 * The ID of the extension the data is for.
	 *
	 * @since 2.7.0
	 *
	 * @var string
	 */
	protected $extension_id;

	/**
	 * The extension's data.
	 *
	 * @since 2.7.0
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * @since 2.7.0
	 *
	 * @param string $extension_id The ID of the extension.
	 */
	public function __construct( $extension_id = null ) {

		$this->extension_id = $extension_id;
	}

	/**
	 * @since 2.7.0
	 */
	public function get_id() {
		return $this->extension_id;
	}

	/**
	 * @since 2.7.0
	 */
	public function get( $key ) {

		if ( ! isset( $this->data[ $key ] ) ) {
			return null;
		}

		return $this->data[ $key ];
	}

	/**
	 * @since 2.7.0
	 */
	public function set( $key, $value ) {

		$this->data[ $key ] = $value;

		return true;
	}

	/**
	 * @since 2.7.0
	 */
	public function delete( $key ) {

		unset( $this->data[ $key ] );

		return true;
	}
}

// EOF
