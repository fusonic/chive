<?php

/*
 * @todo(mburtscher): Seems to be unfinished ...
 */

class CharacterSetTest extends TestCase
{
	/**
	 * tests the characterset model
	 */
	public function testModel()
	{
		$char = CharacterSet::model()->findAll();

		$this->assertType('array',$char);

	}

	/**
	 * tests some Conifg
	 */
	public function testConfig()
	{
		$char = new CharacterSet();

		$this->assertType('string',$char->tableName());

		$this->assertType('string',$char->primaryKey());

		$this->assertType('array',$char->relations());

	}

}
