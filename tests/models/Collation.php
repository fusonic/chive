d
<?php

class CollationTest extends TestCase
{

	public function testTableName()
	{

		$coll = new Collation();
		$this->assertEquals('COLLATIONS',$coll->tableName());

	}

	public function testprimaryKey()
	{

		$coll = new Collation();

		$this->assertEquals('COLLATION_NAME',$coll->primaryKey());

	}

	public function testRelations()
	{

		$coll = new Collation();

		$this->assertTrue(is_array($coll->relations()));

	}

	public function testGetDefinition()
	{
		$coll = new Collation();
		$this->assertContains('utf8',$coll->getDefinition('utf8_unicode_ci'));
	}
}



?>