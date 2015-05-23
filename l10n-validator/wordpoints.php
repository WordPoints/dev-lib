<?php

/**
 * Ignores of WordPoints plugin things.
 *
 * @package WordPoints_Dev_Lib\L10n-Validator
 * @since 1.1.0
 */

WP_L10n_Validator::register_config_callback( function( $parser ) {

	$parser->add_ignored_functions(
		array(
			// Functions.
			'wordpoints_add_network_option' => true,
			'wordpoints_add_points' => true,
			'wordpoints_add_rank' => array( 2, 3, 4 ),
			'wordpoints_alter_points' => true,
			'wordpoints_debug_message' => true,
			'wordpoints_dir_include' => true,
			'wordpoints_display_points' => true,
			'wordpoints_enqueue_datatables' => array( 1 ),
			'wordpoints_format_points' => true,
			'wordpoints_get_array_option' => true,
			'wordpoints_get_excluded_users' => true,
			'wordpoints_get_formatted_points' => true,
			'wordpoints_get_formatted_user_rank' => array( 1, 2, 3 ),
			'wordpoints_get_points_logs_query' => true,
			'wordpoints_get_points_logs_query_args' => true,
			'wordpoints_get_points_type_setting' => true,
			'wordpoints_list_post_types' => true,
			'wordpoints_load_module_textdomain' => true,
			'wordpoints_modules_url' => true,
			'wordpoints_points_show_top_users' => array( 3 ),
			'wordpoints_prepare__in' => true,
			'wordpoints_register_points_logs_query' => true,
			'wordpoints_show_admin_message' => array( 2 ),
			'wordpoints_show_points_logs_query' => true,
			'wordpoints_subtract_points' => true,
			'wordpoints_verify_nonce' => true,

			// Class methods.
			'WordPoints_Installables::get_installer' => true,
			'WordPoints_Installables::install' => true,
			'WordPoints_Installables::register' => true,
			'WordPoints_Installables::uninstall' => true,
			'WordPoints_Points_Hook::_set' => true,
			'WordPoints_Points_Hook::get_description' => true,
			'WordPoints_Points_Hook::get_field_id' => true,
			'WordPoints_Points_Hook::get_field_name' => true,
			'WordPoints_Points_Hook::get_instances' => true,
			'WordPoints_Points_Hook::the_field_id' => true,
			'WordPoints_Points_Hook::the_field_name' => true,
			'WordPoints_Points_Hooks::get_handler_by_id_base' => true,
			'WordPoints_Points_Hooks::register' => true,
			'WordPoints_Points_Hooks::points_type_form' => true,
			'WordPoints_Points_Log_Queries::get_query_data' => true,
			'WordPoints_Points_Log_Queries::register_query' => true,
			'WordPoints_Points_Logs_Query::__construct' => true,
			'WordPoints_Points_Logs_Query::_cache_get' => true,
			'WordPoints_Points_Logs_Query::_cache_set' => true,
			'WordPoints_Points_Logs_Query::_get' => true,
			'WordPoints_Points_Logs_Query::_prepare__in' => true,
			'WordPoints_Points_Logs_Query::_prepare_posint__in' => true,
			'WordPoints_Points_Logs_Query::get' => true,
			'WordPoints_Points_Logs_Query::prime_cache' => true,
			'WordPoints_Rank_Groups::register_type_for_group' => true,
			'WordPoints_Rank_Types::register_type' => array( 1, 2 ),
			'WordPoints_Ranks_Admin_Screen_Ajax::_send_json_result' => true,
			'WordPoints_Ranks_Admin_Screen_Ajax::_unexpected_error' => true,
			'WordPoints_Ranks_Admin_Screen_Ajax::_verify_request' => true,
			'WordPoints_Points_Un_Installer::_1_4_0_split_points_hooks' => true,
			'WordPoints_Points_Widget::__construct' => array( 1 ),
			'WordPoints_Points_Widget::get_field_id' => true,
			'WordPoints_Points_Widget::get_field_name' => true,
			'WordPoints_Shortcodes::register' => true,
			'WordPoints_Un_Installer_Base::get_updates_for' => true,
			'WordPoints_Un_Installer_Base::map_shortcuts' => true,
			'WordPoints_Un_Installer_Base::map_uninstall_shortcut' => true,
			'WordPoints_Un_Installer_Base::maybe_update_tables_to_utf8mb4' => true,
			'WordPoints_Un_Installer_Base::set_component_version' => true,
			'WordPoints_Un_Installer_Base::uninstall_metadata' => true,
			'WordPoints_Un_Installer_Base::uninstall_' => true,
			'WordPoints_Un_Installer_Base::update_' => true,

			// Instance methods.
			'$hook->get_description' => true,
			'$hook->get_option' => true,
			'$wordpoints_components->activate' => true,
		)
	);

	$parser->add_ignored_strings( array( '%points%' ) );
});

// EOF
