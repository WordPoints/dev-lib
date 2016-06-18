<?php

/**
 * WordPoints Loader Codeception test module.
 *
 * @package wordpoints-hooks-api
 * @since   1.0.0
 */

namespace WordPoints\Tests\Codeception\Modules;

use Codeception\Module;
use Codeception\Configuration;
use Codeception\Exception\ModuleException;
use Codeception\TestCase;

/**
 * A module to load and activate WordPoints in the context of the tests.
 *
 * @since 1.0.0
 */
class WordPointsLoader extends Module {

	/**
	 * @since 1.0.0
	 */
	public function _cleanup() {

		parent::_cleanup();

		$this->reset_db();
	}

	/**
	 * @since 1.0.0
	 */
	public function _initialize() {

		// Load everything up and install it.
		$this->load_wordpress();
		$this->load_wordpoints();
		$this->load_wordpoints_module();

		// Disable time-consuming unnecessary features, like update checks.
		$this->streamline_wordpress();

		// Now get a dump of the pristine database so that we can restore it later.
		$this->create_db_dump( $this->get_db_dump_file_name() );

		// Flush the cache and suspend caching, since the DB can be modified during
		// the tests.
		$this->suspend_caching();
		$this->flush_cache();
	}

	/**
	 * Loads WordPress.
	 *
	 * @since 1.0.0
	 *
	 * @throws ModuleException If the path to the tests directory isn't set.
	 */
	protected function load_wordpress() {

		echo( 'Loading WordPress...' . PHP_EOL );

		if ( ! getenv( 'WP_TESTS_DIR' ) ) {

			throw new ModuleException(
				__CLASS__
				, "\nWP_TESTS_DIR is not set."
			);
		}

		// Catch output from PHPUnit bootstrap.
		ob_start();

		/**
		 * Sets up the WordPress test environment.
		 *
		 * @since 1.0.0
		 */
		require getenv( 'WP_TESTS_DIR' ) . '/includes/bootstrap.php';

		$this->debugSection( 'WordPress Bootstrap Output', ob_get_clean() );

		echo(
			'Running WordPress '
			. $GLOBALS['wp_version']
			. ( is_multisite() ? ' multisite' : '' )
			. PHP_EOL
		);
	}

	/**
	 * Load and activate WordPoints.
	 *
	 * @since 1.0.0
	 *
	 * @throws ModuleException If there is an error activating WordPoints.
	 */
	protected function load_wordpoints() {

		$result = activate_plugin( 'wordpoints/wordpoints.php' );

		if ( is_wp_error( $result ) ) {
			throw new ModuleException(
				__CLASS__
				, "\nError activating WordPoints: " . $result->get_error_message()
			);
		}

		echo(
			'Running WordPoints '
			. WORDPOINTS_VERSION
			. ( is_wordpoints_network_active() ? ' network active' : '' )
			. PHP_EOL
		);
	}

	/**
	 * Load and activate the module.
	 *
	 * @since 1.0.0
	 *
	 * @throws ModuleException If there is an error activating the module.
	 */
	protected function load_wordpoints_module() {

		$result = wordpoints_activate_module( 'hooks-api/hooks-api.php' );

		if ( is_wp_error( $result ) ) {
			throw new ModuleException(
				__CLASS__
				,
				"\nError activating WordPoints module: " . $result->get_error_message()
			);
		}

		// Initialize autoloading, since this is normally hooked to the modules
		// loaded action, which has already been called.
		\WordPoints_Class_Autoloader::init();
	}

	/**
	 * Disable time-consuming WordPress features that are unneeded during our tests.
	 *
	 * @since 1.0.0
	 */
	protected function streamline_wordpress() {

		$this->disable_update_checks();
		$this->disable_dashboard_feed_widgets();
		$this->disable_compression_testing();
	}

	/**
	 * Disables checks for updates.
	 *
	 * Checking for updates can seriously slow down WordPress. We don't need to run
	 * these checks during the tests, so we disable them.
	 *
	 * @since 1.0.0
	 */
	protected function disable_update_checks() {

		foreach ( array( 'update_core', 'update_plugins', 'update_themes' ) as $transient ) {

			$array = array( 'last_checked' => time() + DAY_IN_SECONDS );

			if ( 'update_core' === $transient ) {
				$array['version_checked'] = $GLOBALS['wp_version'];
			}

			set_site_transient( $transient, (object) $array );
		}
	}

	/**
	 * Disables the feed fetching of the dashboard widgets.
	 *
	 * Fetching these feeds can slow down WordPress. We don't need to fetch them
	 * during the tests, so we disable them.
	 *
	 * @since 1.0.0
	 */
	protected function disable_dashboard_feed_widgets() {

		set_transient(
			'dash_' . md5( 'dashboard_primary_' . get_locale() )
			, 'Disabled by WordPoints Loader Codeception module.'
		);
	}

	/**
	 * Disable compression testing.
	 *
	 * When an admin visits the site for the first time this triggers an Ajax request
	 * which is unnecessary for us and just slows things down.
	 *
	 * @link https://developer.wordpress.org/reference/functions/compression_test/
	 *
	 * @since 1.0.0
	 */
	protected function disable_compression_testing() {
		update_option( 'can_compress_scripts', 1 );
	}

	/**
	 * Get the name of the database dump file.
	 *
	 * @since 1.0.0
	 */
	protected function get_db_dump_file_name() {

		return Configuration::outputDir() . 'WordPointsLoaderSQLDump.sql';
	}

	/**
	 * Creates a dump of the database using `mysqldump`.
	 *
	 * @since 1.0.0
	 *
	 * @param string $dump_file The file to dump the database to.
	 *
	 * @throws ModuleException If creating the dump failed.
	 */
	protected function create_db_dump( $dump_file ) {

		$result = shell_exec(
			vsprintf(
				'mysqldump --host=%s -u %s --password=%s %s 2>&1 1> %s'
				, array(
					escapeshellarg( DB_HOST ),
					escapeshellarg( DB_USER ),
					escapeshellarg( DB_PASSWORD ),
					escapeshellarg( DB_NAME ),
					escapeshellarg( $dump_file ),
				)
			)
		);

		if ( ! empty( $result ) ) {
			throw new ModuleException(
				__CLASS__
				, "\nFailed to create database dump: {$result}"
			);
		}
	}

	/**
	 * Reset the database to the unaltered state.
	 *
	 * @since 1.0.0
	 */
	protected function reset_db() {

		$this->drop_db_tables();
		$this->load_dump_into_db( $this->get_db_dump_file_name() );
	}

	/**
	 * Drop all of the database tables.
	 *
	 * @since 1.0.0
	 */
	protected function drop_db_tables() {

		global $wpdb;

		$wpdb->db_connect();

		$wpdb->query( 'SET FOREIGN_KEY_CHECKS=0;' );

		$tables = $wpdb->get_col(
			"SHOW FULL TABLES WHERE TABLE_TYPE LIKE '%TABLE';"
		);

		foreach ( $tables as $table ) {
			$wpdb->query( 'DROP TABLE `' . $table . '`' );
		}

		$wpdb->query( 'SET FOREIGN_KEY_CHECKS=1;' );
	}

	/**
	 * Loads the data from the database dump into the database.
	 *
	 * @since 1.0.0
	 *
	 * @param string $dump_file The path to the dump file to load into the DB.
	 *
	 * @throws ModuleException If the database dump couldn't be loaded.
	 */
	protected function load_dump_into_db( $dump_file ) {

		$result = shell_exec(
			vsprintf(
				'cat %s | mysql --host=%s -u %s --password=%s %s 2>&1 1> /dev/null'
				, array(
					escapeshellarg( $dump_file ),
					escapeshellarg( DB_HOST ),
					escapeshellarg( DB_USER ),
					escapeshellarg( DB_PASSWORD ),
					escapeshellarg( DB_NAME ),
				)
			)
		);

		if ( ! empty( $result ) ) {
			throw new ModuleException(
				__CLASS__
				, "\nFailed to load database dump into database: {$result}"
			);
		}
	}

	/**
	 * Suspend object caching in WordPress.
	 *
	 * @since 1.0.0
	 */
	protected function suspend_caching() {
		wp_suspend_cache_addition( true );
	}

	/**
	 * Flush the WordPress object cache.
	 *
	 * @since 1.0.0
	 */
	protected function flush_cache() {
		wp_cache_flush();
	}
}

// EOF
