<?php

/**
 * This is global bootstrap for autoloading for the codeception tests.
 *
 * @package WordPoints\Codeception
 * @since 1.0.0
 */

Codeception\Util\Autoload::addNamespace(
	'WordPoints\Tests\Codeception'
	, __DIR__ . '/includes'
);

// Support lowercase "cept" suffixes for our test file names.
WordPoints\Tests\Codeception\TestLoader::support_lowercase_formats();

// EOF
