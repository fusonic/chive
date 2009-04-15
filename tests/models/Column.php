<?php

class ColumnTest extends TestCase
{

	/**
	 * Setup test databases.
	 */
	protected function setUp()
	{
		$this->executeSqlFile('models/Column.sql');
		Column::$db = new CDbConnection('mysql:host='.DB_HOST.';dbname=columntest', DB_USER, DB_PASSWORD);
		Column::$db->charset='utf8';
		Column::$db->active = true;
	}

	/**
	 * Test loading
	 */
	public function testLoad()
	{
		// Define primary key for column
		$pk = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test1',
		);

		// Load column definition
		$col1 = Column::model()->findByPk($pk);

		// Check if result is of type Column
		$this->assertEquals(true, $col1 instanceof Column);

		// Set PK to a column that doesn't exist
		$pk['COLUMN_NAME'] = 'test10';

		// Load definition
		$col2 = Column::model()->findByPk($pk);

		// Check if result is null
		$this->assertEquals(true, is_null($col2));
	}

	/**
	 * Test dropping
	 */
	public function testDrop()
	{
		// Load table definition
		$table = Table::model()->findByPk(array('TABLE_SCHEMA' => 'columntest', 'TABLE_NAME' => 'test'));

		// Save column count
		$columnCount = count($table->columns);

		// Drop first column
		$col = $table->columns[0];
		$col->delete();

		// Refresh table definition
		$table->refresh();

		// Check column count
		$this->assertEquals($columnCount - 1, count($table->columns));
	}

	/**
	 * Test move
	 */
	public function testMove()
	{
		// Define primary key for column
		$pk = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test5',
		);

		// Load column definition
		$col = Column::model()->findByPk($pk);

		// Check original position
		$this->assertEquals(5, $col->ORDINAL_POSITION);

		// Move column one foreward
		$col->move('AFTER test3');

		// Reload column definition
		$col->refresh();

		// Check new position
		$this->assertEquals(4, $col->ORDINAL_POSITION);

		// Move column to first position
		$col->move('FIRST');

		// Reload column definition
		$col->refresh();

		// Check position
		$this->assertEquals(1, $col->ORDINAL_POSITION);

	}

	/**
	 * Tests if primary key information is correct.
	 */
	public function testPrimaryKey()
	{
		// Define primary key for column
		$pk = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test5',
		);

		// Load column definition
		$col = Column::model()->findByPk($pk);

		// Check primary key information
		$this->assertEquals(false, $col->getIsPartOfPrimaryKey());

		// Change column PK (first primary key column)
		$pk['COLUMN_NAME'] = 'test1';

		// Load column definition
		$col = Column::model()->findByPk($pk);

		// Check primary key information
		$this->assertEquals(true, $col->getIsPartOfPrimaryKey());

		// Change column PK (second primary key column)
		$pk['COLUMN_NAME'] = 'test2';

		// Load column definition
		$col = Column::model()->findByPk($pk);

		// Check primary key information
		$this->assertEquals(true, $col->getIsPartOfPrimaryKey());

	}

}

?>