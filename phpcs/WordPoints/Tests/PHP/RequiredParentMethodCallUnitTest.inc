<?php

// Test abstract method.
abstract class Test_A {
	abstract public function setUp();
	public function other() {}
}

// Test missing.
class Test_B extends Test_A {
	public function setUp() {
		parent::other();
	}
}

// Test correct.
class Test_C extends Test_A {
	public function setUp() {
		parent::setUp();
	}
}
