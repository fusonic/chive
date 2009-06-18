<?php

class CollationTest extends TestCase
{

	/**
	 * Load collation with properties and relations.
	 */
	public function testLoad()
	{
		// Load collation
		$col = Collation::model()->findByPk('utf8_general_ci');

		// Assert properties
		$this->assertEquals('utf8_general_ci', $col->COLLATION_NAME);
		$this->assertEquals('utf8', $col->CHARACTER_SET_NAME);
		$this->assertEquals(33, $col->ID);
		$this->assertEquals('Yes', $col->IS_DEFAULT);
		$this->assertEquals('Yes', $col->IS_COMPILED);
		$this->assertEquals(1, $col->SORTLEN);

		// Assert character set
		$this->assertType('CharacterSet', $col->characterSet);
	}
	
	/**
	 * Tests some Config
	 */
	public function testConfig()
	{
		//new Collation model
		$coll = new Collation();
		
		//check config
		$this->assertTrue(is_array($coll->relations()));
		$this->assertEquals('COLLATIONS',$coll->tableName());
		$this->assertEquals('COLLATION_NAME',$coll->primaryKey());
	}

	/**
	 * Tests to get collation definition for different collations.
	 */
	public function testGetDefinition()
	{
		// Get definition for utf8_unicode_ci
		$defCi = Collation::getDefinition('utf8_unicode_ci');
		$defCs = Collation::getDefinition('utf8_unicode_cs');
		$def = Collation::getDefinition('utf8_unicode');

		// Check type
		$this->assertType('string', $defCi);
		$this->assertType('string', $defCs);
		$this->assertType('string', $def);

		// Check case sensitivity
		$this->assertContains('(' . Yii::t('collation', 'ci') . ')', $defCi);
		$this->assertContains('(' . Yii::t('collation', 'cs') . ')', $defCs);
		$this->assertNotContains('(' . Yii::t('collation', 'ci') . ')', $def);
		$this->assertNotContains('(' . Yii::t('collation', 'cs') . ')', $def);
	}

	/**
	 * Tests to get character set name from collation name.
	 */
	public function testGetCharset()
	{
		// Get Charset for utf8_unicode_ci
		$this->assertEquals('utf8', Collation::getCharacterSet('utf8_unicode_ci'));
	}
}



?>