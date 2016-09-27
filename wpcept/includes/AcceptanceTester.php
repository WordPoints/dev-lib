<?php

/**
 * Acceptance tester class.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.4.0
 */

namespace WordPoints\Tests\Codeception;

/**
 * Tester for use in the acceptance tests.
 *
 * @since 2.4.0
 */
class AcceptanceTester extends \Codeception\Actor {

	use \_generated\AcceptanceTesterActions;

	/**
	 * Logs the user in as the admin.
	 *
	 * We'd like to save a snapshot of the session here so that we don't have to log
	 * in each time. However, that currently isn't working for some reason.
	 *
	 * @link https://github.com/Codeception/Codeception/issues/2900
	 * @link http://sqa.stackexchange.com/q/18244/18542
	 *
	 * @since 2.4.0
	 */
	public function amLoggedInAsAdmin() {
		$this->loginAsAdmin();
	}

	/**
	 * Logs the user in as an admin and redirects them to the given page.
	 *
	 * This saves a step by taking you directly to the desired page after logging in,
	 * instead of to the dashboard.
	 *
	 * @since 2.4.0
	 *
	 * @param string $page The page to redirect to after logging in.
	 */
	public function amLoggedInAsAdminOnPage( $page ) {

		$this->amOnPage(
			add_query_arg( 'redirect_to', rawurlencode( $page ), '/wp-login.php' )
		);
		$this->fillField( '#user_login', 'admin' );
		$this->fillField( '#user_pass', 'password' );
		$this->click( '#wp-submit' );
	}

	/**
	 * Wait for an element to become interactable.
	 *
	 * @since 2.4.0
	 *
	 * @param string $element The element that should become interactable.
	 * @param int    $timeout The number of seconds to wait before timing out.
	 */
	public function waitUntilElementInteractable( $element, $timeout = null ) {

		$I = $this;

		// Wait until the fields are actually interactive.
		// Attempting to set a field value immediately after creating the new
		// reaction  will result in an error: "Element is not currently interactable
		// and may not be manipulated."
		$I->waitForElementChange(
			$element
			, function ( \Facebook\WebDriver\WebDriverElement $element ) {

				try {

					// It should be OK that we clear this since this is a new
					// reaction and doesn't have a description yet.
					$element->clear();

				} catch ( \Facebook\WebDriver\Exception\InvalidElementStateException $e ) {

					codecept_debug(
						'Error while waiting for new reaction:' . $e->getMessage()
					);
				}

				return ! isset( $e );
			}
			, $timeout
		);
	}

	/**
	 * Wait for a new reaction to be displayed on the screen.
	 *
	 * @since 2.4.0
	 *
	 * @param string $context The context in which the reaction should appear.
	 * @param int    $timeout The number of seconds to wait before timing out.
	 */
	public function waitForNewReaction( $context = '', $timeout = null ) {

		$this->waitUntilElementInteractable(
			"{$context} .wordpoints-hook-reaction.new [name=description]"
			, $timeout
		);
	}

	/**
	 * Wait for a new rank to be displayed on the screen.
	 *
	 * @since 2.4.0
	 *
	 * @param string $context The context in which the rank should appear.
	 * @param int    $timeout The number of seconds to wait before timing out.
	 */
	public function waitForNewRank( $context = '', $timeout = null ) {

		$this->waitUntilElementInteractable(
			"{$context} .wordpoints-rank.new [name=name]"
			, $timeout
		);
	}

	/**
	 * Asserts that a success message is being displayed.
	 *
	 * @since 2.4.0
	 */
	public function seeSuccessMessage() {
		$this->seeElement( '.notice.updated' );
	}

	/**
	 * Assert that a dialog is displayed.
	 *
	 * @since 2.4.0
	 *
	 * @param string $title The title of the dialog.
	 */
	public function seeJQueryDialog( $title = null ) {

		$this->seeElement( '.ui-dialog' );

		if ( $title ) {
			$this->see( $title, '.ui-dialog-title' );
		}
	}

	/**
	 * Creates a points type in the database.
	 *
	 * @since 2.4.0
	 *
	 * @param array $settings Settings for this points type.
	 */
	public function hadCreatedAPointsType( array $settings = array() ) {

		if ( ! isset( $settings['name'] ) ) {
			$settings['name'] = 'Points';
		}

		wordpoints_add_points_type( $settings );
	}

	/**
	 * Asserts taht a points type exists in the database.
	 *
	 * @since 2.4.0
	 *
	 * @param string $slug The slug of the points type.
	 */
	public function canSeePointsTypeInDB( $slug ) {
		\PHPUnit_Framework_Assert::assertTrue( wordpoints_is_points_type( $slug ) );
	}

	/**
	 * Creates a points reaction in the database.
	 *
	 * @since 2.4.0
	 *
	 * @param array $settings Settings for the reaction.
	 *
	 * @return \WordPoints_Hook_ReactionI The hook reaction.
	 */
	public function hadCreatedAPointsReaction( array $settings = array() ) {

		$defaults = array(
			'event'       => 'user_register',
			'reactor'     => 'points',
			'points'      => 10,
			'points_type' => 'points',
			'target'      => array( 'user' ),
			'description' => 'Test description.',
			'log_text'    => 'Test log text.',
		);

		$settings = array_merge( $defaults, $settings );

		if ( ! wordpoints_is_points_type( $settings['points_type'] ) ) {
			$this->hadCreatedAPointsType(
				array( 'name' => $settings['points_type'] )
			);
		}

		return wordpoints_hooks()->get_reaction_store( 'points' )->create_reaction(
			$settings
		);
	}

	/**
	 * Asserts that a points reaction is in the database.
	 *
	 * @since 2.4.0
	 *
	 * @param int $reaction_id The ID of the reaction.
	 */
	public function canSeePointsReactionInDB( $reaction_id ) {

		\PHPUnit_Framework_Assert::assertInstanceOf(
			'WordPoints_Hook_ReactionI'
			, wordpoints_hooks()->get_reaction_store( 'points' )->get_reaction(
				$reaction_id
			)
		);
	}

	/**
	 * Asserts that a points reaction is not in the database.
	 *
	 * @since 2.5.0
	 *
	 * @param int $reaction_id The ID of the reaction.
	 */
	public function cantSeePointsReactionInDB( $reaction_id ) {

		\PHPUnit_Framework_Assert::assertFalse(
			wordpoints_hooks()->get_reaction_store( 'points' )->get_reaction(
				$reaction_id
			)
		);
	}

	/**
	 * Asserts that a condition for a points reaction is in the database.
	 *
	 * @since 2.5.0
	 *
	 * @param \WordPoints_Hook_ReactionI $reaction The reaction object.
	 */
	public function canSeePointsReactionConditionInDB( $reaction ) {

		\PHPUnit_Framework_Assert::assertNotEmpty(
			$reaction->get_meta( 'conditions' )
		);
	}

	/**
	 * Asserts that a condition for a points reaction is not the database.
	 *
	 * @since 2.5.0
	 *
	 * @param \WordPoints_Hook_ReactionI $reaction The reaction object.
	 */
	public function cantSeePointsReactionConditionInDB( $reaction ) {

		\PHPUnit_Framework_Assert::assertFalse(
			$reaction->get_meta( 'conditions' )
		);
	}

	/**
	 * Make this a site with some legacy points hooks disabled.
	 *
	 * @since 2.4.0
	 *
	 * @param array $hooks The list of hook handler ID bases to disable.
	 */
	public function haveSiteWithDisabledLegacyPointsHooks( $hooks ) {
		update_site_option(
			'wordpoints_legacy_points_hooks_disabled'
			, $hooks
		);
	}

	/**
	 * Activate a component.
	 *
	 * @since 2.4.0
	 *
	 * @param string $slug The component slug.
	 *
	 * @return bool Whether the component was activated successfully.
	 */
	public function hadActivatedComponent( $slug ) {
		return \WordPoints_Components::instance()->activate( $slug );
	}

	/**
	 * Activate a module.
	 *
	 * @since 2.5.0
	 *
	 * @param string $module       The module to activate.
	 * @param bool   $network_wide Whether to activate the module network-wide.
	 *
	 * @return void|\WP_Error An error object on failure.
	 */
	public function hadActivatedModule( $module, $network_wide = false ) {
		return wordpoints_activate_module( $module, '', $network_wide );
	}

	/**
	 * Install a test module on the site.
	 *
	 * @since 2.5.0
	 *
	 * @param string $module The module file or directory to symlink.
	 */
	public function haveTestModuleInstalled( $module ) {

		$modules_dir = wordpoints_modules_dir();
		$test_modules_dir = WORDPOINTS_DIR . '/../tests/phpunit/data/modules/';

		if ( ! file_exists( $modules_dir . $module ) ) {

			global $wp_filesystem;

			WP_Filesystem();

			$wp_filesystem->mkdir( $modules_dir . $module );

			copy_dir( $test_modules_dir . $module, $modules_dir . $module );
		}
	}
}

// EOF
