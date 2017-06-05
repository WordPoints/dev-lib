<?php

/**
 * Extension class used in the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib
 * @since   2.7.0
 */

/**
 * Encapsulates the extension being tested.
 *
 * @since 2.6.0 As WordPoints_PHPUnit_Module.
 * @since 2.7.0
 */
class WordPoints_PHPUnit_Extension {

	/**
	 * The extension's directory.
	 *
	 * @since 2.6.0 As part of WordPoints_PHPUnit_Module.
	 * @since 2.7.0
	 *
	 * @var string
	 */
	protected $dir;

	/**
	 * The name of the main file of the extension.
	 *
	 * @since 2.6.0 As part of WordPoints_PHPUnit_Module.
	 * @since 2.7.0
	 *
	 * @var string
	 */
	protected $file;

	/**
	 * The extension's headers.
	 *
	 * @since 2.6.0 As part of WordPoints_PHPUnit_Module.
	 * @since 2.7.0
	 *
	 * @var string[]
	 */
	protected $headers;

	/**
	 * @since 2.6.0 As part of WordPoints_PHPUnit_Module.
	 * @since 2.7.0
	 *
	 * @param string $dir The extension's main directory.
	 */
	public function __construct( $dir ) {

		$this->dir = $dir;

		$this->get_data();
	}

	/**
	 * Gets the extension's basename.
	 *
	 * @since 2.6.0 As part of WordPoints_PHPUnit_Module.
	 * @since 2.7.0
	 *
	 * @return string The extension's basename.
	 */
	public function get_basename() {
		return substr( $this->file, 0, -4 /* .php */ ) . '/' . $this->file;
	}

	/**
	 * Gets one of the extension's file headers.
	 *
	 * @since 2.6.0 As part of WordPoints_PHPUnit_Module.
	 * @since 2.7.0
	 *
	 * @param string $header The header to get.
	 *
	 * @return string The header value.
	 */
	public function get_header( $header ) {
		return $this->headers[ $header ];
	}

	/**
	 * Gets the extension's data.
	 *
	 * @since 2.6.0 As part of WordPoints_PHPUnit_Module.
	 * @since 2.7.0
	 */
	protected function get_data() {

		$extensions = wordpoints_dev_lib_get_extensions( $this->dir );

		$this->file = key( $extensions );
		$this->headers = $extensions[ $this->file ];
	}
}

// EOF
