<?php

/**
 * WordPoints PHPUnit Missing Covers Sniff class.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.3.0
 */

/**
 * Sniff for missing @covers annotations on PHPUnit tests.
 *
 * @since 2.3.0
 */
class WordPoints_Sniffs_PHPUnit_MissingCoversSniff implements PHP_CodeSniffer_Sniff {

	/**
	 * @since 2.3.0
	 */
	public function register() {
		return array( T_CLASS, T_FUNCTION );
	}

	/**
	 * @since 2.3.0
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		$is_class = ! $is_function = T_FUNCTION === $tokens[ $stackPtr ]['code'];

		$scope_closer = $tokens[ $stackPtr ]['scope_closer'];

		if ( $is_function ) {

			$function_name = $phpcsFile->getDeclarationName( $stackPtr );

			// If this isn't a test method, we don't need to check it.
			if ( 'test' !== substr( $function_name, 0, 4 ) ) {
				return $scope_closer;
			}

			$class = $phpcsFile->getCondition( $stackPtr, T_CLASS );

			// If it isn't in a class, we don't need to check it.
			if ( ! $class ) {
				return $scope_closer;
			}
		}

		// Find the previous token (excluding scope modifiers and whitespace).
		$exclude = PHP_CodeSniffer_Tokens::$methodPrefixes;
		$exclude[] = T_WHITESPACE;

		$comment_closer = $phpcsFile->findPrevious( $exclude, $stackPtr - 1, 0, $exclude );

		// If the token isn't the close of a doc comment, there is no doc comment for
		// this item, and therefore no annotations.
		if ( T_DOC_COMMENT_CLOSE_TAG !== $tokens[ $comment_closer ]['code'] ) {

			if ( $is_function ) {

				$phpcsFile->addError(
					'Missing PHPUnit code coverage annotation.'
					, $stackPtr
					, 'NoDocComment'
				);

				return $scope_closer;
			}

			return null;
		}

		// Now check if the docblock contains the required annotations.
		$comment_opener = $tokens[ $comment_closer ]['comment_opener'];

		$has_annotation = false;

		foreach ( $tokens[ $comment_opener ]['comment_tags'] as $tag ) {

			$tag_name = $tokens[ $tag ]['content'];

			if ( '@covers' === $tag_name || '@coversNothing' === $tag_name ) {
				$has_annotation = true;
				break;
			}
		}

		if ( ! $has_annotation ) {

			if ( $is_class ) {

				// Classes don't have to have the annotation, so this is OK. However,
				// each method will have to have it.
				return null;

			} elseif ( $is_function ) {

				$phpcsFile->addError(
					'Missing PHPUnit code coverage annotation.'
					, $stackPtr
					, 'MissingAnnotation'
				);
			}
		}

		// We skip to the end of the class/function. If we're in a class and we're
		// still here, we found the annotation, so we don't need to check each
		// method. If we're in a function, we don't need to test any sub-functions
		// either, since they won't be test methods.
		return $scope_closer;

	} // public function process()

} // class WordPoints_Sniffs_PHPUnit_MissingCoversSniff

// EOF
