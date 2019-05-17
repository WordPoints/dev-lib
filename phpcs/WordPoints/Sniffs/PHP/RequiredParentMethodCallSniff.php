<?php

/**
 * Sniff for methods that are required to call the parent method.
 *
 * @package WordPoints_Dev_Lib\PHPCS
 * @since 2.2.0
 */

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Warns about methods not calling the parent method when they are supposed to.
 *
 * @since 2.2.0
 */
class WordPoints_Sniffs_PHP_RequiredParentMethodCallSniff implements Sniff {

	/**
	 * A list of methods whose parents are required to be called.
	 *
	 * @since 2.2.0
	 *
	 * @var string[]
	 */
	public $methods = array(
		'setUp'              => true,
		'tearDown'           => true,
		'setUpBeforeClass'   => true,
		'tearDownAfterClass' => true,
	);

	/**
	 * @since 2.2.0
	 */
	public function register() {
		return array( T_FUNCTION );
	}

	/**
	 * @since 2.0.0
	 */
	public function process( PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();
		$token  = $tokens[ $stackPtr ];

		// Skip function without body.
		if ( isset( $token['scope_opener'] ) === false ) {
			return;
		}

		// Get function name.
		$methodName = $phpcsFile->getDeclarationName( $stackPtr );

		// If this method isn't required to call it's parent, bail out.
		if ( ! isset( $this->methods[ $methodName ] ) ) {
			return;
		}

		$next = ++$token['scope_opener'];
		$end  = --$token['scope_closer'];

		for ( ; $next <= $end; ++$next ) {

			if ( T_PARENT !== $tokens[ $next ]['code'] ) {
				continue;
			}

			// Find next non empty token index, should be double colon.
			$next = $phpcsFile->findNext(
				Tokens::$emptyTokens
				, $next + 1
				, null
				, true
			);

			// Skip for invalid code.
			if ( false === $next || T_DOUBLE_COLON !== $tokens[ $next ]['code'] ) {
				continue;
			}

			// Find next non empty token index, should be the function name.
			$next = $phpcsFile->findNext(
				Tokens::$emptyTokens
				, $next + 1
				, null
				, true
			);

			// If this is the method we are looking for, we're done.
			if ( false !== $next && $tokens[ $next ]['content'] === $methodName ) {
				return;
			}
		}

		// We didn't find a call to the parent method.
		$phpcsFile->addError(
			'Missing call to parent::%s()'
			, $stackPtr
			, 'Missing'
			, $methodName
		);
	}
}

// EOF
