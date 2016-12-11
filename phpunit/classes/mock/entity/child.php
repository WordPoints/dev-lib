<?php

/**
 * Mock entity child class for the PHPUnit tests.
 *
 * @package WordPoints_Dev_Lib\PHPUnit
 * @since 2.6.0
 */

/**
 * Mock entity child class for the PHPUnit tests.
 *
 * @since 2.6.0
 */
class WordPoints_PHPUnit_Mock_Entity_Child
	extends WordPoints_PHPUnit_Mock_Entityish
	implements WordPoints_Entity_ChildI {

	/**
	 * Set the value of the child from an entity.
	 *
	 * @since 2.6.0
	 *
	 * @param WordPoints_Entity $entity The entity.
	 */
	public function set_the_value_from_entity( WordPoints_Entity $entity ) {
		$this->set_the_value( $entity->get_the_value() );
	}
}

// EOF
