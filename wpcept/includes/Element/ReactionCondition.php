<?php

/**
 * Points hook reaction condition DOM element class.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.5.0
 */

namespace WordPoints\Tests\Codeception\Element;

use WordPoints\Tests\Codeception\AcceptanceTester;
use WordPoints\Tests\Codeception\Element;

/**
 * Represents the element for a points hook reaction condition in the DOM.
 *
 * @since 2.5.0
 */
class ReactionCondition extends Element {

	/**
	 * Construct the element with its parent reaction element.
	 *
	 * @since 2.5.0
	 *
	 * @param AcceptanceTester $actor    The actor object.
	 * @param Reaction         $reaction The reaction element object.
	 */
	public function __construct( AcceptanceTester $actor, Reaction $reaction ) {

		$this->setActor( $actor );

		$this->selector = $reaction . '.condition ';
	}

	/**
	 * Delete the condition.
	 *
	 * @since 2.5.0
	 */
	public function delete() {
		$I = $this->actor;
		$I->click( 'Remove Condition', $this->selector );
	}
}

// EOF
