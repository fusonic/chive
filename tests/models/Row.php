<?php

class RowTest extends TestCase
{

	public static $enabled = false;

	/**
	 * Setup test databases.
	 */
	protected function setUp()
	{
		$this->executeSqlFile('models/Row.sql');

		$_POST['schema'] = 'rowtest';
		$_POST['table'] = 'data';
	}

	/**
	 * Tests to read database information.
	 */
	public function testRead()
	{

		// Load schema
		$row = Row::model()->findAll();


		die($row);

		// Check properties
		$this->assertEquals('schematest2', $schema->SCHEMA_NAME);
		$this->assertEquals('latin7', $schema->DEFAULT_CHARACTER_SET_NAME);
		$this->assertEquals('latin7_general_cs', $schema->DEFAULT_COLLATION_NAME);
	}

	public function testInsert()
	{
		// Create new schema
		$schema = new Schema();

		// Set properties
		$schema->SCHEMA_NAME = 'schematest1';
		$schema->DEFAULT_COLLATION_NAME = 'latin1_swedish_ci';

		// Save
		$this->assertEquals(true, $schema->save());

		// Load again
		$schema = Schema::model()->findByPk('schematest1');

		// Check properties
		$this->assertEquals('schematest1', $schema->SCHEMA_NAME);
		$this->assertEquals('latin1', $schema->DEFAULT_CHARACTER_SET_NAME);
		$this->assertEquals('latin1_swedish_ci', $schema->DEFAULT_COLLATION_NAME);
	}

	public function testUpdate()
	{
		// Load schema
		$schema = Schema::model()->findByPk('schematest2');

		// Set properties
		$schema->DEFAULT_COLLATION_NAME = 'latin1_swedish_ci';

		// Save
		$this->assertEquals(true, $schema->save());

		// Load again
		$schema = Schema::model()->findByPk('schematest2');

		// Check properties
		$this->assertEquals('schematest2', $schema->SCHEMA_NAME);
		$this->assertEquals('latin1', $schema->DEFAULT_CHARACTER_SET_NAME);
		$this->assertEquals('latin1_swedish_ci', $schema->DEFAULT_COLLATION_NAME);
	}

	public function testDrop()
	{
		// Load schema
		$schema = Schema::model()->findByPk('schematest2');

		// Drop
		$this->assertEquals(true, $schema->delete());

		// Load again
		$this->assertEquals(null, Schema::model()->findByPk('schematest2'));
	}

}

?>