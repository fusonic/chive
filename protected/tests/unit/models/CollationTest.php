<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2010 Fusonic GmbH
 *
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */


class CollationTest extends CTestCase
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