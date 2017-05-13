<?php

/**
 * EDD SL Free module server remote simulator class.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since   2.7.0
 */

/**
 * Set up the remote server to handle an EDD Software Licenses Free requests.
 *
 * @since 2.7.0
 */
class WordPoints_PHPUnit_Remote_Simulator_Module_Server_API_EDD_SL_Free
	extends WordPoints_PHPUnit_Remote_Simulator_Module_Server_API_EDD_SL {

	/**
	 * @since 2.7.0
	 */
	public function add_dependencies( WPPPB_Loader $loader ) {

		parent::add_dependencies( $loader );

		$loader->add_plugin( 'edd-sl-free/edd-sl-free.php' );
	}

	/**
	 * @since 2.7.0
	 */
	public function setup() {

		// Create the download.
		wp_insert_post(
			array(
				'import_id'   => 124,
				'post_type'   => 'download',
				'post_status' => 'publish',
				'post_title'  => 'Free Download',
			)
		);

		add_post_meta( 124, 'edd_price', '0.00' );
		add_post_meta( 124, '_edd_sl_version', '1.2.4' );
		add_post_meta( 124, '_edd_sl_changelog', 'A test changelog.' );
		add_post_meta( 124, '_edd_sl_upgrade_file_key', 0 );
		add_post_meta( 124, 'edd_download_files', array( array( 'file' => 'https://example.org/edd-sl/package_download/124.zip' ) ) );

		parent::setup();
	}
}

// EOF
