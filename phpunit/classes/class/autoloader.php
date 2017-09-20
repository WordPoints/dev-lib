<?php

/**
 * WordPoints Dev-lib PHPUnit class autoloader.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.4.0
 */

/**
 * Autoloads classes.
 *
 * {@link http://www.php-fig.org/psr/psr-0/ PSR-0} is loosely followed, with the
 * following differences:
 * - Currently, no provision is made for namespaces.
 * - The file names are expected to be all lowercase.
 *
 * @since 2.4.0 As WordPoints_Dev_Lib_PHPUnit_Class_Autoloader.
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Class_Autoloader {

	/**
	 * The prefixes of classes to autoload.
	 *
	 * @since 2.4.0 As part of WordPoints_Dev_Lib_PHPUnit_Class_Autoloader.
	 * @since 2.6.0
	 *
	 * @var array[]
	 */
	protected static $prefixes = array();

	/**
	 * Whether the registered directories have been sorted.
	 *
	 * We use this flag to prevent us from resorting the directories unnecessarily.
	 *
	 * @since 2.4.0 As part of WordPoints_Dev_Lib_PHPUnit_Class_Autoloader.
	 * @since 2.6.0
	 *
	 * @var bool
	 */
	protected static $sorted = false;

	/**
	 * Whether we've registered ourselves as an autoloader yet.
	 *
	 * @since 2.45.0 As part of WordPoints_Dev_Lib_PHPUnit_Class_Autoloader.
	 * @since 2.6.0
	 *
	 * @var bool
	 */
	protected static $registered_autoloader = false;

	/**
	 * Register a directory to autoload classes from.
	 *
	 * @since 2.4.0 As part of WordPoints_Dev_Lib_PHPUnit_Class_Autoloader.
	 * @since 2.6.0
	 *
	 * @param string $dir    The full path of the directory.
	 * @param string $prefix The prefix used for class names in this directory.
	 */
	public static function register_dir( $dir, $prefix ) {

		if ( ! self::$registered_autoloader ) {
			spl_autoload_register( __CLASS__ . '::load_class' );
			self::$registered_autoloader = true;
		}

		self::$prefixes[ $prefix ]['length'] = strlen( $prefix );
		self::$prefixes[ $prefix ]['dirs'][] = rtrim( $dir, '/\\' ) . '/';

		self::$sorted = false;
	}

	/**
	 * Load a class.
	 *
	 * Checks if the class name matches any of the registered prefixes, and if so,
	 * checks whether a file for that class exists in the registered directories for
	 * that prefix. If the file does exist, it is included.
	 *
	 * @since 2.4.0 As part of WordPoints_Dev_Lib_PHPUnit_Class_Autoloader.
	 * @since 2.6.0
	 *
	 * @param string $class_name The name fo the class to load.
	 */
	public static function load_class( $class_name ) {

		if ( ! self::$sorted ) {
			arsort( self::$prefixes );
			self::$sorted = true;
		}

		foreach ( self::$prefixes as $prefix => $data ) {

			if ( substr( $class_name, 0, $data['length'] ) !== $prefix ) {
				continue;
			}

			$trimmed_class_name = substr( $class_name, $data['length'] );

			$file_name = str_replace( '_', '/', strtolower( $trimmed_class_name ) );
			$file_name = $file_name . '.php';

			foreach ( $data['dirs'] as $dir ) {

				// So that we don't modify the file name within the loop.
				$full_path = $dir . $file_name;

				// Autoloading for tests, in case they sub-class one another (which
				// generally they shouldn't).
				if ( false !== strpos( $dir, '/phpunit/tests/' ) ) {
					if ( '/test' === substr( $full_path, -9, 5 ) ) {
						$full_path = substr( $full_path, 0, - 9 ) . '.php';
					} else {
						continue;
					}
				}

				if ( ! file_exists( $full_path ) ) {
					continue;
				}

				require_once $full_path;

				return;
			}
		}
	}
}

// EOF
