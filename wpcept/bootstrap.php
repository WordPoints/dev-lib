<?php

/**
 * This is global bootstrap for autoloading for the codeception tests.
 *
 * @package WordPoints\Codeception
 * @since 2.4.0
 */

Codeception\Util\Autoload::addNamespace(
	'WordPoints\Tests\Codeception'
	, __DIR__ . '/includes'
);

// Support lowercase "cept" suffixes for our test file names.
WordPoints\Tests\Codeception\TestLoader::support_lowercase_formats();

/**
 * WordPoints bootstrap loader class.
 *
 * Used to load WordPress and any plugins and WordPoints extensions.
 *
 * @since 2.7.0
 */
require_once __DIR__ . '/../../dev-lib/phpunit/classes/bootstrap/loader.php';

// EOF
