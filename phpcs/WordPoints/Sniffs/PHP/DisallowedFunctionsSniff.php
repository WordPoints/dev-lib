<?php

/**
 * WordPoints_Sniffs_PHP_DisallowedFunctionsSniff.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.1.0
 */

use WordPressCS\WordPress\AbstractFunctionRestrictionsSniff;

/**
 * WordPoints_Sniffs_PHP_DisallowedFunctionsSniff.
 *
 * Checks for functions that aren't allowed.
 *
 * @since 2.1.0
 */
class WordPoints_Sniffs_PHP_DisallowedFunctionsSniff
	extends AbstractFunctionRestrictionsSniff {

	/**
	 * @since 2.1.0
	 */
	public function getGroups() {

		return array(
			'esc_js' => array(
				'type'      => 'error',
				'message'   => 'Do not use esc_js(), use wp_json_encode() instead.',
				'functions' => array( 'esc_js' ),
			),
			'esc_sql' => array(
				'type'      => 'error',
				'message'   => 'Do not use esc_sql(), use $wpdb->prepare() instead.',
				'functions' => array( 'esc_sql' ),
			),
			'gmdate' => array(
				'type'      => 'error',
				'message'   => 'No need to use gmdate(), use date() instead, as it is always UTC in WordPress.',
				'functions' => array( 'gmdate' ),
			),
			'unserialize' => array(
				'type'      => 'error',
				'message'   => 'Do not unserialize untrusted data.',
				'functions' => array( 'unserialize', 'maybe_unserialize' ),
			),
			'wp_redirect' => array(
				'type'      => 'error',
				'message'   => 'Using wp_redirect() can lead to open redirects, use wp_safe_redirect() instead.',
				'functions' => array( 'wp_redirect' ),
			),
			'wp_remote' => array(
				'type'      => 'error',
				'message'   => 'Using wp_remote_*() can lead to unsafe internal requests, use wp_safe_remote_*() instead.',
				'functions' => array( 'wp_remote_*' ),
				'whitelist' => array(
					'wp_remote_retrieve_body'             => true,
					'wp_remote_retrieve_cookie'           => true,
					'wp_remote_retrieve_cookie_value'     => true,
					'wp_remote_retrieve_cookies'          => true,
					'wp_remote_retrieve_header'           => true,
					'wp_remote_retrieve_headers'          => true,
					'wp_remote_retrieve_response_code'    => true,
					'wp_remote_retrieve_response_message' => true,
				),
			),
		);
	}

} // class WordPoints_Sniffs_PHP_DisallowedFunctionsSniff

// EOF
