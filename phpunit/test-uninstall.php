<?php

/**
 * Uninstall test case.
 *
 * @package WordPoints_My_Module
 * @since 1.0.0
 */

/**
 * Tests uninstalling the module.
 *
 * @since 1.0.0
 */
class My_Module_Uninstall_Test
	extends WordPoints_PHPUnit_TestCase_Module_Uninstall {

	/**
	 * Test installation and uninstallation.
	 *
	 * @since 1.0.0
	 */
	public function test_uninstall() {

		global $wpdb;

		/*
		 * First test that the module installed itself properly.
		 */

		// Check that a database table was added.
		$this->assertTableExists( $wpdb->prefix . 'mymodule_table' );

		// Check that an option was added to the database.
		$this->assertSame( 'default', get_option( 'mymodule_option' ) );

		/*
		 * Now, test that it uninstalls itself properly.
		 */

		// You must call this to perform uninstallation.
		$this->uninstall();

		// Check that everything with this module's prefix has been uninstalled.
		$this->assertUninstalledPrefix( 'mymodule' );

		// Or, if we need to, we can also run more granular checks, like this:

		// Check that the table was deleted.
		$this->assertTableNotExists( $wpdb->prefix . 'mymodule_table' );

		// Check that all options with a prefix was deleted.
		$this->assertNoOptionsWithPrefix( 'mymodule' );

		// Same for usermeta and comment meta.
		$this->assertNoUserMetaWithPrefix( 'mymodule' );
		$this->assertNoCommentMetaWithPrefix( 'mymodule' );
	}
}

// EOF
