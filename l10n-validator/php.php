<?php

/**
 * Ignores of PHP things.
 *
 * @package WordPoints_Dev_Lib\L10n-Validator
 * @since 1.1.0
 */

WP_L10n_Validator::register_config_callback(
	function( $parser ) {

		$parser->add_ignored_functions(
			array(
				// Functions.
				'class_exists' => true,
				'define'       => true,
			)
		);
	}
);

// EOF
