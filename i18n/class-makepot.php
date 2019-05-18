<?php

/**
 * Class to generate a POT file for a WordPoints extension.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.7.0
 */

/**
 * Generate a POT file for a WordPoints extension.
 *
 * Currently it can generate a POT file for WordPoints itself, and also for
 * extensions.
 *
 * @since 1.3.0
 */
class WordPoints_MakePOT extends MakePOT {

	/**
	 * @since 1.3.0
	 */
	public $projects = array( 'wordpoints', 'wordpoints-extension', 'wordpoints-module' );

	/**
	 * @since 2.6.0
	 */
	protected $max_header_lines = 50;

	/**
	 * @since 1.3.0
	 */
	public function __construct() {

		parent::__construct();

		$this->meta['wordpoints'] = $this->meta['wp-plugin'];

		$this->meta['wordpoints-extension'] = array(
			'description'        => 'Translation of the WordPoints extension {name} {version} by {author}',
			'msgid-bugs-address' => '',
			'copyright-holder'   => '{author}',
			'package-name'       => 'WordPoints {name}',
			'package-version'    => '{version}',
			'comments'           => "Copyright (C) {year} {copyright-holder}\nThis file is distributed under the same license as the {package-name} package.",
		);

		$this->meta['wordpoints-module'] = $this->meta['wordpoints-extension'];
	}

	/**
	 * Generate the POT file for WordPoints.
	 *
	 * @since 1.3.0
	 *
	 * @param string $dir    The directory containing WordPoints's source.
	 * @param string $output The path of the output file.
	 *
	 * @return bool Whether the POT file was generated successfully.
	 */
	public function wordpoints( $dir, $output = null ) {

		if ( is_null( $output ) ) {
			$output = "{$dir}/languages/wordpoints.pot";
		}

		return $this->wp_plugin( $dir, $output, 'wordpoints' );
	}

	/**
	 * Generate the POT file for a WordPoints extension.
	 *
	 * @since 1.3.0
	 * @deprecated 2.7.0
	 *
	 * @param string $dir    The directory containing WordPoints's source.
	 * @param string $output The path of the output file.
	 * @param string $slug   The slug of the extension.
	 *
	 * @return bool Whether the POT file was generated successfully.
	 */
	public function wordpoints_module( $dir, $output = null, $slug = null ) {
		return $this->wordpoints_extension( $dir, $output, $slug );
	}

	/**
	 * Generate the POT file for a WordPoints extension.
	 *
	 * @since 1.3.0 As wordpoints_module().
	 * @since 2.7.0
	 *
	 * @param string $dir    The directory containing WordPoints's source.
	 * @param string $output The path of the output file.
	 * @param string $slug   The slug of the extension.
	 *
	 * @return bool Whether the POT file was generated successfully.
	 */
	public function wordpoints_extension( $dir, $output = null, $slug = null ) {

		if ( is_null( $slug ) ) {
			$slug = $this->guess_plugin_slug( $dir );
		}

		if ( is_null( $output ) ) {
			$output = "{$dir}/languages/{$slug}.pot";
		}

		// Escape pattern-matching characters in the path.
		$extension_escape_root = str_replace(
			array( '*', '?', '[' )
			, array( '[*]', '[?]', '[[]' )
			, $dir
		);

		// Get the top level files.
		$extension_files = glob( "{$extension_escape_root}/*.php" );

		if ( empty( $extension_files ) ) {
			$this->error( 'No extension source files found.' );
			return false;
		}

		$main_file = '';

		foreach ( $extension_files as $extension_file ) {

			if ( ! is_readable( $extension_file ) ) {
				continue;
			}

			$source = $this->get_first_lines( $extension_file, $this->max_header_lines );

			// Stop when we find a file with a Extension Name header in it.
			if ( false !== $this->get_addon_header( 'Extension Name', $source ) ) {
				$main_file = $extension_file;
				break;
			}

			if ( false !== $this->get_addon_header( 'Module Name', $source ) ) {
				$main_file = $extension_file;
				break;
			}
		}

		if ( empty( $main_file ) ) {
			$this->error( 'Couldn\'t locate the main extension file.' );
			return false;
		}

		$placeholders            = array();
		$placeholders['version'] = $this->get_addon_header( 'Version', $source );
		$placeholders['author']  = $this->get_addon_header( 'Author', $source );
		$placeholders['name']    = $this->get_addon_header( 'Extension Name', $source );
		$placeholders['slug']    = $slug;

		if ( empty( $placeholders['name'] ) ) {
			$placeholders['name'] = $this->get_addon_header( 'Module Name', $source );
		}

		// Attempt to extract the strings and write them to the POT file.
		$result = $this->xgettext( 'wordpoints-extension', $dir, $output, $placeholders );

		if ( ! $result ) {
			return false;
		}

		// Now attempt to append the headers from the extension file, so they can be
		// translated too.
		$potextmeta = new WordPoints_PotExtMeta();
		if ( ! $potextmeta->append( $main_file, $output ) ) {
			return false;
		}

		// Adding non-gettexted strings can repeat some phrases, so uniquify them.
		$output_shell = escapeshellarg( $output );
		system( "msguniq {$output_shell} -o {$output_shell}" );

		return true;
	}

	/**
	 * Give an error.
	 *
	 * @since 1.3.0
	 *
	 * @param string $message The error message.
	 */
	public function error( $message ) {
		fwrite( STDERR, $message . "\n" );
	}
}

// EOF
