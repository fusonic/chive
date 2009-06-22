<?php

class CharacterSetTest extends TestCase
{
	
	public function testLoad()
	{
		// Load character set
		$cs = CharacterSet::model()->findByPk('big5');
		
		// Assert properties
		$this->assertEquals('big5', $cs->CHARACTER_SET_NAME);
		$this->assertEquals('big5_chinese_ci', $cs->DEFAULT_COLLATE_NAME);
		$this->assertEquals('Big5 Traditional Chinese', $cs->DESCRIPTION);
		$this->assertEquals(2, $cs->MAXLEN);
	}

	/**
	 * tests some Conifg
	 */
	public function testConfig()
	{
		$char = CharacterSet::model();
		$this->assertType('string', $char->tableName());
		$this->assertType('string', $char->primaryKey());
		$this->assertType('array', $char->relations());
	}

}