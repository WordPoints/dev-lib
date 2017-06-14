<?php

/**
 * Class for mocking an un/installer object.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock un/installer.
 *
 * Allows access to all protected methods and properties.
 *
 * @since 2.6.0
 *
 * @property $type
 * @property $slug
 * @property $version
 * @property $action
 * @property $context
 * @property $network_wide
 * @property $custom_caps_getter
 * @property $custom_caps
 * @property $custom_caps_keys
 * @property $schema
 * @property $updates
 * @property $uninstall
 * @property $updating_from
 *
 * @method get_db_version()
 * @method maybe_load_custom_caps()
 * @method before_install()
 * @method install_db_schema()
 * @method before_update()
 * @method install_custom_caps()
 * @method before_uninstall()
 * @method prepare_uninstall_list_tables()
 * @method prepare_uninstall_non_per_site_items( $items_key )
 * @method map_uninstall_shortcut( $shortcut, $canonical, $args )
 * @method map_shortcuts( $type )
 * @method uninstall_custom_caps( $caps )
 * @method uninstall_( $type )
 * @method uninstall_metadata( $type, $key )
 * @method uninstall_meta_boxes( $screen_id, $args )
 * @method uninstall_list_table( $screen_id, $args )
 * @method uninstall_option( $option )
 * @method uninstall_transient( $transient )
 * @method uninstall_widget( $id_base )
 * @method uninstall_points_hook( $id_base )
 * @method uninstall_table( $table )
 * @method set_db_version( $version = null )
 * @method uninstall_single()
 * @method uninstall_site()
 * @method uninstall_network()
 * @method do_per_site_install()
 * @method get_all_site_ids()
 * @method set_network_installed()
 * @method is_network_installed()
 * @method unset_network_installed()
 * @method unset_network_install_skipped()
 * @method unset_network_update_skipped()
 * @method do_per_site_uninstall()
 * @method do_per_site_update()
 * @method get_installed_site_ids()
 * @method add_installed_site_id( $id = null )
 * @method delete_installed_site_ids()
 * @method validate_site_ids( $site_ids )
 * @method unset_db_version()
 * @method set_component_version( $component, $version )
 */
class WordPoints_PHPUnit_Mock_Un_Installer extends WordPoints_Un_Installer_Base {

	/**
	 * Manually set whether to skip per-site install or not.
	 *
	 * @since 2.6.0
	 *
	 * @var bool
	 */
	public $skip_per_site_install;

	/**
	 * The calls to inaccessible methods.
	 *
	 * @since 2.6.0
	 *
	 * @var array[]
	 */
	public $method_calls = array();

	/**
	 * @since 2.6.0
	 */
	public function &__get( $var ) {
		return $this->$var;
	}

	/**
	 * @since 2.6.0
	 */
	public function __set( $var, $value ) {
		$this->$var = $value;
	}

	/**
	 * @since 2.6.0
	 */
	public function __isset( $var ) {
		return isset( $this->$var );
	}

	/**
	 * @since 2.6.0
	 */
	public function __unset( $var ) {
		unset( $this->$var );
	}

	/**
	 * @since 2.6.0
	 */
	public function __call( $method, $args ) {

		$this->method_calls[] = array( 'method' => $method, 'args' => $args );

		return call_user_func_array( array( $this, $method ), $args );
	}

	/**
	 * @since 2.6.0
	 */
	public function skip_per_site_install() {

		if ( isset( $this->skip_per_site_install ) ) {
			return $this->skip_per_site_install;
		}

		return parent::skip_per_site_install();
	}

	/**
	 * @since 2.6.0
	 */
	public function install_network() {

		$this->method_calls[] = array( 'method' => __FUNCTION__, 'args' => array() );

		parent::install_network();
	}

	/**
	 * @since 2.6.0
	 */
	public function install_site() {

		$this->method_calls[] = array( 'method' => __FUNCTION__, 'args' => array() );

		parent::install_site();
	}

	/**
	 * @since 2.6.0
	 */
	public function install_single() {

		$this->method_calls[] = array( 'method' => __FUNCTION__, 'args' => array() );

		parent::install_single();
	}

	/**
	 * @since 2.6.0
	 */
	public function set_network_install_skipped() {

		$this->method_calls[] = array( 'method' => __FUNCTION__, 'args' => array() );

		parent::set_network_install_skipped();
	}

	/**
	 * @since 2.6.0
	 */
	public function set_network_update_skipped() {

		$this->method_calls[] = array( 'method' => __FUNCTION__, 'args' => array() );

		parent::set_network_update_skipped();
	}

	/**
	 * @since 2.6.0
	 */
	public function update_network_to_1_0_0() {
		$this->method_calls[] = array( 'method' => __FUNCTION__, 'args' => array() );
	}

	/**
	 * @since 2.6.0
	 */
	public function update_site_to_1_0_0() {
		$this->method_calls[] = array( 'method' => __FUNCTION__, 'args' => array() );
	}

	/**
	 * @since 2.6.0
	 */
	public function update_single_to_1_0_0() {
		$this->method_calls[] = array( 'method' => __FUNCTION__, 'args' => array() );
	}

	/**
	 * @since 2.7.0
	 */
	public function update_single_to_0_9_0() {
		$this->method_calls[] = array( 'method' => __FUNCTION__, 'args' => array() );
	}
}

// EOF
