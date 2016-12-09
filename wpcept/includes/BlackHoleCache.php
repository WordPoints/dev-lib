<?php

/**
 * Black-hole cache class.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.5.0
 */

namespace WordPoints\Tests\Codeception;

/**
 * A WordPress object cache that eats everything you write to it.
 *
 * Using a class like this is the only way to entirely disable WordPress's object
 * caching feature. We use this class to do just that during the Acceptance tests,
 * since the values of any of the things in the database can be modified remotely.
 *
 * @since 2.5.0
 */
class BlackHoleCache extends \WP_Object_Cache {

	/**
	 * @since 2.5.0
	 */
	public function decr( $key, $offset = 1, $group = 'default' ) {
		return $offset - 1;
	}

	/**
	 * @since 2.5.0
	 */
	public function get( $key, $group = 'default', $force = false, &$found = null ) {
		return false;
	}

	/**
	 * @since 2.5.0
	 */
	public function incr( $key, $offset = 1, $group = 'default' ) {
		return $offset;
	}

	/**
	 * @since 2.5.0
	 */
	public function set( $key, $data, $group = 'default', $expire = 0 ) {
		return true;
	}
}

// EOF
