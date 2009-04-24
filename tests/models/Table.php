<?php

class TableTest extends TestCase
{

	/**
	 * Setup test databases.
	 */
	protected function setUp()
	{
		$this->executeSqlFile('models/Table.sql');
		Table::$db = new CDbConnection('mysql:host='.DB_HOST.';dbname=tabletest', DB_USER, DB_PASSWORD);
		Table::$db->charset='utf8';
		Table::$db->active = true;
	}

	/**
	 * Test loading
	 */
	public function testLoad()
	{
		// Load table definition
		$table1 = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => 'tabletest',
			'TABLE_NAME' => 'innodb',
		));

		// Check if result is of type Table
		$this->assertEquals(true, $table1 instanceof Table);

		// Check properties
		$this->assertEquals('innodb', $table1->TABLE_NAME);
		$this->assertEquals('tabletest', $table1->TABLE_SCHEMA);
		$this->assertEquals('InnoDB', $table1->ENGINE);
		$this->assertEquals('0', $table1->optionChecksum);
		$this->assertEquals('0', $table1->optionDelayKeyWrite);
		$this->assertEquals('DEFAULT', $table1->optionPackKeys);
		$this->assertEquals(true, $table1->getHasPrimaryKey());

		// Load table definition which doesn't exist
		$table2 = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => 'tabletest',
			'TABLE_NAME' => 'notthere',
		));

		// Check if result is null
		$this->assertEquals(null, $table2);
	}

	/**
	 * Test dropping
	 */
	public function testDrop()
	{
		// Load table definition
		$table = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => 'tabletest',
			'TABLE_NAME' => 'innodb',
		));

		// Drop table
		$table->delete();

		// Reload table definition
		$table = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => 'tabletest',
			'TABLE_NAME' => 'innodb',
		));

		// Check if table is still there
		$this->assertEquals(null, $table);
	}

	/**
	 * Test updating table properties
	 */
	public function testUpdate()
	{
		// Load table definition
		$table = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => 'tabletest',
			'TABLE_NAME' => 'innodb',
		));

		// Save without changing something
		$table->save();

		// Set some properties and save
		$table->TABLE_NAME = 'innodb2';
		$table->optionChecksum = 1;
		$table->optionDelayKeyWrite = 1;
		$table->optionPackKeys = 0;
		$table->ENGINE = 'MyISAM';
		$table->TABLE_COLLATION = 'utf8_general_ci';
		$table->TABLE_COMMENT = 'mein testkommentar';
		$table->save();

		// Load again
		$table = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => 'tabletest',
			'TABLE_NAME' => 'innodb2',
		));

		// Check properties
		$this->assertEquals('innodb2', $table->TABLE_NAME);
		$this->assertEquals('1', $table->optionChecksum);
		$this->assertEquals('1', $table->optionDelayKeyWrite);
		$this->assertEquals('0', $table->optionPackKeys);
		$this->assertEquals('MyISAM', $table->ENGINE);
		$this->assertEquals('utf8_general_ci', $table->TABLE_COLLATION);
		// This test will fail due to a innodb issue!!!
		$this->assertEquals('mein testkommentar', $table->TABLE_COMMENT);
	}

	/**
	 * Test renaming table to a table that already exists
	 */
	public function testUpdateNameExists()
	{
		// Load table definition
		$table = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => 'tabletest',
			'TABLE_NAME' => 'innodb',
		));

		// Set name and save
		$table->TABLE_NAME = 'myisam';

		// Saving must fail
		$this->assertEquals(false, $table->save());
	}

	/**
	 * Checks wether supported index types are detected correctly.
	 */
	public function testIndexTypes()
	{
		// Load table definition
		$innodb = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => 'tabletest',
			'TABLE_NAME' => 'innodb',
		));

		// Check types
		$types = $innodb->getSupportedIndexTypes();
		$this->assertEquals(true, isset($types['PRIMARY']));
		$this->assertEquals(true, isset($types['INDEX']));
		$this->assertEquals(true, isset($types['UNIQUE']));
		$this->assertEquals(false, isset($types['FULLTEXT']));

		// Load table definition
		$myisam = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => 'tabletest',
			'TABLE_NAME' => 'myisam',
		));

		// Check types
		$types = $myisam->getSupportedIndexTypes();
		$this->assertEquals(true, isset($types['PRIMARY']));
		$this->assertEquals(true, isset($types['INDEX']));
		$this->assertEquals(true, isset($types['UNIQUE']));
		$this->assertEquals(true, isset($types['FULLTEXT']));
	}

}

?>