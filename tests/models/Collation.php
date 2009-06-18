<?php

/*
 * @todo(mburtscher): Seems to be unfinished ...
 */

class CollationTest extends TestCase
{

	public function testTableName()
	{

		/*
		 * @todo(mburtscher): Move all config things to one test!
		 */

		$coll = new Collation();
		$this->assertEquals('COLLATIONS',$coll->tableName());

	}
	
	public function testModel()
	{
		$coll = Collation::Model()->findAll();
		
		$this->assertType('array',$coll);
		
	}

	public function testprimaryKey()
	{

		/*
		 * @todo(mburtscher): Move all config things to one test!
		 */

		$coll = new Collation();

		$this->assertEquals('COLLATION_NAME',$coll->primaryKey());

	}

	public function testRelations()
	{

		/*
		 * @todo(mburtscher): Move all config things to one test!
		 */
		
		$coll = new Collation();

		$this->assertTrue(is_array($coll->relations()));

	}

	public function testGetDefinition()
	{
		/*
		 * @todo(mburtscher): Collation::getDefinition() is static! Don't call
		 * 	it on an instantiated object!
		 * @todo(mburtscher): Test if definiton for *_ci collations contains
		 * 	case-insensitive part.
		 */
		$coll = new Collation();
		$this->assertContains('utf8',$coll->getDefinition('utf8_unicode_ci'));
	}
}



?>