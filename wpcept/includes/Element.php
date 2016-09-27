<?php

/**
 * DOM Element class for the Codeception tests.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.5.0
 */

namespace WordPoints\Tests\Codeception;

/**
 * Base class for interacting with a DOM element.
 *
 * @since 2.5.0
 */
abstract class Element {

	/**
	 * The selector for this element.
	 *
	 * @since 2.5.0
	 *
	 * @var string
	 */
	protected $selector;

	/**
	 * The actor being used in the tests.
	 *
	 * @since 2.5.0
	 *
	 * @var AcceptanceTester
	 */
	protected $actor;

	/**
	 * Returns the selector for the element.
	 *
	 * @since 2.5.0
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->selector;
	}

	/**
	 * Set the actor object to use.
	 *
	 * @since 2.5.0
	 *
	 * @param AcceptanceTester $actor The actor object.
	 */
	public function setActor( AcceptanceTester $actor ) {
		$this->actor = $actor;
	}
}

// EOF
