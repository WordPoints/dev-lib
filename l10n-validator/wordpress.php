<?php

/**
 * Ignores of WordPress things.
 *
 * @package WordPoints_Dev_Lib\L10n-Validator
 * @since 1.1.0
 */

WP_L10n_Validator::register_config_callback( function( $parser ) {

	$parser->add_ignored_functions(
		array(
			// Functions.
			'_deprecated_argument' => true,
			'_deprecated_file' => true,
			'_doing_it_wrong' => true,
			'add_site_option' => true,
			'apply_filters_deprecated' => array( 1, 3, 4, 5 ),
			'do_action_deprecated' => array( 1, 3, 4, 5 ),
		)
	);

	$parser->add_ignored_strings(
		array(
			'admin.php',
			'widefat',
		)
	);
});

// EOF
