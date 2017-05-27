<?php

/**
 * WordPoints Loader Codeception test module.
 *
 * @package WordPoints_Dev_Lib
 * @since   2.4.0
 */

namespace WordPoints\Tests\Codeception\Modules;

use WordPoints\Tests\Codeception\BlackHoleCache;
use Codeception\Module;
use Codeception\Configuration;
use Codeception\Exception\ModuleException;
use Codeception\TestCase;

/**
 * A module to load and activate WordPoints in the context of the tests.
 *
 * @since 2.4.0
 */
class WordPointsLoader extends Module {

	/**
	 * @since 2.4.0
	 */
	protected $config = array( 'module' => null );

	/**
	 * @since 2.4.0
	 */
	public function _cleanup() {

		parent::_cleanup();

		$this->reset_db();
	}

	/**
	 * @since 2.4.0
	 */
	public function _initialize() {

		// Load everything up and install it.
		$this->load_wordpress();
		$this->load_wordpoints();

		if ( $this->config['module'] ) {

			// Fix a bug in Codeception 2.2.1.
			// (https://github.com/Codeception/Codeception/issues/3218)
			if ( '%WORDPOINTS_MODULE%' === $this->config['module'] ) {
				$this->config['module'] = getenv( 'WORDPOINTS_MODULE' );
			}

			$this->load_wordpoints_module( $this->config['module'] );
		}

		// Disable time-consuming unnecessary features, like update checks.
		$this->streamline_wordpress();

		// Now get a dump of the pristine database so that we can restore it later.
		$this->create_db_dump( $this->get_db_dump_file_name() );

		// Suspend caching, since the DB can be modified during the tests.
		$this->suspend_caching();
	}

	/**
	 * Loads WordPress.
	 *
	 * @since 2.4.0
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

		// We indicate whether to run as multisite via a file.
		// Properly handling for this must be added to the site's wp-config.php.
		$multisite_file = getenv( 'WP_TESTS_DIR' ) . '/../../is-multisite';

		if ( getenv( 'WP_MULTISITE' ) ) {
			touch( $multisite_file );
		} elseif ( file_exists( $multisite_file ) ) {
			unlink( $multisite_file );
		}

		// Catch output from PHPUnit bootstrap.
		ob_start();

		/**
		 * Sets up the WordPress test environment.
		 *
		 * @since 2.4.0
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
	 * @since 2.4.0
	 *
	 * @throws ModuleException If there is an error activating WordPoints.
	 */
	protected function load_wordpoints() {

		$result = activate_plugin(
			'wordpoints/wordpoints.php'
			, ''
			, (bool) getenv( 'WORDPOINTS_NETWORK_ACTIVE' )
		);

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
	 * @since 2.4.0
	 *
	 * @param string $module The module, e.g., 'module/module.php'.
	 *
	 * @throws ModuleException If there is an error activating the module.
	 */
	protected function load_wordpoints_module( $module ) {

		$result = wordpoints_activate_module(
			$module
			, ''
			, (bool) getenv( 'WORDPOINTS_MODULE_NETWORK_ACTIVE' )
		);

		if ( is_wp_error( $result ) ) {
			throw new ModuleException(
				__CLASS__
				, "\nError activating WordPoints module: " . $result->get_error_message()
			);
		}

		echo "Running WordPoints module {$module}\n";
	}

	/**
	 * Disable time-consuming WordPress features that are unneeded during our tests.
	 *
	 * @since 2.4.0
	 */
	protected function streamline_wordpress() {

		$this->disable_update_checks();
		$this->disable_dashboard_feed_widgets();
		$this->disable_compression_testing();
		$this->disable_cron();
	}

	/**
	 * Disables checks for updates.
	 *
	 * Checking for updates can seriously slow down WordPress. We don't need to run
	 * these checks during the tests, so we disable them.
	 *
	 * @since 2.4.0
	 */
	protected function disable_update_checks() {

		foreach ( array( 'update_core', 'update_plugins', 'update_themes' ) as $transient ) {

			$array = array( 'last_checked' => time() + DAY_IN_SECONDS );

			if ( 'update_core' === $transient ) {
				$array['version_checked'] = $GLOBALS['wp_version'];
				$array['updates'] = array();
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
	 * @since 2.4.0
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
	 * @since 2.4.0
	 */
	protected function disable_compression_testing() {
		update_option( 'can_compress_scripts', 1 );
	}

	/**
	 * Disable WordPress cron.
	 *
	 * Prevents cron requests from being spawned, as usually these aren't important
	 * to the tests and so just take up unnecessary time.
	 *
	 * @since 2.7.0
	 */
	protected function disable_cron() {

		// See spawn_cron().
		set_transient(
			'doing_cron'
			, sprintf( '%.22F', microtime( true ) + 9 * MINUTE_IN_SECONDS )
		);
	}

	/**
	 * Get the name of the database dump file.
	 *
	 * @since 2.4.0
	 */
	protected function get_db_dump_file_name() {

		return Configuration::outputDir() . 'WordPointsLoaderSQLDump.sql';
	}

	/**
	 * Creates a dump of the database using `mysqldump`.
	 *
	 * @since 2.4.0
	 *
	 * @param string $dump_file The file to dump the database to.
	 *
	 * @throws ModuleException If creating the dump failed.
	 */
	protected function create_db_dump( $dump_file ) {

		$result = shell_exec(
			vsprintf(
				'MYSQL_PWD=%s mysqldump --host=%s -u %s %s 2>&1 1> %s'
				, array(
					escapeshellarg( DB_PASSWORD ),
					escapeshellarg( DB_HOST ),
					escapeshellarg( DB_USER ),
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
	 * @since 2.4.0
	 */
	protected function reset_db() {

		$this->drop_db_tables();
		$this->load_dump_into_db( $this->get_db_dump_file_name() );
	}

	/**
	 * Drop all of the database tables.
	 *
	 * @since 2.4.0
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
	 * @since 2.4.0
	 *
	 * @param string $dump_file The path to the dump file to load into the DB.
	 *
	 * @throws ModuleException If the database dump couldn't be loaded.
	 */
	protected function load_dump_into_db( $dump_file ) {

		$result = shell_exec(
			vsprintf(
				'cat %s | MYSQL_PWD=%s mysql --host=%s -u %s %s 2>&1 1> /dev/null'
				, array(
					escapeshellarg( $dump_file ),
					escapeshellarg( DB_PASSWORD ),
					escapeshellarg( DB_HOST ),
					escapeshellarg( DB_USER ),
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
	 * @since 2.4.0
	 */
	protected function suspend_caching() {

		global $wp_object_cache;

		$wp_object_cache = new BlackHoleCache();
	}

	/**
	 * Flush the WordPress object cache.
	 *
	 * @since 2.4.0
	 * @deprecated 2.5.0 Now implied by self::suspend_caching().
	 */
	protected function flush_cache() {

		_deprecated_function( __FUNCTION__, '2.5.0 of the dev-lib' );

		wp_cache_flush();
	}
}

// EOF
