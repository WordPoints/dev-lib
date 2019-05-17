<?php

/**
 * WordPoints_Sniffs_PHP_MissingEchoSniff.
 *
 * @package WordPoints_Dev_Lib
 * @since 1.0.0
 */

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;


/**
 * WordPoints_Sniffs_PHP_MissingEchoSniff.
 *
 * Checks for code that may intended to be output but is missing an echo.
 *
 * @since 1.0.0
 */
class WordPoints_Sniffs_PHP_MissingEchoSniff implements Sniff {

	/**
	 * Custom functions that directly output their data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $customOutputFunctions = array();

	/**
	 * A list of functions that directly output their data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public static $outputFunctions = array(
		// PHP built-in functions.
		'printf',
		'vprintf',

		// WordPress functions.
		'_e',
		'$wp_list_table->display',
		'$wp_list_table->search_box',
		'$wp_list_table->views',
		'checked',
		'disabled',
		'do_meta_boxes',
		'esc_attr_e',
		'esc_html_e',
		'selected',
		'settings_errors',
		'submit_button',
		'wp_nonce_field',

		// WordPoints functions.
		'$hook->form_callback',
		'$hook->the_field_id',
		'$hook->the_field_name',
		'$this->pagination',
		'$this->search_box',
		'$this->single_row_columns',
		'$this->the_field_id',
		'$this->the_field_name',
		'wordpoints_admin_show_tabs',
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
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected static $addedCustomFunctions;

	/**
	 * @since 1.0.0
	 */
	public function register() {
		return array( T_OPEN_TAG );
	}

	/**
	 * @since 1.0.0
	 */
	public function process( PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr ) {

		// Merge any custom functions with the defaults, if we haven't already.
		if ( ! self::$addedCustomFunctions ) {
			self::$outputFunctions      = array_merge( self::$outputFunctions, $this->customOutputFunctions );
			self::$addedCustomFunctions = true;
		}

		$tokens = $phpcsFile->getTokens();

		// We only want one line PHP sections, so return if the closing tag is not
		// on the next line.
		$closeTag = $phpcsFile->findNext( T_CLOSE_TAG, $stackPtr, null, false );
		if ( ! $closeTag || $tokens[ $stackPtr ]['line'] !== $tokens[ $closeTag ]['line'] ) {
			return;
		}

		$stackPtr++;
		if ( T_WHITESPACE === $tokens[ $stackPtr ]['code'] ) {
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

			if ( in_array( $tokens[ $stackPtr + 2 ]['code'], Tokens::$assignmentTokens, true ) ) {
				return;
			}

		} else {
			return;
		}

		if ( in_array( $data[0], self::$outputFunctions, true ) ) {
			return;
		}

		if ( strpos( $data[0], 'display' ) ) {
			return;
		}

		$error = 'Expected echo or other output function; found %s';

		if ( isset( $phpcsFile->fixer ) ) {

			$fix = $phpcsFile->addFixableError( $error, $stackPtr, 'MissingEcho', $data );

			if ( $fix ) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->addContentBefore( $stackPtr, 'echo ' );
				$phpcsFile->fixer->endChangeset();
			}

		} else {

			$phpcsFile->addError( $error, $stackPtr, 'MissingEcho', $data );
		}

	} // End public function process().

} // End class WordPoints_Sniffs_PHP_MissingEchoSniff.

// EOF
