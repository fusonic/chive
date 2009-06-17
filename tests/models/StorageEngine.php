<?php


class StorageEngineTest extends TestCase
{

	public function setup()
	{

		$db = new CDbConnection('mysql:host='.DB_HOST.';dbname=information_schema', DB_USER, DB_PASSWORD);
		$db->charset='utf8';
		$db->active = true;
		
	}

	/**
	 *
	 * test whether getSupportedEngines returns an array
	 */
	public function testGetSupportedEngines()
	{

		$this->assertType('array',StorageEngine::getSupportedEngines());
	}

	/**
	 *
	 * tests whether getFormattedName formates the names correct
	 */
	public function testGetFormattedName()
	{

		$this->assertEquals('MyISAM',StorageEngine::getFormattedName('myisam'));
		$this->assertEquals('InnoDB',StorageEngine::getFormattedName('innodb'));
		$this->assertEquals('BLUB',StorageEngine::getFormattedName('blub'));
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
		'isam'	
		);

		foreach($db_arr as $db)
		{
			$this->assertFalse(StorageEngine::check($db, StorageEngine::SUPPORTS_FOREIGN_KEYS));
			$this->assertFalse(StorageEngine::check($db, StorageEngine::SUPPORTS_DELAY_KEY_WRITE));
			$this->assertFalse(StorageEngine::check($db, StorageEngine::SUPPORTS_CHECKSUM));
			$this->assertFalse(StorageEngine::check($db, StorageEngine::SUPPORTS_PACK_KEYS));
		}
	}

	public function testGetPackKeyOptions()
	{
		$this->assertType('array',StorageEngine::getPackKeyOptions());
	}


	public function testConifg()
	{
		$this->assertType('array',StorageEngine::attributeNames());
		$this->assertType('array',StorageEngine::model()->findAll());
	}

	/*

	public function testSupports()
	{

	
		$se = StorageEngine::model()->findAllByAttributes(array(
			'Engine' => 'MyISAM'	
		));

		var_dump($se->getSupportsPackKeys());

	}
*/
}




?>