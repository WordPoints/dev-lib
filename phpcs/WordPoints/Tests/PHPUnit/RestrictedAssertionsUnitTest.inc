<?php

function assertEquals() {} // OK
function assertNotEquals() {} // OK

PHPUnit_Framework_TestCase::assertEquals( 'a', 'b' ); // Bad

$this->assertEquals(); // Bad
$this->assertEmpty(); // Bad
$this->assertEqualFields(); // Bad
$this->assertEqualSets(); // Bad
$this->assertEqualSetsWithIndex(); // Bad
$this->assertContains(); // Bad
$this->assertAttributeContains(); // Bad

// EOF
