<?php

/**
 * Factory class for use in the unit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * A registry for factories to be used in the unit tests.
 *
 * @since 2.6.0
 *
 * @property-read WordPoints_PHPUnit_Factory_For_Entity $entity
 * @property-read WordPoints_PHPUnit_Factory_For_Entity_Context $entity_context
 * @property-read WordPoints_PHPUnit_Factory_For_Hook_Action $hook_action
 * @property-read WordPoints_PHPUnit_Factory_For_Hook_Condition $hook_condition
 * @property-read WordPoints_PHPUnit_Factory_For_Hook_Event $hook_event
 * @property-read WordPoints_PHPUnit_Factory_For_Hook_Extension $hook_extension
 * @property-read WordPoints_PHPUnit_Factory_For_Hook_Reaction $hook_reaction
 * @property-read WordPoints_PHPUnit_Factory_For_Hook_Reaction_Store $hook_reaction_store
 * @property-read WordPoints_PHPUnit_Factory_For_Hook_Reactor $hook_reactor
 * @property-read WordPoints_PHPUnit_Factory_For_Points_Log $points_log
 * @property-read WordPoints_PHPUnit_Factory_For_Points_Type $points_type
 * @property-read WordPoints_PHPUnit_Factory_For_Post_Type $post_type
 * @property-read WordPoints_PHPUnit_Factory_For_Rank $rank
 * @property-read WordPoints_PHPUnit_Factory_For_User_Role $user_role
 * @property-read WordPoints_PHPUnit_Factory_For_Widget $widget
 */
class WordPoints_PHPUnit_Factory {

	/**
	 * The registered classes, indexed by slug.
	 *
	 * @since 2.6.0
	 *
	 * @var string[]
	 */
	protected $classes = array();

	/**
	 * The factory registry.
	 *
	 * @since 2.6.0
	 *
	 * @var WordPoints_PHPUnit_Factory
	 */
	public static $factory;

	/**
	 * Initialize the registry.
	 *
	 * @since 2.6.0
	 *
	 * @return WordPoints_PHPUnit_Factory The factory registry.
	 */
	public static function init() {

		self::$factory = new WordPoints_PHPUnit_Factory();

		$factory = self::$factory;
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
		$factory->register( 'widget', 'WordPoints_PHPUnit_Factory_For_Widget' );

		return self::$factory;
	}

	/**
	 * @since 2.6.0
	 */
	public function __get( $var ) {

		if ( $this->is_registered( $var ) && isset( $this->classes[ $var ] ) ) {
			$this->$var = new $this->classes[ $var ]( $this );
			return $this->$var;
		}

		return null;
	}

	/**
	 * Register a factory.
	 *
	 * @since 2.6.0
	 *
	 * @param string $slug  The factory slug.
	 * @param string $class The factory class.
	 */
	public function register( $slug, $class ) {

		$this->classes[ $slug ] = $class;
	}

	/**
	 * Deregister a factory.
	 *
	 * @since 2.6.0
	 *
	 * @param string $slug The factory slug.
	 */
	public function deregister( $slug ) {

		unset( $this->classes[ $slug ], $this->$slug );
	}

	/**
	 * Check if a factory is registered.
	 *
	 * @since 2.6.0
	 *
	 * @param string $slug The factory slug.
	 *
	 * @return bool Whether the factory is registered.
	 */
	public function is_registered( $slug ) {

		return isset( $this->classes[ $slug ] );
	}
}

// EOF
