<?php

/**
 * Class Has_Annotation
 *
 * @since 1.0.0
 *
 * @covers Something
 */
class Has_Annotation {

	/**
	 * A test.
	 *
	 * @since 1.0.0
	 *
	 * @covers Something::tt
	 */
	public function test_has_annotation() {} // OK

	/**
	 * A test.
	 *
	 * @since 1.0.0
	 *
	 * @coversNothing
	 */
	public function test_has_nothing_annotation() {} // OK

	/**
	 * A test.
	 *
	 * @since 1.0.0
	 */
	public function test_no_annotation() {} // OK

	public function test_no_doc_comment() {} // OK
	public function not_a_test() {} // OK
}

/**
 * Class Has_Nothing_Annotation
 *
 * @since 1.0.0
 *
 * @coversNothing
 */
class Has_Nothing_Annotation {

	/**
	 * A test.
	 *
	 * @since 1.0.0
	 *
	 * @covers Something::tt
	 */
	public function test_has_annotation() {} // OK

	/**
	 * A test.
	 *
	 * @since 1.0.0
	 *
	 * @coversNothing
	 */
	public function test_has_nothing_annotation() {} // OK

	/**
	 * A test.
	 *
	 * @since 1.0.0
	 */
	public function test_no_annotation() {} // OK

	public function test_no_doc_comment() {} // OK
	public function not_a_test() {} // OK
}

/**
 * Class Has_No_Annotation
 *
 * @since 1.0.0
 */
class Has_No_Annotation {

	/**
	 * A test.
	 *
	 * @since 1.0.0
	 *
	 * @covers Something::tt
	 */
	public function test_has_annotation() {} // OK

	/**
	 * A test.
	 *
	 * @since 1.0.0
	 *
	 * @coversNothing
	 */
	public function test_has_nothing_annotation() {} // OK

	/**
	 * A test.
	 *
	 * @since 1.0.0
	 */
	public function test_no_annotation() {} // Bad

	public function test_no_doc_comment() {} // Bad
	public function not_a_test() {} // OK
}

class Has_No_Doc_Comment {

	/**
	 * A test.
	 *
	 * @since 1.0.0
	 *
	 * @covers Something::tt
	 */
	public function test_has_annotation() {} // OK

	/**
	 * A test.
	 *
	 * @since 1.0.0
	 *
	 * @coversNothing
	 */
	public function test_has_nothing_annotation() {} // OK

	/**
	 * A test.
	 *
	 * @since 1.0.0
	 */
	public function test_no_annotation() {} // Bad

	public function test_no_doc_comment() {} // Bad
	public function not_a_test() {} // OK
}

/**
 * Not a test.
 *
 * @since 1.0.0
 */
function test_not_in_class() {} // OK
