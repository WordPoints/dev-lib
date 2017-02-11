<?php

/**
 * WordPoints PHPUnit Restricted Assertions Sniff class.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.6.0
 */

/**
 * Sniff for restricted assertions used in PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_Sniffs_PHPUnit_RestrictedAssertionsSniff
	extends WordPress_AbstractFunctionRestrictionsSniff {

	/**
	 * @since 2.6.0
	 */
	public function is_targetted_token( $stackPtr ) {

		// Exclude everything except class methods.
		if (
			T_STRING === $this->tokens[ $stackPtr ]['code']
			&& isset( $this->tokens[ ( $stackPtr - 1 ) ] )
		) {

			$prev = $this->phpcsFile->findPrevious(
				PHP_CodeSniffer_Tokens::$emptyTokens
				, ( $stackPtr - 1 )
				, null
				, true
			);

			if ( false === $prev ) {
				return false;
			}

			$include = array(
				T_DOUBLE_COLON    => T_DOUBLE_COLON,
				T_OBJECT_OPERATOR => T_OBJECT_OPERATOR,
			);

			if ( isset( $include[ $this->tokens[ $prev ]['code'] ] ) ) {
				return true;
			}
		}

		return false;

	} // End is_targetted_token().

	/**
	 * @since 2.6.0
	 */
	public function getGroups() {
		return array(
			'strict_equals' => array(
				'type'      => 'error',
				'message'   => 'Use strict equality assertions, like assertSame() Found: %s.',
				'functions' => array(
					'assertEquals',
					'assertEmpty',
					'assertEqualFields',
					'assertEqualSets',
					'assertEqualSetsWithIndex',
					'assertContains',
					'assertAttributeContains',
				),
			),
		);
	}

} // class WordPoints_Sniffs_PHPUnit_RestrictedAssertionsSniff

// EOF
