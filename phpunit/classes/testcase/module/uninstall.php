<?php

/**
 * WordPoints module uninstall test case.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.5.0
 */

/**
 * Test WordPoints module installation and uninstallation.
 *
 * @since 2.5.0 As WordPoints_Dev_Lib_PHPUnit_TestCase_Module_Uninstall.
 * @since 2.6.0
 */
abstract class WordPoints_PHPUnit_TestCase_Module_Uninstall
	extends WPPPB_TestCase_Uninstall {

	/**
	 * Whether to uninstall only the module, or WordPoints entirely.
	 *
	 * @since 2.6.0
	 *
	 * @var bool
	 */
	protected $uninstall_module_only = false;

	/**
	 * @since 2.5.0 As part of WordPoints_Dev_Lib_PHPUnit_TestCase_Module_Uninstall.
	 * @since 2.6.0
	 */
	public function setUp() {

		$this->uninstall_module_only = getenv( 'WORDPOINTS_ONLY_UNINSTALL_MODULE' );

		$usage_simulator = dirname( __FILE__ )
		    . '/../../../../../tests/phpunit/includes/usage-simulator.php';

		if ( file_exists( $usage_simulator ) ) {
			$this->simulation_file = dirname( __FILE__ )
				. '/../../../simulate-module-use.php';
		}

		if ( ! isset( $this->module_file ) ) {

			$loader = WordPoints_PHPUnit_Bootstrap_Loader::instance();

			$modules = $loader->get_modules();

			$this->module_file = key( $modules );
		}

		parent::setUp();
	}

	/**
	 * @since 2.6.0
	 */
	public function uninstall() {

		if ( ! $this->uninstall_module_only ) {

			parent::uninstall();
			return;
		}

		global $wpdb;

		if ( ! $this->simulated_usage ) {

			$wpdb->query( 'ROLLBACK' );

			// If the module has a usage simulation file, run it remotely.
			$this->simulate_usage();
		}

		// We're going to do real table dropping, not temporary tables.
		remove_filter( 'query', array( $this, '_drop_temporary_tables' ) );

		if ( empty( $this->module_file ) ) {
			$this->fail( 'Error: $module_file property not set.' );
		}

		wordpoints_uninstall_module( $this->module_file );

		$this->flush_cache();
	}
}

// EOF
