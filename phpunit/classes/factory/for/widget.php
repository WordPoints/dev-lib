<?php

/**
 * A widget factory for use in the unit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.7.0
 */

/**
 * Factory for widgets.
 *
 * @since 2.7.0
 *
 * @method WP_Widget create( $args = array(), $generation_definitions = null )
 * @method WP_Widget create_and_get( $args = array(), $generation_definitions = null )
 * @method WP_Widget[] create_many( $count, $args = array(), $generation_definitions = null )
 */
class WordPoints_PHPUnit_Factory_For_Widget extends WP_UnitTest_Factory_For_Thing {

	/**
	 * @since 2.7.0
	 */
	public function __construct( $factory = null ) {

		parent::__construct( $factory );

		$this->default_generation_definitions = array(
			'title'   => new WP_UnitTest_Generator_Sequence( 'Widget title %s' ),
			'text'    => new WP_UnitTest_Generator_Sequence( 'Widget text %s' ),
			'id_base' => 'text',
		);
	}

	/**
	 * Create a widget.
	 *
	 * @since 2.7.0
	 *
	 * @param array $args {
	 *        Optional arguments to use.
	 *
	 *        @type string $id_base    The ID base of the widget.
	 *        @type string $sidebar_id The ID of the sidebar.
	 * }
	 *
	 * @return WP_Widget|false The widget handler, or false on failure.
	 */
	public function create_object( $args ) {

		global $wp_registered_widget_updates;

		static $multi_number = 0;
		$multi_number++;

		$sidebars = wp_get_sidebars_widgets();

		if ( isset( $args['sidebar_id'] ) ) {

			$sidebar_id = $args['sidebar_id'];

			$sidebar = ( isset( $sidebars[ $sidebar_id ] ) )
				? $sidebars[ $sidebar_id ]
				: array();

			unset( $args['sidebar_id'] );

		} else {

			$sidebar_id = key( $sidebars );
			$sidebar    = array_shift( $sidebars );
		}

		$id_base = $args['id_base'];

		unset( $args['id_base'] );

		$settings = $args;

		$sidebar[] = $id_base . '-' . $multi_number;

		$_POST['sidebar'] = $sidebar_id;
		$_POST[ "widget-{$id_base}" ] = array( $multi_number => $settings );
		$_POST['widget-id'] = $sidebar;

		if (
			! isset( $wp_registered_widget_updates[ $id_base ] )
			|| ! is_callable( $wp_registered_widget_updates[ $id_base ]['callback'] )
		) {

			return false;
		}

		$control = $wp_registered_widget_updates[ $id_base ];

		$result = call_user_func_array( $control['callback'], $control['params'] );

		if ( false === $result ) {
			return false;
		}

		/** @var WP_Widget $handler */
		$handler = $control['callback'][0];
		$handler->updated = false;

		return $handler;
	}

	/**
	 * Update a widget.
	 *
	 * @since 2.7.0
	 *
	 * @param WP_Widget $handler The handler for the widget.
	 * @param array     $fields  The fields to update.
	 *
	 * @return bool Whether the widget was updated successfully.
	 */
	public function update_object( $handler, $fields ) {

		$_POST[ 'widget-' . $handler->id_base ] = array( $handler->number => $fields );

		$handler->update_callback();
		$handler->updated = false;

		return true;
	}

	/**
	 * Get a widget by ID.
	 *
	 * @since 2.7.0
	 *
	 * @param WP_Widget $id The widget object.
	 *
	 * @return WP_Widget The widget object.
	 */
	public function get_object_by_id( $id ) {

		return $id;
	}
}

// EOF
