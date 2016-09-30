<?php

/**
 * Points hook reaction DOM element class.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.5.0
 */

namespace WordPoints\Tests\Codeception\Element;

use WordPoints\Tests\Codeception\AcceptanceTester;
use WordPoints\Tests\Codeception\Element;

/**
 * Represents the DOM element of a points hook reaction.
 *
 * @since 2.5.0
 */
class Reaction extends Element {

	/**
	 * The pattern for the selector.
	 *
	 * First substitution is the reactor slug, the second is the event slug.
	 *
	 * @since 2.5.0
	 *
	 * @var string
	 */
	protected $selector_pattern = '#%1$s-%2$s .wordpoints-hook-reaction ';

	/**
	 * @since 2.5.0
	 *
	 * @param AcceptanceTester           $actor    The actor object.
	 * @param \WordPoints_Hook_ReactionI $reaction The reaction object.
	 */
	public function __construct( AcceptanceTester $actor, \WordPoints_Hook_ReactionI $reaction ) {

		$this->setActor( $actor );

		$this->selector = sprintf(
			$this->selector_pattern
			, $reaction->get_reactor_slug()
			// In selectors passed to WebDriver, backslashes need to be escaped.
			, str_replace( '\\', '\\\\', $reaction->get_event_slug() )
		);
	}

	/**
	 * Open the reaction form for editing.
	 *
	 * @since 2.5.0
	 */
	public function edit() {
		$I = $this->actor;
		$I->click( 'Edit', $this->selector );
	}

	/**
	 * Cancel editing the reaction.
	 *
	 * @since 2.5.0
	 */
	public function cancel() {
		$I = $this->actor;
		$I->click( 'Cancel', $this->selector . '.action-buttons' );
	}

	/**
	 * Save the reaction.
	 *
	 * Also tests that the save was a success.
	 *
	 * @since 2.5.0
	 */
	public function save() {

		$I = $this->actor;
		$I->click( 'Save', $this->selector );
		$I->waitForJqueryAjax();
		$I->see( 'Your changes have been saved.', $this->selector . '.messages' );
	}

	/**
	 * Select a condition to add in the add condition form.
	 *
	 * Assumes that the add condition form is already open.
	 *
	 * @since 2.5.0
	 *
	 * @param array $condition The title of the condition type and arg(s) to select.
	 */
	public function selectCondition( array $condition ) {

		$I = $this->actor;
		$I->selectOption(
			$this->selector . '.condition-selectors .arg-selector select'
			, $condition[0]
		);

		$I->selectOption(
			$this->selector . '.condition-selectors .condition-selector select'
			, $condition[1]
		);
	}

	/**
	 * Add a new condition to a reaction.
	 *
	 * The returned condition object is pre-filled with the actor in use by this
	 * reaction.
	 *
	 * @since 2.5.0
	 *
	 * @param array $title The title of the condition and arg(s) to select.
	 *
	 * @return ReactionCondition The condition element object.
	 */
	public function addCondition( array $title ) {

		$I = $this->actor;
		$I->see( 'Conditions', $this->selector );
		$I->click( 'Add New Condition', $this->selector );
		$this->selectCondition( $title );
		$I->click( 'Add', $this->selector . '.add-condition-form' );

		return new ReactionCondition( $I, $this );
	}
}

// EOF
