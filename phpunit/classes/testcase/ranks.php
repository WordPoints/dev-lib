<?php

/**
 * A parent test case class for rank tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Parent test case for rank tests.
 *
 * @since 2.6.0
 */
abstract class WordPoints_PHPUnit_TestCase_Ranks
	extends WordPoints_PHPUnit_TestCase {

	/**
	 * @since 2.6.0
	 */
	protected $wordpoints_component = 'ranks';

	/**
	 * The slug of the rank group used in the tests.
	 *
	 * @since 2.6.0
	 *
	 * @type string $rank_group
	 */
	protected $rank_group = 'test_group';

	/**
	 * The slug of the rank type used in the tests.
	 *
	 * @since 2.6.0
	 *
	 * @type string $rank_type
	 */
	protected $rank_type = 'test_type';

	/**
	 * Set up for each test.
	 *
	 * @since 2.6.0
	 */
	public function setUp() {

		parent::setUp();

		WordPoints_Rank_Types::register_type(
			$this->rank_type
			, 'WordPoints_PHPUnit_Mock_Rank_Type'
		);

		WordPoints_Rank_Groups::register_group(
			$this->rank_group
			, array( 'name' => 'Test Group' )
		);

		WordPoints_Rank_Groups::register_type_for_group(
			$this->rank_type
			, $this->rank_group
		);
	}

	/**
	 * Clean up after each test.
	 *
	 * @since 2.6.0
	 */
	public function tearDown() {

		WordPoints_Rank_Types::deregister_type( $this->rank_type );
		WordPoints_Rank_Groups::deregister_group( $this->rank_group );

		parent::tearDown();
	}
}

// EOF
