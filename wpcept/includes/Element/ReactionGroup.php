<?php

/**
 * Points hook reaction group DOM element class.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.7.0
 */

namespace WordPoints\Tests\Codeception\Element;

use WordPoints\Tests\Codeception\AcceptanceTester;
use WordPoints\Tests\Codeception\Element;

/**
 * Represents the DOM element of a points hook reaction group.
 *
 * @since 2.7.0
 */
class ReactionGroup extends Element {

	/**
	 * The pattern for the selector.
	 *
	 * First substitution is the reactor slug, the second is the event slug.
	 *
	 * @since 2.7.0
	 *
	 * @var string
	 */
	protected $selector_pattern = '#%1$s-%2$s ';

	/**
	 * @since 2.7.0
	 *
	 * @param AcceptanceTester $actor        The actor object.
	 * @param string           $reactor_slug The reactor slug.
	 * @param string           $event_slug   The event slug.
	 */
	public function __construct( AcceptanceTester $actor, $reactor_slug, $event_slug ) {

		$this->setActor( $actor );

		$this->selector = sprintf(
			$this->selector_pattern
			, $reactor_slug
			// In selectors passed to WebDriver, backslashes need to be escaped.
			, str_replace( '\\', '\\\\', $event_slug )
		);
	}

	/**
	 * Add a new reaction to this group.
	 *
	 * @since 2.7.0
	 */
	public function addNew() {

		$I = $this->actor;
		$I->click( 'Add New Reaction', $this->selector );
		$I->waitForNewReaction();

		return new Reaction( $I, null, $this );
	}
}

// EOF
