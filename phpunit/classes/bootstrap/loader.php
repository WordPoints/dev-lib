<?php

/**
 * WordPoints loader class for the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Loads plugins, modules, and WordPoints components, for the PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Bootstrap_Loader extends WPPPB_Loader {

	/**
	 * List of functions to call indexed by action slug.
	 *
	 * @since 2.6.0
	 *
	 * @var callable[]
	 */
	protected $actions;

	/**
	 * List of modules to be installed.
	 *
	 * @since 2.6.0
	 *
	 * @var array[]
	 */
	protected $modules = array();

	/**
	 * List of components to be installed.
	 *
	 * @since 2.6.0
	 *
	 * @var array[]
	 */
	protected $components = array();

	/**
	 * The main instance of the loader.
	 *
	 * @since 2.6.0
	 *
	 * @var WordPoints_PHPUnit_Bootstrap_Loader
	 */
	protected static $instance;

	/**
	 * Get the main instance of the loader.
	 *
	 * @since 2.6.0
	 *
	 * @return WordPoints_PHPUnit_Bootstrap_Loader The main instance.
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new WordPoints_PHPUnit_Bootstrap_Loader;
			parent::$instance = self::$instance;
		}

		return self::$instance;
	}

	//
	// Public methods.
	//

	/**
	 * @since 2.6.0
	 */
	public function __construct() {

		parent::__construct();

		$this->add_action(
			'after_load_wordpress'
			, array( $this, 'init_wordpoints_factory' )
		);

		$this->add_action(
			'after_load_wordpress'
			, array( $this, 'throw_errors_for_database_errors' )
		);

		$this->add_action(
			'after_load_wordpress'
			, array( $this, 'clean_database' )
		);
	}

	/**
	 * Hook a function to a custom action.
	 *
	 * These aren't related to the WordPress actions, but are a similar concept.
	 *
	 * @since 2.6.0
	 *
	 * @param string   $action   The action to hook the function to
	 * @param callable $function The function to hook to this action.
	 */
	public function add_action( $action, $function ) {
		$this->actions[ $action ][] = $function;
	}

	/**
	 * Calls all of the functions hooked to an action.
	 *
	 * @since 2.6.0
	 *
	 * @param string $action The action to fire.
	 */
	public function do_action( $action ) {

		if ( ! isset( $this->actions[ $action ] ) ) {
			return;
		}

		foreach ( $this->actions[ $action ] as $function ) {
			call_user_func( $function );
		}
	}

	/**
	 * Add a module to load.
	 *
	 * @since 2.6.0
	 *
	 * @param string $module       The basename slug of the module. Example:
	 *                             'module/module.php'.
	 * @param bool   $network_wide Whether to activate the module network-wide.
	 */
	public function add_module( $module, $network_wide = false ) {
		$this->modules[ $module ] = array( 'network_wide' => $network_wide );
	}

	/**
	 * Get a list of the modules to be loaded.
	 *
	 * @since 2.6.0
	 *
	 * @return array[] The modules to be loaded. Keys are module basename slugs,
	 *                 values arrays of data for the modules.
	 */
	public function get_modules() {
		return $this->modules;
	}

	/**
	 * Add a component to load.
	 *
	 * @since 2.6.0
	 *
	 * @param string $slug The slug of the component.
	 */
	public function add_component( $slug ) {
		$this->components[ $slug ] = array();
	}

	/**
	 * Get a list of the components to be loaded.
	 *
	 * @since 0.1.0
	 *
	 * @return array[] The components to be loaded. Keys are component slugs,
	 *                 values arrays of data for the components.
	 */
	public function get_components() {
		return $this->components;
	}

	/**
	 * @since 2.6.0
	 */
	public function install_plugins() {

		// Initially, we don't want to install the modules during the uninstall tests
		// so that they won't be loaded. However, they do need to be installed
		// remotely later, after the tests have begun.
		if (
			getenv( 'WORDPOINTS_ONLY_UNINSTALL_MODULE' )
			&& class_exists( 'WordPoints_PHPUnit_TestCase_Module_Uninstall', false )
		) {
			$this->install_modules();
			return;
		}

		if ( ! empty( $this->components ) ) {
			$this->add_php_file(
				dirname( __FILE__ ) . '/../../includes/install-components.php'
				, 'after'
				, $this->components
			);
		}

		if ( $this->should_install_modules() ) {
			if ( ! empty( $this->modules ) ) {
				$this->add_php_file(
					dirname( __FILE__ ) . '/../../includes/install-modules.php'
					, 'after'
					, $this->modules
				);
			}
		}

		parent::install_plugins();
	}

	/**
	 * Installs the modules via a separate PHP process.
	 *
	 * @since 2.6.0
	 */
	protected function install_modules() {

		system(
			WP_PHP_BINARY
			. ' ' . escapeshellarg( dirname( __FILE__ ) . '/../../includes/install-modules-only.php' )
			. ' ' . escapeshellarg( wp_json_encode( $this->modules ) )
			. ' ' . escapeshellarg( $this->locate_wp_tests_config() )
			. ' ' . (int) is_multisite()
			. ' ' . escapeshellarg( wp_json_encode( $this->files ) )
			, $exit_code
		);

		if ( 0 !== $exit_code ) {
			echo( 'Remote module installation failed with exit code ' . $exit_code );
			exit( 1 );
		}

		// The caching functions may not be loaded yet.
		if ( function_exists( 'wp_cache_flush' ) ) {
			wp_cache_flush();
		}
	}

	/**
	 * @since 2.6.0
	 */
	public function should_install_plugins() {

		if (
			function_exists( 'running_wordpoints_module_uninstall_tests' )
			&& running_wordpoints_module_uninstall_tests()
		) {
			return false;
		}

		if ( $this->running_module_uninstall_tests() ) {
			return (bool) getenv( 'WORDPOINTS_ONLY_UNINSTALL_MODULE' );
		}

		return parent::should_install_plugins();
	}

	/**
	 * Checks if the modules should be installed.
	 *
	 * @since 2.6.0
	 *
	 * @return bool Whether the modules should be installed.
	 */
	public function should_install_modules() {

		// Initially, we don't want to install the modules during the uninstall tests
		// so that they won't be loaded. However, they do need to be installed
		// remotely later, after the tests have begun.
		return (
			! $this->running_module_uninstall_tests()
			|| class_exists( 'WordPoints_PHPUnit_TestCase_Module_Uninstall', false )
		);
	}

	/**
	 * @since 2.6.0
	 */
	public function running_uninstall_tests() {

		if ( ! defined( 'RUNNING_WORDPOINTS_MODULE_TESTS' ) ) {
			return parent::running_uninstall_tests();
		}

		return $this->running_module_uninstall_tests();
	}

	/**
	 * Checks whether the module uninstall tests are running.
	 *
	 * @since 2.6.0
	 *
	 * @return bool Whether the module uninstall tests are running.
	 */
	public function running_module_uninstall_tests() {

		if ( ! defined( 'RUNNING_WORDPOINTS_MODULE_TESTS' ) ) {
			return false;
		}

		static $uninstall_tests;

		if ( ! isset( $uninstall_tests ) ) {

			ob_start();
			$uninstall_tests = parent::running_uninstall_tests();
			ob_end_clean();

			if ( ! $uninstall_tests ) {
				echo 'Not running module install/uninstall tests... To execute these, use -c phpunit.uninstall.xml.dist.' . PHP_EOL;
			} else {
				echo 'Running module install/uninstall tests...' . PHP_EOL;
			}
		}

		return $uninstall_tests;
	}

	/**
	 * Loads WordPress and its test environment.
	 *
	 * @since 2.6.0
	 */
	public function load_wordpress() {

		$this->do_action( 'before_load_wordpress' );

		parent::load_wordpress();

		$this->do_action( 'after_load_wordpress' );
	}

	/**
	 * Initialize the WordPoints PHPUnit factory.
	 *
	 * @since 2.6.0
	 */
	public function init_wordpoints_factory() {

		$factory = WordPoints_PHPUnit_Factory::init();
		$factory->register( 'entity', 'WordPoints_PHPUnit_Factory_For_Entity' );
		$factory->register( 'entity_context', 'WordPoints_PHPUnit_Factory_For_Entity_Context' );
		$factory->register( 'hook_reaction', 'WordPoints_PHPUnit_Factory_For_Hook_Reaction' );
		$factory->register( 'hook_reaction_store', 'WordPoints_PHPUnit_Factory_For_Hook_Reaction_Store' );
		$factory->register( 'hook_reactor', 'WordPoints_PHPUnit_Factory_For_Hook_Reactor' );
		$factory->register( 'hook_extension', 'WordPoints_PHPUnit_Factory_For_Hook_Extension' );
		$factory->register( 'hook_event', 'WordPoints_PHPUnit_Factory_For_Hook_Event' );
		$factory->register( 'hook_action', 'WordPoints_PHPUnit_Factory_For_Hook_Action' );
		$factory->register( 'hook_condition', 'WordPoints_PHPUnit_Factory_For_Hook_Condition' );
		$factory->register( 'points_log', 'WordPoints_PHPUnit_Factory_For_Points_Log' );
		$factory->register( 'points_type', 'WordPoints_PHPUnit_Factory_For_Points_Type' );
		$factory->register( 'post_type', 'WordPoints_PHPUnit_Factory_For_Post_Type' );
		$factory->register( 'rank', 'WordPoints_PHPUnit_Factory_For_Rank' );
		$factory->register( 'user_role', 'WordPoints_PHPUnit_Factory_For_User_Role' );

		$this->do_action( 'init_wordpoints_factory' );
	}

	/**
	 * Causes database errors to be converted into actual PHP errors.
	 *
	 * @since 2.6.0
	 */
	public function throw_errors_for_database_errors() {

		global $EZSQL_ERROR;

		$EZSQL_ERROR = new WordPoints_PHPUnit_Error_Handler_Database();
	}

	/**
	 * Remove cruft from the database that will interfere with the tests.
	 *
	 * @since 2.6.0
	 */
	public function clean_database() {
		delete_site_transient( 'wordpoints_all_site_ids' );
	}
}

// EOF
