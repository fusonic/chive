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


class CharacterSetTest extends CTestCase
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