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
			'wordpoints_add_maybe_network_option' => array( 4 ),
			'wordpoints_add_network_option' => true,
			'wordpoints_add_points' => array( 1, 2, 3, 4 ),
			'wordpoints_add_rank' => array( 2, 3, 4 ),
			'wordpoints_alter_points' => array( 1, 2, 3, 4 ),
			'wordpoints_component' => true,
			'wordpoints_debug_message' => true,
			'wordpoints_dir_include' => true,
			'wordpoints_display_points' => true,
			'wordpoints_escape_mysql_identifier' => true,
			'wordpoints_format_points' => true,
			'wordpoints_get_array_option' => true,
			'wordpoints_get_excluded_users' => true,
			'wordpoints_get_formatted_points' => true,
			'wordpoints_get_formatted_user_rank' => array( 1, 2, 3 ),
			'wordpoints_get_points_logs_query' => true,
			'wordpoints_get_points_logs_query_args' => true,
			'wordpoints_get_points_type_setting' => true,
			'wordpoints_hooks_ui_get_script_data_from_objects' => array( 2 ),
			'wordpoints_list_post_types' => true,
			'wordpoints_load_module_textdomain' => true,
			'wordpoints_module' => true,
			'wordpoints_modules_url' => true,
			'wordpoints_points_show_top_users' => array( 3 ),
			'wordpoints_prepare__in' => true,
			'wordpoints_register_points_logs_query' => true,
			'wordpoints_show_admin_message' => array( 2 ),
			'wordpoints_show_points_logs_query' => true,
			'wordpoints_subtract_points' => array( 1, 2, 3, 4 ),
			'wordpoints_verify_nonce' => true,

			// Class methods.
			'WordPoints_Admin_Ajax_Hooks::send_json_result' => array( 2 ),
			'WordPoints_App::__construct' => array( 1 ),
			'WordPoints_Class_Autoloader::register_dir' => true,
			'WordPoints_DB_Query::get' => array( 1 ),
			'WordPoints_DB_Query::prepare_column__in' => array( 3 ),
			'WordPoints_Entity_Restrictions::get' => array( 3 ),
			'WordPoints_Entityish::__construct' => array( 1 ),
			'WordPoints_Hook_Events::get_sub_app' => array( 1 ),
			'WordPoints_Hook_Reaction::get_meta' => array( 1 ),
			'WordPoints_Hook_Reaction_Validator::add_error' => array( 2 ),
			'WordPoints_Hook_Router::add_event_to_action' => true,
			'WordPoints_Hook_Router::remove_event_from_action' => true,
			'WordPoints_Hooks::current_mode' => true,
			'WordPoints_Hooks::get_sub_app' => array( 1 ),
			'WordPoints_Installables::get_installer' => true,
			'WordPoints_Installables::install' => true,
			'WordPoints_Installables::register' => true,
			'WordPoints_Installables::uninstall' => true,
			'WordPoints_Modules::get_data' => true,
			'WordPoints_Modules::register' => true,
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
			'WordPoints_Points_Un_Installer::import_legacy_points_hook' => true,
			'WordPoints_Points_Widget_Logs::get_field_id' => true,
			'WordPoints_Points_Widget_Logs::get_field_name' => true,
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
			'WordPoints_Widget::get_field_name' => true,

			// Property methods.
			'WordPoints_App::$main->get_sub_app' => array( 1 ),
			'WordPoints_Hook_Condition_Entity_Array_Contains::validator->add_error' => array( 2 ),
			'WordPoints_Hook_Condition_Entity_Array_Contains::validator->push_field' => array( 1 ),
			'WordPoints_Hook_Extension::validator->add_error' => array( 2 ),
			'WordPoints_Hook_Extension::validator->push_field' => array( 1 ),
			'WordPoints_Hook_Reaction_Validator::hooks->get_sub_app' => array( 1 ),
			'WordPoints_Hook_Router::events->get_sub_app' => array( 1 ),
			'WordPoints_Points_Admin_Screen_Points_Types::hooks->get_sub_app' => array( 1 ),
			'WordPoints_Points_Legacy_Hook_To_Reaction_Importer::legacy_handler->get_option' => true,
			'WordPoints_Points_Legacy_Hook_To_Reaction_Importer::legacy_handler->points_type' => true,

			// Instance methods.
			'$actions->register' => array( 1, 2 ),
			'$admin_screens->register' => array( 1, 2 ),
			'$apps->get' => array( 1 ),
			'$apps->register' => array( 1, 2 ),
			'$children->register' => array( 1, 2, 3 ),
			'$conditions->register' => array( 1, 2, 3 ),
			'$contexts->register' => array( 1, 2 ),
			'$data_types->register' => array( 1, 2 ),
			'$entities->register' => array( 1, 2 ),
			'$events->register' => array( 1, 2 ),
			'$events->get_sub_app' => array( 1 ),
			'$entities->get_sub_app' => array( 1 ),
			'$events_app->get_sub_app' => array( 1 ),
			'$extensions->register' => array( 1, 2 ),
			'$hook->get_description' => true,
			'$hook->get_option' => true,
			'$hooks->get_sub_app' => array( 1 ),
			'$hooks->set_current_mode' => true,
			'$sub_apps->register' => array( 1, 2 ),
			'$validator->add_error' => array( 2 ),
			'$reaction->update_meta' => array( 1 ),
			'$reaction->get_meta' => array( 1 ),
			'$reactors->register' => array( 1, 2 ),
			'$reaction_stores->register' => array( 1, 2, 3 ),
			'$restrictions->get' => array( 3 ),
			'$restrictions->register' => array( 1, 2, 3 ),
			'$query->get' => array( 1 ),
			'$wordpoints_components->activate' => true,

			// Universal instance methods.
			'(unknown)->get_reaction_store' => array( 1 ),
			'(unknown)->get_sub_app' => array( 1 ),
			'(unknown)->set_current_mode' => array( 1 ),
		)
	);

	$parser->add_ignored_properties(
		array(
			'WordPoints_Entity_Attr::$data_type' => true,
			'WordPoints_Entity_Attr::$field' => true,
			'WordPoints_Entity_Attr_Field::$data_type' => true,
			'WordPoints_Entity_Attr_Field::$field' => true,
			'WordPoints_Entity_Attr_Field::$storage_type' => true,
			'WordPoints_Entity::$context' => true,
			'WordPoints_Entity::$human_id_field' => true,
			'WordPoints_Entity::$id_field' => true,
			'WordPoints_Entity_Context::$parent_slug' => true,
			'WordPoints_Entity_Relationship::$primary_entity_slug' => true,
			'WordPoints_Entity_Relationship::$related_entity_slug' => true,
			'WordPoints_Entity_Relationship::$related_ids_field' => true,
			'WordPoints_Entity_Relationship_Dynamic::$primary_entity_slug' => true,
			'WordPoints_Entity_Relationship_Dynamic::$related_entity_slug' => true,
			'WordPoints_Entity_Relationship_Dynamic::$related_ids_field' => true,
			'WordPoints_Entity_Relationship_Dynamic_Stored_Field::$primary_entity_slug' => true,
			'WordPoints_Entity_Relationship_Dynamic_Stored_Field::$related_entity_slug' => true,
			'WordPoints_Entity_Relationship_Dynamic_Stored_Field::$related_ids_field' => true,
			'WordPoints_Entity_Relationship_Stored_Field::$primary_entity_slug' => true,
			'WordPoints_Entity_Relationship_Stored_Field::$related_entity_slug' => true,
			'WordPoints_Entity_Relationship_Stored_Field::$related_ids_field' => true,
			'WordPoints_Entity_Relationship_Stored_Field::$storage_type' => true,
			'WordPoints_Entity_Stored_Array::$context' => true,
			'WordPoints_Entity_Stored_Array::$getter' => true,
			'WordPoints_Entity_Stored_Array::$human_id_field' => true,
			'WordPoints_Entity_Stored_Array::$id_field' => true,
			'WordPoints_Entity_Stored_DB_Table::$context' => true,
			'WordPoints_Entity_Stored_DB_Table::$getter' => true,
			'WordPoints_Entity_Stored_DB_Table::$human_id_field' => true,
			'WordPoints_Entity_Stored_DB_Table::$id_field' => true,
			'WordPoints_Entity_Stored_DB_Table::$wpdb_table_name' => true,
			'WordPoints_Hook_Action_Post_Type::$post_hierarchy' => true,
			'WordPoints_Hook_Action_Post_Type_Comment::$comment_type' => true,
			'WordPoints_Hook_Action_Post_Type_Comment::$post_hierarchy' => true,
			'WordPoints_Hook_Event_Dynamic::$generic_entity_slug' => true,
			'WordPoints_Hook_Extension::$slug' => true,
			'WordPoints_Hook_Reaction_Store::$context' => true,
			'WordPoints_Hook_Reaction_Store::$reaction_class' => true,
			'WordPoints_Hook_Reaction_Store_Options::$context' => true,
			'WordPoints_Hook_Reaction_Store_Options::$reaction_class' => true,
			'WordPoints_Hook_Reactor::$action_types' => true,
			'WordPoints_Hook_Reactor::$arg_types' => true,
			'WordPoints_Hook_Reactor::$slug' => true,
			'WordPoints_Points_Widget_Logs::$query_slug' => true,
			'WordPoints_Un_Installer_Base::$type' => true,
		)
	);

	$parser->add_ignored_atts(
		array(
			'data-wordpoints-hooks-reaction-store',
			'data-wordpoints-hooks-reactor',
		)
	);

	$parser->add_ignored_strings(
		array(
			'%points%', // Placeholder.
			'db', // Entity storage info.
			'table', // Entity storage info.
			'WordPoints_Hook_Arg',
			'.min',
		)
	);
});

// EOF
