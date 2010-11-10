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


class StorageEngineTest extends CTestCase
{

	/**
	 * test whether getSupportedEngines returns an array
	 */
	public function testGetSupportedEngines()
	{
		$this->assertType('array', StorageEngine::getSupportedEngines());
	}

	/**
	 *
	 * tests whether getFormattedName formates the names correct
	 */
	public function testGetFormattedName()
	{
		$this->assertEquals('MyISAM', StorageEngine::getFormattedName('myisam'));
		$this->assertEquals('InnoDB', StorageEngine::getFormattedName('innodb'));
		$this->assertEquals('BLUB', StorageEngine::getFormattedName('blub'));
	}

	/**
	 * test whether the dbs got the right options
	 */
	public function testCheck()
	{
		$this->assertFalse(StorageEngine::check('myisam', StorageEngine::SUPPORTS_FOREIGN_KEYS));
		$this->assertTrue(StorageEngine::check('myisam', StorageEngine::SUPPORTS_DELAY_KEY_WRITE));
		$this->assertTrue(StorageEngine::check('myisam', StorageEngine::SUPPORTS_CHECKSUM));
		$this->assertTrue(StorageEngine::check('myisam', StorageEngine::SUPPORTS_PACK_KEYS));

		$this->assertTrue(StorageEngine::check('innodb', StorageEngine::SUPPORTS_FOREIGN_KEYS));
		$this->assertFalse(StorageEngine::check('innodb', StorageEngine::SUPPORTS_DELAY_KEY_WRITE));
		$this->assertFalse(StorageEngine::check('innodb', StorageEngine::SUPPORTS_CHECKSUM));
		$this->assertFalse(StorageEngine::check('innodb', StorageEngine::SUPPORTS_PACK_KEYS));

		$db_arr = array(
			'memory',
			'berkeleydb',
			'blackhole',
			'example',
			'archive',
			'csv',
			'ndbcluster',
			'federated',
			'mrg_myisam',
			'isam',	
		);

		foreach($db_arr as $db)
		{
			$this->assertFalse(StorageEngine::check($db, StorageEngine::SUPPORTS_FOREIGN_KEYS));
			$this->assertFalse(StorageEngine::check($db, StorageEngine::SUPPORTS_DELAY_KEY_WRITE));
			$this->assertFalse(StorageEngine::check($db, StorageEngine::SUPPORTS_CHECKSUM));
			$this->assertFalse(StorageEngine::check($db, StorageEngine::SUPPORTS_PACK_KEYS));
		}
	}


	/**
	 * tests some config
	 */
	public function testConfig()
	{
		$this->assertType('StorageEngine', StorageEngine::model());
		$this->assertType('array', StorageEngine::getPackKeyOptions());

		$model = StorageEngine::model();
		$this->assertType('array', $model->attributeNames());
	}

	/**
	 * tests to load an engine and checks the attributes
	 */
	public function testLoad()
	{
		$engines = array(
			'MyISAM',
			'MEMORY',
			'InnoDB',
			'BerkeleyDB',
			'BLACKHOLE',
			'EXAMPLE',
			'ARCHIVE',
			'CSV',
			'ndbcluster',
			'FEDERATED',
			'MRG_MYISAM',
			'ISAM'
		);

		foreach($engines as $engine)
		{
			$se = StorageEngine::model()->findAllByAttributes(array(
				'Engine' => $engine	
			));
			
			if(count($se) == 0)
				continue;
				
			$se = $se[0];
			
			$this->assertType('StorageEngine', $se);
			$this->assertType('string', $se->Comment);
			$this->assertType('string', $se->Support);
		}
	}

	/**
	 * tests the return values of the getSupports* methods
	 */
	public function testSupports()
	{
		$db_arr = array(
			'MEMORY',
			'BerkeleyDB',
			'BLACKHOLE',
			'EXAMPLE',
			'ARCHIVE',
			'CSV',
			'ndbcluster',
			'FEDERATED',
			'MRG_MYISAM',
			'ISAM',	
		);

		foreach($db_arr as $db)
		{
			$se = StorageEngine::model()->findAllByAttributes(array(
				'Engine' => $db	
			));
			
			if(count($se) == 0)
				continue;

			$this->assertFalse($se[0]->getSupportsChecksum());
			$this->assertFalse($se[0]->getSupportsPackKeys());
			$this->assertFalse($se[0]->getSupportsDelayKeyWrite());

		}

		$se = StorageEngine::model()->findAllByAttributes(array(
			'Engine' => 'MyISAM'	
		));

		$this->assertTrue($se[0]->getSupportsChecksum());
		$this->assertTrue($se[0]->getSupportsPackKeys());
		$this->assertTrue($se[0]->getSupportsDelayKeyWrite());

		$se = StorageEngine::model()->findAllByAttributes(array(
			'Engine' => 'InnoDB'	
		));

		$this->assertFalse($se[0]->getSupportsChecksum());
		$this->assertFalse($se[0]->getSupportsPackKeys());
		$this->assertFalse($se[0]->getSupportsDelayKeyWrite());
	}
	
}