<?php

/**
 * Uninstall test case.
 *
 * @package WordPoints_My_Extension
 * @since 1.0.0
 */

/**
 * Tests uninstalling the extension.
 *
 * @since 1.0.0
 *
 * @covers WordPoints_My_Extension_Installable
 */
class WordPoints_My_Extension_Uninstall_Test
	extends WordPoints_PHPUnit_TestCase_Extension_Uninstall {

	/**
	 * Test installation and uninstallation.
	 *
	 * @since 1.0.0
	 */
	public function test_uninstall() {

		global $wpdb;

		/*
		 * First test that the extension installed itself properly.
		 */

		// Check that a database table was added.
		$this->assertTableExists( $wpdb->prefix . 'myextension_table' );

		// Check that an option was added to the database.
		$this->assertSame( 'default', get_option( 'myextension_option' ) );

		/*
		 * Now, test that it uninstalls itself properly.
		 */

		// You must call this to perform uninstallation.
		$this->uninstall();

		// Check that everything with this extension's prefix has been uninstalled.
		$this->assertUninstalledPrefix( 'myextension' );

		// Or, if we need to, we can also run more granular checks, like this:

		// Check that the table was deleted.
		$this->assertTableNotExists( $wpdb->prefix . 'myextension_table' );

		// Check that all options with a prefix was deleted.
		$this->assertNoOptionsWithPrefix( 'myextension' );

		// Same for usermeta and comment meta.
		$this->assertNoUserMetaWithPrefix( 'myextension' );
		$this->assertNoCommentMetaWithPrefix( 'myextension' );
	}
}

// EOF
