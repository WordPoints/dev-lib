<?php

/**
 * Class for mocking a breaking updater object.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock breaking updater.
 *
 * Allows access to all protected methods and properties.
 *
 * @since 2.6.0
 *
 * @property $context
 * @property $network_wide
 * @property $checked_modules
 *
 * @method is_network_installed()
 * @method before_update()
 * @method after_update()
 * @method maintenance_mode( $enable = true )
 * @method deactivate_modules( $modules )
 * @method check_module( $module )
 * @method check_modules( $modules )
 * @method update_network_to_breaking()
 * @method update_site_to_breaking()
 * @method update_single_to_breaking()
 */
class WordPoints_PHPUnit_Mock_Breaking_Updater extends WordPoints_Breaking_Updater {

	/**
	 * The calls to inaccessible methods.
	 *
	 * @since 2.6.0
	 *
	 * @var array[]
	 */
	protected $method_calls = array();

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
}

// EOF
