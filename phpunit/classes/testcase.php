<?php

/**
 * Base test case class.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Parent test case.
 *
 * @since 2.6.0
 *
 * @property WordPoints_PHPUnit_Factory_Stub $factory The factory.
 */
abstract class WordPoints_PHPUnit_TestCase extends WP_UnitTestCase {

	/**
	 * Fixtures to share across all of the tests in this testcase.
	 *
	 * If you need to use some fixtures across your tests and these will not be
	 * modified during the tests, then you can ask the testcase to automatically
	 * create them for you here. This will cause them to only be created once, before
	 * the first test, and then only destroyed once, after the last test.
	 *
	 * You can access the IDs of the created fixtures through {@see
	 * self::$fixture_ids}.
	 *
	 * The keys of the array should correspond to factory properties on the core or
	 * WordPoints factories. Values are accepted in several formats:
	 *
	 * - An integer to denote the number of items to create of that type.
	 * - An array of args to pass in to the factory.
	 * - An array with the keys 'count' and 'args', corresponding to the above two
	 *   options, respectively. The 'get' key may also be used, which when true will
	 *   cause the fixture to be retrieved from the database and added to {@see
	 *   self::$fixtures}.
	 * - An array of arrays following the above format.
	 *
	 * By default the count is 1, the args are an empty array, and the fixture is not
	 * retrieved.
	 *
	 * The values of each of the args to pass to the factory can reference the IDs of
	 * other fixtures that have been requested, using the format `$fixture_ids[type][0]`,
	 * where `type` is the slug of the type of fixture, and `0` is the index of the
	 * particular fixture ID of that type that you want to use. The fixture being
	 * referenced must have already been created, so you will need to order your
	 * array of requested fixtures accordingly.
	 *
	 * Note that if a site is requested to be created, it will only be created if
	 * multisite is enabled. The same is true for any fixtures whose args reference
	 * the IDs of site fixtures.
	 *
	 * @since 2.6.0
	 *
	 * @var array
	 */
	protected $shared_fixtures;

	/**
	 * The IDs of fixtures that were requested to be created via `$shared_fixtures`.
	 *
	 * Indexed just as the `self::$shared_fixtures` array is, with the slugs of the
	 * types of fixtures.
	 *
	 * @since 2.6.0
	 *
	 * @var array[]
	 */
	protected $fixture_ids;

	/**
	 * The fixtures that were requested to be retrieved via `$shared_fixtures`.
	 *
	 * Indexed just as the `self::$shared_fixtures` array is, with the slugs of the
	 * types of fixtures.
	 *
	 * @since 2.6.0
	 *
	 * @var array[]
	 */
	protected $fixtures;

	/**
	 * The default points data set up for each test.
	 *
	 * @since 2.6.0
	 *
	 * @type array $points_data
	 */
	protected $points_data;

	/**
	 * The list of filters currently being watched.
	 *
	 * @since 2.6.0
	 *
	 * @see WordPoints_PHPUnit_TestCase::listen_for_filter()
	 *
	 * @type WordPoints_PHPUnit_Mock_Filter[] $watched_filters
	 */
	protected $watched_filters = array();

	/**
	 * The class name of the widget type that this test is for.
	 *
	 * @since 2.6.0
	 *
	 * @type string $widget_class
	 */
	protected $widget_class;

	/**
	 * The name of the shortcode that this test is for.
	 *
	 * @since 2.7.0
	 *
	 * @type string $shortcode
	 */
	protected $shortcode;

	/**
	 * The WordPoints component that this testcase is for.
	 *
	 * @since 2.6.0
	 *
	 * @type string $wordpoints_component
	 */
	protected $wordpoints_component;

	/**
	 * The slug of the WordPoints extension that this testcase is for.
	 *
	 * The slug of the extension is the name of its base directory.
	 *
	 * @since 2.6.0 As $wordpoints_module.
	 * @since 2.7.0
	 *
	 * @var string
	 */
	protected $wordpoints_extension;

	/**
	 * The slug of the WordPoints module that this testcase is for.
	 *
	 * The slug of the module is the name of its base directory.
	 *
	 * @since 2.6.0
	 * @deprecated 2.7.0 Use $wordpoints_extension instead.
	 *
	 * @var string
	 */
	protected $wordpoints_module;

	/**
	 * The database schema defined by this component.
	 *
	 * @see self::get_db_schema()
	 *
	 * @since 2.6.0
	 *
	 * @var string
	 */
	protected $db_schema;

	/**
	 * The database tables created by this component.
	 *
	 * @see self::get_db_tables()
	 *
	 * @since 2.6.0
	 *
	 * @var string[]
	 */
	protected $db_tables;

	/**
	 * The previous version if this is an update testcase.
	 *
	 * @since 2.6.0
	 *
	 * @type string $previous_version
	 */
	protected $previous_version;

	/**
	 * A mock filesystem object.
	 *
	 * @since 2.6.0
	 *
	 * @var WP_Mock_Filesystem
	 */
	protected $mock_fs;

	/**
	 * A list of global variables to back up between tests.
	 *
	 * PHPUnit has built-in support for backing up globals between tests, but it has
	 * a few issues that make it difficult to use. First, it has only a blacklist, no
	 * whitelist. That means that when you enable the backup globals feature for a
	 * test, all of the globals will be backed up. This can be time-consuming, and
	 * also leads to breakage because of the way that the globals are backed up.
	 * PHPUnit backs up the globals by serializing them, which is necessary for some
	 * uses, but causes `$wpdb` to stop working after the globals are restored,
	 * causing all tests after that to fail. Our implementation here is much simpler,
	 * and is based on a whitelist so that we can just back up the globals that
	 * actually need to be backed up.
	 *
	 * @since 2.6.0
	 *
	 * @var string[]
	 */
	protected $backup_globals;

	/**
	 * Backed up values of global variables that are modified in the tests.
	 *
	 * @since 2.6.0
	 *
	 * @var array
	 */
	protected $backed_up_globals = array();

	/**
	 * A backup of the main app.
	 *
	 * @since 2.6.0
	 *
	 * @var WordPoints_App
	 */
	public static $backup_app;

	/**
	 * The IDs of the fixtures created for the testcase that is currently running.
	 *
	 * @since 2.6.0
	 *
	 * @var array[]
	 */
	protected static $_fixtures_ids = array();

	/**
	 * The fixtures created for the testcase that is currently running.
	 *
	 * Only populated with those fixtures that have been requested to be retrieved.
	 *
	 * @since 2.6.0
	 *
	 * @var array[]
	 */
	protected static $_fixtures = array();

	/**
	 * The IDs of extra fixtures created for the testcase that is currently running.
	 *
	 * @since 2.6.0
	 *
	 * @var array[]
	 */
	public static $extra_fixture_ids = array();

	/**
	 * Whether currently in the process of creating fixtures for the testcase.
	 *
	 * @since 2.6.0
	 *
	 * @var bool
	 */
	public static $creating_fixtures = false;

	/**
	 * @since 2.6.0
	 */
	public static function factory() {
		return parent::factory();
	}

	/**
	 * @since 2.6.0
	 */
	public static function tearDownAfterClass() {

		if ( ! empty( self::$_fixtures_ids ) ) {

			/** @var WordPoints_PHPUnit_Factory_Stub $factories */
			$factories = self::factory();

			$fixtures = array_merge_recursive(
				self::$_fixtures_ids
				, self::$extra_fixture_ids
			);

			foreach ( $fixtures as $type => $ids ) {

				/** @var WP_UnitTest_Factory_For_Thing $factory */
				$factory = isset( $factories->$type )
					? $factories->$type
					: $factories->wordpoints->$type;

				if ( $factory instanceof WordPoints_PHPUnit_Factory_DeletingI ) {
					$delete_method = array( $factory, 'delete' );
				} else {
					$delete_method = array( __CLASS__, "delete_{$type}" );
				}

				array_map( $delete_method, $ids );
			}

			self::commit_transaction();

			self::$_fixtures_ids     = array();
			self::$_fixtures         = array();
			self::$extra_fixture_ids = array();
		}

		parent::tearDownAfterClass();
	}

	/**
	 * Fully deletes a site from the network.
	 *
	 * @since 2.6.0
	 *
	 * @param int $site_id The ID of the site to delete.
	 */
	protected static function delete_site( $site_id ) {

		wpmu_delete_blog( $site_id, true );
	}

	/**
	 * Fully deletes a post.
	 *
	 * @since 2.6.0
	 *
	 * @param int $id The post ID.
	 */
	public static function delete_post( $id ) {

		wp_delete_post( $id, true );
	}

	/**
	 * Fully deletes a comment.
	 *
	 * @since 2.6.0
	 *
	 * @param int $id The comment ID.
	 */
	public static function delete_comment( $id ) {

		wp_delete_comment( $id, true );
	}

	/**
	 * Fully deletes a points type.
	 *
	 * @since 2.6.0
	 *
	 * @param string $slug The points type slug.
	 */
	public static function delete_points_type( $slug ) {

		wordpoints_delete_points_type( $slug );
	}

	/**
	 * @since 2.6.0
	 */
	protected function checkRequirements() {

		parent::checkRequirements();

		$annotations = $this->getAnnotations();

		foreach ( array( 'class', 'method' ) as $depth ) {

			if ( isset( $annotations[ $depth ]['WordPoints-requires'] ) ) {
				foreach ( $annotations[ $depth ]['WordPoints-requires'] as $function ) {

					$name = $function;

					if ( ! function_exists( $function ) ) {
						$function = array( $this, $function );
					}

					if ( ! call_user_func( $function ) ) {
						$this->markTestSkipped( "{$name}() must be true." );
					}
				}
			}

			if ( empty( $annotations[ $depth ]['requires'] ) ) {
				continue;
			}

			$requires = array_flip( $annotations[ $depth ]['requires'] );

			if ( isset( $requires['WordPress multisite'] ) && ! is_multisite() ) {
				$this->markTestSkipped( 'Multisite must be enabled.' );
			} elseif ( isset( $requires['WordPress !multisite'] ) && is_multisite() ) {
				$this->markTestSkipped( 'Multisite must not be enabled.' );
			}

			if (
				isset( $requires['WordPoints network-active'] )
				&& ! is_wordpoints_network_active()
			) {
				$this->markTestSkipped( 'WordPoints must be network-activated.' );
			} elseif (
				isset( $requires['WordPoints !network-active'] )
				&& is_wordpoints_network_active()
			) {
				$this->markTestSkipped( 'WordPoints must not be network-activated.' );
			}

			if ( isset( $requires['WordPoints version'] ) ) {

				$version = $annotations[ $depth ]['WordPoints-version'][0];

				if ( ! version_compare( WORDPOINTS_VERSION, $version, '>=' ) ) {
					$this->markTestSkipped( "WordPoints version must be >= ${version}." );
				}
			}

		} // End foreach ( depth ).
	}

	/**
	 * @since 2.6.0
	 */
	public function setUp() {

		if ( ! isset( $this->factory->wordpoints ) ) {
			$this->factory->wordpoints = WordPoints_PHPUnit_Factory::$factory;
		}

		if ( ! empty( self::$_fixtures_ids ) ) {

			$this->fixture_ids = self::$_fixtures_ids;
			$this->fixtures    = self::$_fixtures;

		} elseif ( isset( $this->shared_fixtures ) ) {

			self::$creating_fixtures = true;

			foreach ( $this->shared_fixtures as $type => $definitions ) {

				if ( 'site' === $type ) {

					if ( ! is_multisite() ) {
						continue;
					}

					$factory = $this->factory->blog;

				} else {

					/** @var WP_UnitTest_Factory_For_Thing $factory */
					$factory = isset( $this->factory->$type )
						? $this->factory->$type
						: $this->factory->wordpoints->$type;
				}

				self::$_fixtures_ids[ $type ] = array();
				self::$_fixtures[ $type ]     = array();

				if ( is_int( $definitions ) ) {
					$definitions = array( array( 'count' => $definitions ) );
				} elseif ( isset( $definitions['count'] ) || isset( $definitions['get'] ) ) {
					$definitions = array( $definitions );
				}

				foreach ( $definitions as $definition ) {

					$count_defined = isset( $definition['count'] );
					$count         = $count_defined ? $definition['count'] : 1;

					$get_defined = isset( $definition['get'] );
					$get         = $get_defined ? $definition['get'] : false;

					if ( isset( $definition['args'] ) ) {
						$args = $definition['args'];
					} elseif ( ! empty( $definition ) && ! $count_defined && ! $get_defined ) {
						$args = $definition;
					} else {
						$args = array();
					}

					foreach ( $args as $index => $arg ) {

						if ( ! is_string( $arg ) ) {
							continue;
						}

						$args[ $index ] = preg_replace_callback(
							'/\$fixture_ids\[([a-z_]+)\]\[(\d+)\]/'
							, array( $this, 'fixture_replace_callback' )
							, $arg
						);

						if ( ! $args[ $index ] ) {
							continue 2;
						}
					}

					$ids = $factory->create_many( $count, $args );

					if ( $get ) {
						self::$_fixtures[ $type ] = array_merge(
							self::$_fixtures[ $type ]
							, array_map(
								array( $factory, 'get_object_by_id' )
								, $ids
							)
						);
					}

					self::$_fixtures_ids[ $type ] = array_merge(
						self::$_fixtures_ids[ $type ]
						, $ids
					);

				} // End foreach ( $definitions ).

				$this->fixture_ids = self::$_fixtures_ids;
				$this->fixtures    = self::$_fixtures;

			} // End foreach ( $this->shared_fixtures ).

			$this->commit_transaction();

			self::$creating_fixtures = false;

		} // End if ( fixtures exist ) elseif ( fixtures to create ).

		parent::setUp();

		if ( ! empty( $this->backup_globals ) ) {
			foreach ( $this->backup_globals as $global ) {
				$this->backed_up_globals[ $global ] = isset( $GLOBALS[ $global ] )
					? $GLOBALS[ $global ]
					: null;
			}
		}

		// Back-compat, use $this->factory->wordpoints->points_log instead.
		$this->factory->wordpoints_points_log =
			new WordPoints_PHPUnit_Factory_For_Points_Log(
				$this->factory
			);

		// Back-compat, use $this->factory->wordpoints->rank instead.
		$this->factory->wordpoints_rank = new WordPoints_PHPUnit_Factory_For_Rank(
			$this->factory
		);

		add_filter( 'query', array( $this, 'do_not_alter_tables' ) );
	}

	/**
	 * @since 2.6.0
	 */
	public function tearDown() {

		parent::tearDown();

		if ( isset( self::$backup_app ) ) {
			WordPoints_App::$main = self::$backup_app;
			self::$backup_app     = null;
		}

		unset( $GLOBALS['current_screen'] );

		if ( ! empty( $this->backed_up_globals ) ) {
			foreach ( $this->backed_up_globals as $key => $value ) {
				$GLOBALS[ $key ] = $value;
			}
		}

		WordPoints_PHPUnit_Mock_Entity_Context::$current_id     = 1;
		WordPoints_PHPUnit_Mock_Entity_Context::$stack          = array();
		WordPoints_PHPUnit_Mock_Entity_Context::$current_ids    = array();
		WordPoints_PHPUnit_Mock_Entity_Context::$fail_switching = array();
	}

	//
	// Helpers.
	//

	/**
	 * Set the version of the plugin.
	 *
	 * @since 2.6.0
	 *
	 * @param string $version The version to set. Defaults to 1.0.0.
	 */
	protected function wordpoints_set_db_version( $version = '1.0.0' ) {

		$wordpoints_data            = wordpoints_get_maybe_network_option( 'wordpoints_data' );
		$wordpoints_data['version'] = $version;
		wordpoints_update_maybe_network_option( 'wordpoints_data', $wordpoints_data );

		if ( is_multisite() && ! is_wordpoints_network_active() ) {
			$wordpoints_data            = get_site_option( 'wordpoints_data' );
			$wordpoints_data['version'] = $version;
			update_site_option( 'wordpoints_data', $wordpoints_data );
		}
	}

	/**
	 * Get the version of the plugin.
	 *
	 * @since 2.6.0
	 *
	 * @return string The version of the plugin.
	 */
	protected function wordpoints_get_db_version() {

		$wordpoints_data = wordpoints_get_maybe_network_option( 'wordpoints_data' );

		return ( isset( $wordpoints_data['version'] ) )
			? $wordpoints_data['version']
			: '';
	}

	/**
	 * Set the version of a component.
	 *
	 * @since 2.6.0
	 *
	 * @param string $component The slug of the component.
	 * @param string $version   The version to set. Defaults to 1.0.0.
	 */
	protected function set_component_db_version( $component, $version = '1.0.0' ) {

		$wordpoints_data = wordpoints_get_maybe_network_option( 'wordpoints_data' );
		$wordpoints_data['components'][ $component ]['version'] = $version;
		wordpoints_update_maybe_network_option( 'wordpoints_data', $wordpoints_data );

		if ( is_multisite() && ! is_wordpoints_network_active() ) {
			$wordpoints_data = get_site_option( 'wordpoints_data' );
			$wordpoints_data['components'][ $component ]['version'] = $version;
			update_site_option( 'wordpoints_data', $wordpoints_data );
		}
	}

	/**
	 * Get the version of a component.
	 *
	 * @since 2.6.0
	 *
	 * @param string $component The slug of the component.
	 *
	 * @return string The version of the points component.
	 */
	protected function get_component_db_version( $component ) {

		$wordpoints_data = wordpoints_get_maybe_network_option( 'wordpoints_data' );

		return ( isset( $wordpoints_data['components'][ $component ]['version'] ) )
			? $wordpoints_data['components'][ $component ]['version']
			: '';
	}

	/**
	 * Set the version of a module.
	 *
	 * @since 2.6.0
	 * @deprecated 2.7.0 Use set_extension_db_version() instead.
	 *
	 * @param string $module       The slug of the module.
	 * @param string $version      The version to set. Defaults to 1.0.0.
	 * @param bool   $network_wide Whether to set the network-wide version.
	 */
	protected function set_module_db_version( $module, $version = '1.0.0', $network_wide = false ) {
		$this->set_extension_db_version( $module, $version, $network_wide );
	}

	/**
	 * Set the version of a extension.
	 *
	 * @since 2.6.0 As set_module_db_version().
	 * @since 2.7.0
	 *
	 * @param string $extension    The slug of the extension.
	 * @param string $version      The version to set. Defaults to 1.0.0.
	 * @param bool   $network_wide Whether to set the network-wide version.
	 */
	protected function set_extension_db_version( $extension, $version = '1.0.0', $network_wide = false ) {

		if ( is_multisite() ) {
			$wordpoints_data = get_site_option( 'wordpoints_data' );
		}

		if ( ! $network_wide ) {
			$wordpoints_data = get_option( 'wordpoints_data' );
		}

		$wordpoints_data['modules'][ $extension ]['version'] = $version;

		if ( is_multisite() ) {
			update_site_option( 'wordpoints_data', $wordpoints_data );
		}

		if ( ! $network_wide ) {
			update_option( 'wordpoints_data', $wordpoints_data );
		}
	}

	/**
	 * Get the version of a module.
	 *
	 * @since 2.6.0
	 * @deprecated 2.7.0 Use get_extension_db_version() instead.
	 *
	 * @param string $module       The slug of the component.
	 * @param bool   $network_wide Whether to get the network-wide version.
	 *
	 * @return string The version of the points component.
	 */
	protected function get_module_db_version( $module, $network_wide = false ) {
		return $this->get_extension_db_version( $module, $network_wide );
	}

	/**
	 * Get the version of a extension.
	 *
	 * @since 2.6.0 As get_module_db_version().
	 * @since 2.7.0
	 *
	 * @param string $extension    The slug of the extension.
	 * @param bool   $network_wide Whether to get the network-wide version.
	 *
	 * @return string The version of the points component.
	 */
	protected function get_extension_db_version( $extension, $network_wide = false ) {

		if ( $network_wide ) {
			$wordpoints_data = get_site_option( 'wordpoints_data' );
		} else {
			$wordpoints_data = get_option( 'wordpoints_data' );
		}

		return ( isset( $wordpoints_data['modules'][ $extension ]['version'] ) )
			? $wordpoints_data['modules'][ $extension ]['version']
			: '';
	}

	/**
	 * Run an update for WordPoints.
	 *
	 * @since 2.6.0
	 *
	 * @param string $from The version to update from.
	 */
	protected function update_wordpoints( $from = null ) {

		if ( ! isset( $from ) ) {
			$from = $this->previous_version;
		}

		$this->wordpoints_set_db_version( $from );

		wordpoints_delete_maybe_network_option( 'wordpoints_installable_versions' );

		wordpoints_installables_maybe_update();
	}

	/**
	 * Run an update for a component.
	 *
	 * @since 2.6.0
	 *
	 * @param string $component The slug of the component to update.
	 * @param string $from      The version to update from.
	 */
	protected function update_component( $component = null, $from = null ) {

		if ( ! isset( $component ) ) {
			$component = $this->wordpoints_component;
		}

		if ( ! isset( $from ) ) {
			$from = $this->previous_version;
		}

		$this->set_component_db_version( $component, $from );

		// Make sure that the component is marked as active in the database.
		wordpoints_update_maybe_network_option(
			'wordpoints_active_components'
			, array( $component => 1 )
		);

		wordpoints_delete_maybe_network_option( 'wordpoints_installable_versions' );

		// Run the update.
		wordpoints_installables_maybe_update();
	}

	/**
	 * Run an update for a module.
	 *
	 * @since 2.6.0
	 * @deprecated 2.7.0 Use update_extension() instead.
	 *
	 * @param string $module The slug of the module to update.
	 * @param string $from   The version to update from.
	 */
	protected function update_module( $module = null, $from = null ) {

		if ( ! isset( $module ) ) {
			$module = $this->wordpoints_module;
		}

		$this->update_extension( $module, $from );
	}

	/**
	 * Run an update for a extension.
	 *
	 * @since 2.6.0 As update_module().
	 * @since 2.7.0
	 *
	 * @param string $extension The slug of the extension to update.
	 * @param string $from      The version to update from.
	 */
	protected function update_extension( $extension = null, $from = null ) {

		if ( ! isset( $extension ) ) {
			$extension = $this->wordpoints_extension;
		}

		if ( ! isset( $from ) ) {
			$from = $this->previous_version;
		}

		$this->set_extension_db_version( $extension, $from );

		// Make sure that the extension is marked as active in the database.
		wordpoints_update_maybe_network_option(
			'wordpoints_active_modules'
			, array( $extension => 1 )
		);

		wordpoints_delete_maybe_network_option( 'wordpoints_installable_versions' );

		// Run the update.
		// Back-compat with WordPoints < 2.4.0.
		if ( function_exists( 'wordpoints_installables_maybe_update' ) ) {
			wordpoints_installables_maybe_update();
		} else {
			WordPoints_Installables::maybe_do_updates();
		}
	}

	/**
	 * Create the points type used in the tests.
	 *
	 * @since 2.6.0
	 */
	protected function create_points_type() {

		$this->points_data = array(
			'name'   => 'Points',
			'prefix' => '$',
			'suffix' => 'pts.',
		);

		wordpoints_update_maybe_network_option(
			'wordpoints_points_types'
			, array( 'points' => $this->points_data )
		);
	}

	/**
	 * Alter temporary tables.
	 *
	 * @since 2.6.0
	 *
	 * @WordPoints\filter query Added by self::setUp().
	 */
	public function do_not_alter_tables( $query ) {

		if ( 'ALTER TABLE' === substr( trim( $query ), 0, 11 ) ) {
			$query = 'SELECT "Do not alter tables during tests!"';
		}

		return $query;
	}

	/**
	 * Create the tables for this component with a specific charset.
	 *
	 * @since 2.6.0
	 *
	 * @param string $charset The character set to create the tables with.
	 */
	protected function create_tables_with_charset( $charset ) {

		global $wpdb;

		$wpdb->query( 'ROLLBACK' );

		remove_filter( 'query', array( $this, '_create_temporary_tables' ) );
		remove_filter( 'query', array( $this, '_drop_temporary_tables' ) );
		remove_filter( 'query', array( $this, 'do_not_alter_tables' ) );

		// Remove the current tables.
		foreach ( $this->get_db_tables() as $table ) {
			$wpdb->query( "DROP TABLE `{$table}`" );
		}

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// Create the tables again with the specified charset.
		$schema = $this->get_db_schema();
		$schema = preg_replace( '/\).*;/', ") DEFAULT CHARSET={$charset};", $schema );
		dbDelta( $schema );

		$this->assertTablesHaveCharset( $charset );
	}

	/**
	 * Get the database tables created by this component.
	 *
	 * @since 2.6.0
	 *
	 * @return array The database tables of the component.
	 */
	public function get_db_tables() {

		if ( ! isset( $this->db_tables ) ) {
			preg_match_all( '/CREATE TABLE (.*) \(/', $this->get_db_schema(), $matches );
			$this->db_tables = $matches[1];
		}

		return $this->db_tables;
	}

	/**
	 * Get the database schema for this component.
	 *
	 * @since 2.6.0
	 *
	 * @return string The database schema defined by this component.
	 */
	public function get_db_schema() {

		if ( ! isset( $this->db_schema ) ) {

			global $wpdb;

			$installer = new WordPoints_Points_Installable( 'points' );
			$db_tables = $installer->get_db_tables();

			foreach ( $db_tables['global'] as $table_name => $table_schema ) {
				$this->db_schema .= "CREATE TABLE {$wpdb->base_prefix}{$table_name} (
					{$table_schema}
				);\n";
			}
		}

		return $this->db_schema;
	}

	/**
	 * Mock a filter function with an object.
	 *
	 * @since 2.6.0
	 *
	 * @param string $filter       The filter hook to attach to.
	 * @param mixed  $return_value The filtered value that should be returned.
	 *
	 * @return WordPoints_PHPUnit_Mock_Filter The mock filter.
	 */
	protected function mock_filter( $filter, $return_value = null ) {

		$mock = new WordPoints_PHPUnit_Mock_Filter( $return_value );

		add_filter( $filter, array( $mock, 'filter' ) );

		return $mock;
	}

	/**
	 * Listen for a WordPress action or filter.
	 *
	 * To limit the counting based on the filtered value, you can pass a
	 * $count_callback, which will be called with the value being filtered. The
	 * callback should return a boolean value, which will determine whether the
	 * filter call is counted.
	 *
	 * @since 2.6.0
	 *
	 * @param string   $filter         The filter to listen for.
	 * @param callable $count_callback Function to call to test if this filter call
	 *                                 should be counted.
	 *
	 * @return WordPoints_PHPUnit_Mock_Filter The mock filter.
	 */
	protected function listen_for_filter( $filter, $count_callback = null ) {

		$mock = $this->mock_filter( $filter );

		if ( isset( $count_callback ) ) {
			$mock->count_callback = $count_callback;
		}

		$this->watched_filters[ $filter ] = $mock;

		return $mock;
	}

	/**
	 * Get the number of times a filter was called.
	 *
	 * @since 2.6.0
	 *
	 * @param string $filter The filter to check for.
	 *
	 * @return int How many times this filter was called.
	 */
	protected function filter_was_called( $filter ) {

		return $this->watched_filters[ $filter ]->call_count;
	}

	/**
	 * Check if an SQL string is a points logs query.
	 *
	 * @since 2.6.0
	 *
	 * @param string $sql The SQL query string.
	 *
	 * @return bool Whether the query is a points logs query.
	 */
	public function is_points_logs_query( $sql ) {

		return strpos( $sql, "FROM `{$GLOBALS['wpdb']->wordpoints_points_logs}`" ) !== false;
	}

	/**
	 * Check if an SQL string is a top users query.
	 *
	 * @since 2.6.0
	 *
	 * @param string $sql The SQL query string.
	 *
	 * @return bool Whether the query is a points logs query.
	 */
	public function is_top_users_query( $sql ) {

		global $wpdb;

		if ( ! strpos( $sql, $wpdb->usermeta ) ) {
			return false;
		}

		return false !== strpos(
			$sql
			, '
					ORDER BY COALESCE(CONVERT(`meta`.`meta_value`, SIGNED INTEGER), 0) DESC, `ID` ASC
					LIMIT'
		);
	}

	/**
	 * Check if an SQL query is a Rank retrieval query.
	 *
	 * @since 2.6.0
	 *
	 * @param string $sql The SQL query string.
	 *
	 * @return bool Whether the query is a get rank query.
	 */
	public function is_wordpoints_get_rank_query( $sql ) {

		global $wpdb;

		return 0 === strpos(
			$sql
			, "
					SELECT id, name, type, rank_group, blog_id, site_id
					FROM {$wpdb->wordpoints_ranks}
					WHERE id = "
		);
	}

	/**
	 * Get the HTML for a widget instance.
	 *
	 * @since 2.6.0
	 *
	 * @param array $instance The settings for the widget instance.
	 * @param array $args     Other arguments for the widget display.
	 *
	 * @return string The HTML for this widget instance.
	 */
	protected function get_widget_html( array $instance = array(), array $args = array() ) {

		ob_start();
		the_widget( $this->widget_class, $instance, $args );
		return ob_get_clean();
	}

	/**
	 * Get the XPath query for a widget instance.
	 *
	 * @since 2.6.0
	 *
	 * @param array $instance The settings for the widget instance.
	 *
	 * @return DOMXPath XPath query object loaded with the widget's HTML.
	 */
	protected function get_widget_xpath( array $instance = array() ) {

		$widget = $this->get_widget_html( $instance );

		$document = new DOMDocument();
		$document->loadHTML( $widget );

		return new DOMXPath( $document );
	}

	/**
	 * Give the current user certain capabilities.
	 *
	 * @since 2.6.0
	 *
	 * @param string|string[] $caps The caps to give the user.
	 */
	protected function give_current_user_caps( $caps ) {

		/** @var WP_User $user */
		$user = $this->factory->user->create_and_get();

		foreach ( (array) $caps as $cap ) {
			$user->add_cap( $cap );
		}

		wp_set_current_user( $user->ID );
	}

	/**
	 * Begin mocking the filesystem.
	 *
	 * @since 2.6.0
	 */
	protected function mock_filesystem() {

		if ( ! class_exists( 'WP_Filesystem_Base' ) ) {

			/**
			 * WordPress's base filesystem API class.
			 *
			 * @since 2.6.0
			 */
			require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		}

		// Creating a new mock filesystem.
		$this->mock_fs = new WP_Mock_Filesystem();

		// Tell the WordPress filesystem API shim to use this mock filesystem.
		WP_Filesystem_Mock::set_mock( $this->mock_fs );

		// Tell the shim to start overriding whatever other filesystem access method
		// is in use.
		WP_Filesystem_Mock::start();

		if ( empty( $GLOBALS['wp_filesystem'] ) || ! ( $GLOBALS['wp_filesystem'] instanceof WP_Filesystem_Mock ) ) {
			WP_Filesystem();
		}
	}

	/**
	 * Set up the global apps object as a mock.
	 *
	 * @since 2.6.0
	 *
	 * @return WordPoints_App The mock app.
	 */
	public static function mock_apps() {

		self::$backup_app = WordPoints_App::$main;

		WordPoints_App::$main = new WordPoints_PHPUnit_Mock_App_Silent(
			'apps'
		);

		return WordPoints_App::$main;
	}

	/**
	 * Mock being in the network admin.
	 *
	 * @since 2.6.0
	 */
	public function set_network_admin() {
		$GLOBALS['current_screen'] = WP_Screen::get( 'test-network' );
	}

	/**
	 * `preg_replace()` callback that fills in fixture IDs.
	 *
	 * @since 2.6.0
	 *
	 * @param array $matches The matches on the fixture ID replacing pattern.
	 *
	 * @return string|false The ID of the fixture matched, or false.
	 */
	public function fixture_replace_callback( $matches ) {

		if ( 'site' === $matches[1] && ! is_multisite() ) {
			return false;
		}

		return self::$_fixtures_ids[ $matches[1] ][ $matches[2] ];
	}

	/**
	 * Throws an exception.
	 *
	 * Useful for hooking to an action or filter to short-circuit execution at that
	 * point.
	 *
	 * @since 2.6.0
	 *
	 * @throws WordPoints_PHPUnit_Exception An exception.
	 */
	public function throw_exception() {
		throw new WordPoints_PHPUnit_Exception();
	}

	/**
	 * Call a shortcode function by tag name.
	 *
	 * We can now avoid evil calls to do_shortcode( '[shortcode]' ).
	 *
	 * @since 2.6.0
	 *
	 * @param string $tag     The shortcode whose function to call.
	 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
	 * @param array  $content The shortcode's content. Default is null (none).
	 *
	 * @return string|false False on failure, the result of the shortcode on success.
	 */
	public function do_shortcode( $tag, array $atts = array(), $content = null ) {

		global $shortcode_tags;

		if ( ! isset( $shortcode_tags[ $tag ] ) ) {
			return false;
		}

		return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
	}

	/**
	 * Get an xpath query object for a shortcode's output.
	 *
	 * @since 2.7.0
	 *
	 * @param array $atts The shortcode attributes.
	 *
	 * @return DOMXPath The xpath query for the shortcode.
	 */
	public function get_shortcode_xpath( $atts ) {

		$shortcode = $this->do_shortcode( $this->shortcode, $atts );

		$document = new DOMDocument();
		$document->loadHTML( $shortcode );

		return new DOMXPath( $document );
	}

	/**
	 * Returns a test double for the specified class.
	 *
	 * @since 2.7.0
	 *
	 * @param string $original_class_name The name of the class to mock.
	 *
	 * @return PHPUnit_Framework_MockObject_MockObject The mock object.
	 */
	protected function createMock( $original_class_name ) {

		if ( is_callable( 'parent::createMock' ) ) {
			return parent::createMock( $original_class_name );
		}

		return $this->getMockBuilder( $original_class_name )
			->disableOriginalConstructor()
			->disableOriginalClone()
			->getMock();
	}

	/**
	 * Returns a partial test double for the specified class.
	 *
	 * @since 2.7.0
	 *
	 * @param string $original_class_name The class to mock.
	 * @param array  $methods             The methods to mock.
	 *
	 * @return PHPUnit_Framework_MockObject_MockObject The mock object.
	 */
	protected function createPartialMock( $original_class_name, array $methods ) {

		if ( is_callable( 'parent::createPartialMock' ) ) {
			return parent::createPartialMock( $original_class_name, $methods );
		}

		return $this->getMockBuilder( $original_class_name )
			->disableOriginalConstructor()
			->disableOriginalClone()
			->setMethods( empty( $methods ) ? null : $methods )
			->getMock();
	}

	/**
	 * Returns a partial test double for the specified abstract class.
	 *
	 * The specified methods will be mocked in addition to all of the class's
	 * abstract methods.
	 *
	 * @since 2.7.0
	 *
	 * @param string $original_class_name The abstract class to mock.
	 * @param array  $methods             The concrete methods to mock.
	 * @param array  $args                The args to construct the mock with.
	 *
	 * @return PHPUnit_Framework_MockObject_MockObject The mock object.
	 */
	protected function getPartialMockForAbstractClass(
		$original_class_name,
		array $methods,
		array $args = array()
	) {
		return $this->getMockForAbstractClass(
			$original_class_name
			, $args
			, ''
			, true
			, true
			, true
			, $methods
		);
	}

	//
	// Assertions.
	//

	/**
	 * Assert that a string is an error returned by one of the shortcodes.
	 *
	 * @since 2.6.0
	 *
	 * @param string $string The string that is expected to be a shortcode error.
	 */
	protected function assertWordPointsShortcodeError( $string ) {

		$document = new DOMDocument();
		$document->loadHTML( $string );
		$xpath = new DOMXPath( $document );
		$this->assertSame(
			1
			, $xpath->query( '//p[@class = "wordpoints-shortcode-error"]' )->length
		);
	}

	/**
	 * Assert that a string is not an error returned by one of the shortcodes.
	 *
	 * @since 2.7.0
	 *
	 * @param string $string The string that is expected to not be a shortcode error.
	 */
	protected function assertNotWordPointsShortcodeError( $string ) {

		$document = new DOMDocument();
		$document->loadHTML( $string );
		$xpath = new DOMXPath( $document );
		$this->assertSame(
			0
			, $xpath->query( '//p[@class = "wordpoints-shortcode-error"]' )->length
		);
	}

	/**
	 * Assert that a string is an error output by one of the widgets.
	 *
	 * @since 2.6.0
	 *
	 * @param string $string The string that is expected to be a widget error.
	 */
	protected function assertWordPointsWidgetError( $string ) {

		$document = new DOMDocument();
		$document->loadHTML( $string );
		$xpath = new DOMXPath( $document );
		$this->assertSame(
			1
			, $xpath->query( '//div[@class = "wordpoints-widget-error"]' )->length
		);
	}

	/**
	 * Assert that a string is not an error output by one of the widgets.
	 *
	 * @since 2.7.0
	 *
	 * @param string $string The string that is expected to not be a widget error.
	 */
	protected function assertNotWordPointsWidgetError( $string ) {

		$document = new DOMDocument();
		$document->loadHTML( $string );
		$xpath = new DOMXPath( $document );
		$this->assertSame(
			0
			, $xpath->query( '//div[@class = "wordpoints-widget-error"]' )->length
		);
	}

	/**
	 * Assert that a string is an admin notice.
	 *
	 * @since 2.6.0
	 *
	 * @param string $string The string that is expected to contain an admin notice.
	 * @param array  $args   {
	 *        Other arguments.
	 *
	 *        @type string $type        The type of notice to expect.
	 *        @type bool   $dismissible Whether the notice should be dismissible.
	 *        @type string $option      The option that should be deleted on dismiss.
	 * }
	 */
	protected function assertWordPointsAdminNotice( $string, $args = array() ) {

		$document = new DOMDocument();
		$document->loadHTML( $string );
		$xpath = new DOMXPath( $document );

		$messages = $xpath->query( '//div[contains(@class, "notice")]' );

		$this->assertSame( 1, $messages->length );

		$message = $messages->item( 0 );

		if ( isset( $args['type'] ) ) {

			$this->assertStringMatchesFormat(
				"%Snotice-{$args['type']}%S"
				, $message->attributes->getNamedItem( 'class' )->nodeValue
			);
		}

		if ( isset( $args['dismissible'] ) ) {

			$this->assertStringMatchesFormat(
				'%Sis-dismissible%S'
				, $message->attributes->getNamedItem( 'class' )->nodeValue
			);

			if ( isset( $args['option'] ) ) {

				$this->assertSame(
					$args['option']
					, $message->attributes->getNamedItem( 'data-option' )->nodeValue
				);
			}
		}
	}

	/**
	 * Assert that a string is not an admin notice.
	 *
	 * @since 2.6.0
	 *
	 * @param string $string The string expected to not contain an admin notice.
	 */
	protected function assertNotWordPointsAdminNotice( $string ) {

		$document = new DOMDocument();
		$document->loadHTML( $string );
		$xpath = new DOMXPath( $document );

		$messages = $xpath->query( '//div[contains(@class, "notice")]' );

		$this->assertSame( 0, $messages->length );
	}

	/**
	 * Assert that all of this component's database tables have a certain charset.
	 *
	 * @since 2.6.0
	 *
	 * @param string $charset The charset that the tables are expected to have.
	 */
	public function assertTablesHaveCharset( $charset ) {

		foreach ( $this->get_db_tables() as $table ) {
			$this->assertTableHasCharset( $charset, $table );
		}
	}

	/**
	 * Assert that a database table has a certain charset.
	 *
	 * @since 2.6.0
	 *
	 * @param string $charset The charset the table is expected to have.
	 * @param string $table   The table name.
	 */
	public function assertTableHasCharset( $charset, $table ) {

		global $wpdb;

		// We append a space followed by another character to the strings so that we
		// can properly handle cases with and without a collation specified, and
		// without utf8 matching utf8mb4, for example.
		$this->assertStringMatchesFormat(
			"%aDEFAULT CHARSET={$charset} %a"
			, $wpdb->get_var( "SHOW CREATE TABLE `{$table}`", 1 ) . ' .'
		);
	}

	/**
	 * Asserts that a table has a column.
	 *
	 * @since 2.7.0
	 *
	 * @param string $column The field name.
	 * @param string $table  The database table name.
	 */
	protected function assertTableHasColumn( $column, $table ) {

		global $wpdb;

		$table_fields = $wpdb->get_results( "DESCRIBE {$table}" );

		$this->assertCount( 1, wp_list_filter( $table_fields, array( 'Field' => $column ) ) );
	}

	/**
	 * Asserts that a table doesn't have a column.
	 *
	 * @since 2.7.0
	 *
	 * @param string $column The field name.
	 * @param string $table  The database table name.
	 */
	protected function assertTableHasNotColumn( $column, $table ) {

		global $wpdb;

		$table_fields = $wpdb->get_results( "DESCRIBE {$table}" );

		$this->assertCount( 0, wp_list_filter( $table_fields, array( 'Field' => $column ) ) );
	}

	/**
	 * Assert that a value is a hook reaction.
	 *
	 * @since 2.6.0
	 *
	 * @param mixed $reaction The reaction.
	 */
	public function assertIsReaction( $reaction ) {

		if ( $reaction instanceof WP_Error ) {
			$reaction = $reaction->get_error_data();
		}

		if ( $reaction instanceof WordPoints_Hook_Reaction_Validator ) {

			$message = '';

			foreach ( $reaction->get_errors() as $error ) {
				$message .= PHP_EOL . 'Field: ' . implode( '.', $error['field'] );
				$message .= PHP_EOL . 'Error: ' . $error['message'];
			}

			$this->fail( $message );
		}

		$this->assertInstanceOf( 'WordPoints_Hook_ReactionI', $reaction );
	}

	/**
	 * Assert that a database table exists.
	 *
	 * @since 2.6.0
	 *
	 * @param string $table_name The name of the table to assert exists.
	 */
	public function assertDBTableExists( $table_name ) {

		global $wpdb;

		$this->assertSame(
			$table_name
			, $wpdb->get_var(
				$wpdb->prepare(
					'SHOW TABLES LIKE %s'
					, $wpdb->esc_like( $table_name )
				)
			)
		);
	}

	/**
	 * Asserts that two objects have identical properties.
	 *
	 * This differs from the behavior of assertEquals() in that strict comparison is
	 * used.
	 *
	 * @since 2.6.0
	 *
	 * @param object $object   The object with the expected properties.
	 * @param object $object_2 The object that should have identical properties.
	 */
	public function assertSameProperties( $object, $object_2 ) {

		$this->assertInternalType( 'object', $object );
		$this->assertInternalType( 'object', $object_2 );

		// If it is the exact same instance, no need for further checks.
		if ( $object === $object_2 ) {
			return;
		}

		$this->assertSame( get_class( $object ), get_class( $object_2 ) );

		$this->assertSameSetsWithIndex(
			get_object_vars( $object )
			, get_object_vars( $object_2 )
		);
	}

	/**
	 * Asserts that two indexed arrays have identical values and indexes.
	 *
	 * This differs from the behavior of assertEqualSets() in that strict comparison
	 * is used. It differs from assertSame() in that ordering does not matter.
	 *
	 * @since 2.6.0
	 *
	 * @param array $array   The array with the expected elements.
	 * @param array $array_2 The array that should have identical elements.
	 */
	public function assertSameSetsWithIndex( $array, $array_2 ) {

		$this->assertInternalType( 'array', $array );
		$this->assertInternalType( 'array', $array_2 );

		ksort( $array );
		ksort( $array_2 );

		$this->assertSame( $array, $array_2 );
	}

	/**
	 * Asserts that an array contains a value.
	 *
	 * This differs from the behavior of assertContains() in that strict comparison
	 * is used.
	 *
	 * @since 2.6.0
	 *
	 * @param mixed $value The value that an array is expected to contain.
	 * @param array $array The array that should contain an identical value.
	 */
	public function assertContainsSame( $value, $array ) {

		$this->assertInternalType( 'array', $array );

		$this->assertTrue( in_array( $value, $array, true ) );
	}

	/**
	 * Asserts that a string contains another string.
	 *
	 * This is basically just a wrapper for a subset of the behavior of
	 * assertContains().
	 *
	 * @since 2.6.0
	 *
	 * @param string $needle      The string that the string is expected to contain.
	 * @param string $haystack    The string expected to contain the string.
	 * @param bool   $ignore_case Whether to ignore the case of the strings.
	 */
	public function assertStringContains( $needle, $haystack, $ignore_case = false ) {

		$this->assertInternalType( 'string', $needle );
		$this->assertInternalType( 'string', $haystack );

		$constraint = new PHPUnit_Framework_Constraint_StringContains(
			$needle,
			$ignore_case
		);

		self::assertThat( $haystack, $constraint );
	}

	/**
	 * Create a points reaction.
	 *
	 * @since 2.6.0
	 *
	 * @param array $settings The settings for this reaction.
	 *
	 * @return false|WordPoints_Hook_Reaction_Validator|WordPoints_Hook_ReactionI
	 *         The reaction, or false on failure.
	 */
	public function create_points_reaction( array $settings = array() ) {

		$settings = array_merge(
			array(
				'event'       => 'user_register',
				'target'      => array( 'user' ),
				'reactor'     => 'points',
				'points'      => 100,
				'points_type' => 'points',
				'log_text'    => 'Test log text.',
				'description' => 'Test description.',
				'reversals'   => array( 'toggle_off' => 'toggle_on' ),
			)
			, $settings
		);

		$store = wordpoints_hooks()->get_reaction_store( 'points' );

		return $store->create_reaction( $settings );
	}
}

// EOF
