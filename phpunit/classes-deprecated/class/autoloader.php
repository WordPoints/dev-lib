<?php

/**
 * WordPoints Dev-lib PHPUnit class autoloader.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Autoloads classes.
 *
 * @since 2.4.0
 * @deprecated 2.6.0 Use WordPoints_PHPUnit_Class_Autoloader.
 */
class WordPoints_Dev_Lib_PHPUnit_Class_Autoloader
	extends WordPoints_PHPUnit_Class_Autoloader {

	/**
	 * @since 2.4.0
	 */
	public static function register_dir( $dir, $prefix ) {

		_deprecated_function(
			__CLASS__
			, '2.6.0'
			, 'WordPoints_PHPUnit_Class_Autoloader'
		);

		parent::register_dir( $dir, $prefix );
	}
}

// EOF
