<?php

/**
 * WordPoints_Sniffs_PHP_MissingEchoSniff.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   J.D. Grimes <jdg@codesymphony.co>
 */

/**
 * WordPoints_Sniffs_PHP_MissingEchoSniff.
 *
 * Checks for code that may intended to be output but is missing an echo.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   J.D. Grimes <jdg@codesymphony.co>
 */
class WordPoints_Sniffs_PHP_MissingEchoSniff implements PHP_CodeSniffer_Sniff
{

	/**
	 * Custom functions that directly output their data.
	 *
	 * @var array
	 */
	public $customOutputFunctions = array();

	/**
	 * A list of functions that directly output their data.
	 *
	 * @var array
	 */
	public static $outputFunctions = array(
		// WordPress functions.
		'_e',
		'$wp_list_table->display',
		'$wp_list_table->search_box',
		'$wp_list_table->views',
		'checked',
		'esc_attr_e',
		'esc_html_e',
		'selected',
		'submit_button',
		'wp_nonce_field',

		// WordPoints functions.
		'$dropdown->display',
		'$hook->form_callback',
		'$hook->the_field_id',
		'$hook->the_field_name',
		'$this->the_field_id',
		'$this->the_field_name',
		'wordpoints_admin_show_tabs',
		'wordpoints_display_points',
		'WordPoints_Points_Hooks::list_by_points_type',
		'WordPoints_Points_Hooks::list_hooks',
		'WordPoints_Points_Hooks::points_type_form',
		'wordpoints_points_types_dropdown',
		'WordPoints_Rank_Types::get_type',
		'wordpoints_show_admin_error',
	);

	/**
	 * Whether the custom functions were added to the default list.
	 *
	 * @var bool
	 */
	protected static $addedCustomFunctions;

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register()
	{
		return array( T_OPEN_TAG );

	}//end register()


	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int                  $stackPtr  The position of the current token in the
	 *                                        stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		// Merge any custom functions with the defaults, if we haven't already.
		if ( ! self::$addedCustomFunctions ) {
			self::$outputFunctions = array_merge( self::$outputFunctions, $this->customOutputFunctions );
			self::$addedCustomFunctions = true;
		}

		$tokens = $phpcsFile->getTokens();

		// We only want one line PHP sections, so return if the closing tag is not
		// on the next line.
		$closeTag = $phpcsFile->findNext( T_CLOSE_TAG, $stackPtr, null, false );
		if ( ! $closeTag || $tokens[ $stackPtr ]['line'] !== $tokens[ $closeTag ]['line'] )  {
			return;
		}

		$stackPtr++;
		if ( $tokens[ $stackPtr ]['code'] === T_WHITESPACE ) {
			$stackPtr++;
		}

		$data = array( $tokens[ $stackPtr ]['content'] );

		if ( T_STRING === $tokens[ $stackPtr ]['code'] ) {

			if ( T_DOUBLE_COLON === $tokens[ $stackPtr + 1 ]['code'] ) {
				$data[0] .= $tokens[ $stackPtr + 1 ]['content'] . $tokens[ $stackPtr + 2 ]['content'];
			}

		} elseif ( T_VARIABLE === $tokens[ $stackPtr ]['code'] ) {

			if ( T_OBJECT_OPERATOR === $tokens[ $stackPtr + 1 ]['code'] ) {
				$data[0] .= $tokens[ $stackPtr + 1 ]['content'] . $tokens[ $stackPtr + 2 ]['content'];
			}

			if ( in_array( $tokens[ $stackPtr + 2 ]['code'], PHP_CodeSniffer_Tokens::$assignmentTokens ) ) {
				return;
			}

		} else {
			return;
		}

		if ( in_array( $data[0], self::$outputFunctions ) ) {
			return;
		}

		$error = 'Expected echo or other output function; found %s';

		if ( isset( $phpcsFile->fixer ) === true ) {

			$fix = $phpcsFile->addFixableError( $error, $stackPtr, 'MissingEcho', $data );

			if ( $fix === true ) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->addContentBefore( $stackPtr, 'echo ');
				$phpcsFile->fixer->endChangeset();
			}

		} else {

			$phpcsFile->addError( $error, $stackPtr, 'MissingEcho', $data );
		}

	}//end process()


}//end class
