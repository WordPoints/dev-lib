<?php

/**
 * Module class used in the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib
 * @since   2.6.0
 */

/**
 * Encapsulates the module being tested.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Module {

	/**
	 * The module's directory.
	 *
	 * @since 2.6.0
	 *
	 * @var string
	 */
	protected $dir;

	/**
	 * The name of the main file of the module.
	 *
	 * @since 2.6.0
	 *
	 * @var string
	 */
	protected $file;

	/**
	 * The module's headers.
	 *
	 * @since 2.6.0
	 *
	 * @var string[]
	 */
	protected $headers;

	/**
	 * @since 2.6.0
	 *
	 * @param string $dir The module's main directory.
	 */
	public function __construct( $dir ) {

		$this->dir = $dir;

		$this->get_data();
	}

	/**
	 * Gets the module's basename.
	 *
	 * @since 2.6.0
	 *
	 * @return string The module's basename.
	 */
	public function get_basename() {
		return substr( $this->file, 0, -4 /* .php */ ) . '/' . $this->file;
	}

	/**
	 * Gets one of the module's file headers.
	 *
	 * @since 2.6.0
	 *
	 * @param string $header The header to get.
	 *
	 * @return string The header value.
	 */
	public function get_header( $header ) {
		return $this->headers[ $header ];
	}

	/**
	 * Gets the module's data.
	 *
	 * @since 2.6.0
	 */
	protected function get_data() {

		$modules = wordpoints_dev_lib_get_modules( $this->dir );

		$this->file = key( $modules );
		$this->headers = $modules[ $this->file ];
	}
}

// EOF
