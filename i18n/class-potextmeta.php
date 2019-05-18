<?php

/**
 * Class to add metadata strings from an extension header to a POT file.
 *
 * @package WordPoints_Dev_Lib
 * @since   2.7.0
 */

/**
 * Add metadata strings from a WordPoints extension header to a POT file.
 *
 * @since 1.3.0
 */
class WordPoints_PotExtMeta extends PotExtMeta {

	/**
	 * @since 1.3.0
	 */
	public function __construct() {

		$this->headers[] = 'Extension Name';
		$this->headers[] = 'Extension URI';
		$this->headers[] = 'Module Name';
		$this->headers[] = 'Module URI';
	}

	/**
	 * @since 1.3.0
	 */
	public function load_from_file( $ext_filename ) {

		$makepot = new WordPoints_MakePOT();
		$source  = $makepot->get_first_lines( $ext_filename, 50 );
		$pot     = '';
		$po      = new PO();

		foreach ( $this->headers as $header ) {

			$string = $makepot->get_addon_header( $header, $source );

			if ( ! $string ) {
				continue;
			}

			$args = array(
				'singular'           => $string,
				'extracted_comments' => $header . ' of the extension',
			);

			$entry = new Translation_Entry( $args );

			$pot .= "\n" . $po->export_entry( $entry ) . "\n";
		}

		return $pot;
	}
}

// EOF
