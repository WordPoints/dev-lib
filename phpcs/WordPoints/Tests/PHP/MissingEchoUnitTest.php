<?php

/**
 * Unit test class for the MissingEcho sniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    J.D. Grimes
 */

/**
 * Unit test class for the MissingEcho sniff.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    J.D. Grimes
 */
class WordPoints_Tests_PHP_MissingEchoUnitTest extends AbstractSniffUnitTest
{


	/**
	 * Returns the lines where errors should occur.
	 *
	 * The key of the array should represent the line number and the value
	 * should represent the number of errors that should occur on that line.
	 *
	 * @return array(int => int)
	 */
	public function getErrorList()
	{
		return array(
			4 => 1,
			7 => 1,
		);

	}//end getErrorList()


	/**
	 * Returns the lines where warnings should occur.
	 *
	 * The key of the array should represent the line number and the value
	 * should represent the number of warnings that should occur on that line.
	 *
	 * @return array(int => int)
	 */
	public function getWarningList()
	{
		return array();

	}//end getWarningList()


}//end class
