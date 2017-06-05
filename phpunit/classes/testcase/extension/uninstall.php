<?php

/**
 * WordPoints extension uninstall test case.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.7.0
 */

/**
 * Test WordPoints extension installation and uninstallation.
 *
 * @since 2.5.0 As WordPoints_Dev_Lib_PHPUnit_TestCase_Module_Uninstall.
 * @since 2.6.0 As WordPoints_PHPUnit_TestCase_Module_Uninstall.
 * @since 2.7.0
 */
abstract class WordPoints_PHPUnit_TestCase_Extension_Uninstall
	extends WPPPB_TestCase_Uninstall {

	/**
	 * Whether to uninstall only the extension, or WordPoints entirely.
	 *
	 * @since 2.7.0
	 *
	 * @var bool
	 */
	protected $uninstall_extension_only = false;

	/**
	 * Full path to the extension to uninstall.
	 *
	 * If left undefined, it will be determined automatically from the loader.
	 *
	 * @since 2.7.0
	 *
	 * @var string
	 */
	protected $extension_file;

	/**
	 * @since 2.5.0 As part of WordPoints_Dev_Lib_PHPUnit_TestCase_Module_Uninstall.
	 * @since 2.6.0 As WordPoints_PHPUnit_TestCase_Module_Uninstall.
	 * @since 2.7.0
	 */
	public function setUp() {

		$this->uninstall_extension_only = (
			getenv( 'WORDPOINTS_ONLY_UNINSTALL_EXTENSION' )
			|| getenv( 'WORDPOINTS_ONLY_UNINSTALL_MODULE' ) // Back-pat.
		);

		$usage_simulator = dirname( __FILE__ )
			. '/../../../../../tests/phpunit/includes/usage-simulator.php';

		if ( file_exists( $usage_simulator ) ) {
			$this->simulation_file = dirname( __FILE__ )
				. '/../../../simulate-extension-use.php';
		}

		if ( ! isset( $this->extension_file ) ) {

			$loader = WordPoints_PHPUnit_Bootstrap_Loader::instance();

			$extensions = $loader->get_extensions();

			$this->extension_file = key( $extensions );
		}

		parent::setUp();
	}

	/**
	 * @since 2.6.0 As WordPoints_PHPUnit_TestCase_Module_Uninstall.
	 * @since 2.7.0
	 */
	public function uninstall() {

		if ( ! $this->uninstall_extension_only ) {

			parent::uninstall();
			return;
		}

		global $wpdb;

		if ( ! $this->simulated_usage ) {

			$wpdb->query( 'ROLLBACK' );

			// If the extension has a usage simulation file, run it remotely.
			$this->simulate_usage();
		}

		// We're going to do real table dropping, not temporary tables.
		remove_filter( 'query', array( $this, '_drop_temporary_tables' ) );

		if ( empty( $this->extension_file ) ) {
			$this->fail( 'Error: $extension_file property not set.' );
		}

		wordpoints_uninstall_module( $this->extension_file );

		$this->flush_cache();
	}
}

// EOF
