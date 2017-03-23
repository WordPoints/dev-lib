<?php

/**
 * Easy Digital Downloads Software Licenses module server API remote simulator.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since   2.6.0
 */

/**
 * Set up the remote server to handle an EDD Software Licenses request.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Remote_Simulator_Module_Server_API_EDD_SL
	extends WordPoints_PHPUnit_Remote_Simulator {

	/**
	 * @since 2.6.0
	 */
	public function add_dependencies( WPPPB_Loader $loader ) {

		$loader->add_plugin( 'easy-digital-downloads/easy-digital-downloads.php' );
		$loader->add_plugin( 'edd-software-licensing/edd-software-licenses.php' );
	}

	/**
	 * @since 2.6.0
	 */
	public function setup() {

		// Create the download.
		wp_insert_post(
			array(
				'import_id'   => 123,
				'post_type'   => 'download',
				'post_status' => 'publish',
				'post_title'  => 'Test Download',
			)
		);

		add_post_meta( 123, 'edd_price', '100.00' );
		add_post_meta( 123, '_edd_sl_version', '1.2.3' );
		add_post_meta( 123, '_edd_sl_changelog', 'A test changelog.' );

		// Create the license.
		$license_id = wp_insert_post(
			array( 'post_type' => 'edd_license', 'post_status' => 'publish' )
		);

		add_post_meta( $license_id, '_edd_sl_key', 'test_key' );
		add_post_meta( $license_id, '_edd_sl_download_id', 123 );
		add_post_meta( $license_id, '_edd_sl_expiration', time() + DAY_IN_SECONDS );

		// Add a second license.
		$license_id = wp_insert_post(
			array( 'post_type' => 'edd_license', 'post_status' => 'publish' )
		);

		add_post_meta( $license_id, '_edd_sl_key', 'test_key_2' );
		add_post_meta( $license_id, '_edd_sl_download_id', 123 );
		add_post_meta( $license_id, '_edd_sl_expiration', time() + DAY_IN_SECONDS );
		add_post_meta( $license_id, '_edd_sl_status', 'active' );

		edd_software_licensing()->insert_site( $license_id, $_POST['url'] );
	}
}

// EOF
