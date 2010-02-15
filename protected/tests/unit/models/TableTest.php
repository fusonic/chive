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


class TableTest extends ChiveTestCase
{

	/**
	 * Setup test databases.
	 */
	protected function setUp()
	{
		$this->executeSqlFile('models/TableTest.sql');
		
		Table::$db = $this->createDbConnection('tabletest');
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
		$this->assertNull($table);
	}

	/**
	 * Test truncating
	 */
	public function testTruncate()
	{
		// Load table definition
		$table = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => 'tabletest',
			'TABLE_NAME' => 'innodb',
		));

		// Drop table
		$table->truncate();

		// Reload
		$table->refresh();

		// Check if table is still there
		$this->assertEquals(0, $table->getRowCount());
		$this->assertEquals('-', $table->getAverageRowSize());
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
		$table->comment = 'mein testkommentar';
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
		$this->assertEquals('mein testkommentar', $table->comment);
		$this->assertEquals(1, $table->getRowCount());
		$this->assertEquals(20, $table->getAverageRowSize());

		// Set option pack_keys to 1
		$table->optionPackKeys = 1;
		$table->ENGINE = 'InnoDB';
		$table->save();

		// Load again
		$table = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => 'tabletest',
			'TABLE_NAME' => 'innodb2',
		));

		// Check properties
		$this->assertEquals(1, $table->optionPackKeys);
		$this->assertEquals('mein testkommentar', $table->comment);
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
	 * Checks whether supported index types are detected correctly.
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

	/**
	 * Tests some config functions.
	 */
	public function testConfigFunctions()
	{
		// Create new schema
		$table = new Table();

		// Check return types
		$this->assertTrue(is_array($table->attributeLabels()));
		$this->assertTrue(is_array($table->rules()));
		$this->assertTrue(is_array($table->relations()));
	}


	/**
	 * Test to create a Table
	 */
	public function testCreateTable()
	{
		$col1 = new Column();
		$col1->COLUMN_NAME = 'test1';

		$col1->setDataType('int');
		$col1->setAutoIncrement(true);
		$col1->setIsNullable(false);
		$col1->size=20;
		$col1->createPrimaryKey = true;

		$col2 = new Column();
		$col2->COLUMN_NAME = 'test2';

		$col2->setDataType('varchar');
		$col2->setCollation('utf8_general_ci');
		$col2->size=250;

		$columns = array($col1,$col2);

		$table = new Table();

		// Set some properties and save
		$table->TABLE_NAME = 'innodb2';
		$table->TABLE_SCHEMA = 'tabletest';
		$table->optionChecksum = 1;
		$table->optionDelayKeyWrite = 1;
		$table->optionPackKeys = 0;
		$table->ENGINE = 'MyISAM';
		$table->TABLE_COLLATION = 'utf8_general_ci';
		$table->comment = 'mein testkommentar';

		$table->columns = $columns;
		$table->insert();

		$this->assertTrue(is_string($table->showCreateTable));

		$pk = array(
		 'TABLE_SCHEMA' => 'tabletest',
		 'TABLE_NAME' => 'innodb2',
		 'COLUMN_NAME' => 'test1',
		);

		// Load column definition
		$col = Column::model()->findByPk($pk);

		$this->assertEquals('test1',$col->COLUMN_NAME);
		$this->assertEquals('int',$col->getDataType());
		$this->assertTrue($col->getAutoIncrement());
		$this->assertFalse($col->getIsNullable());
		$this->assertTrue($col->getIsPartOfPrimaryKey());
		$this->assertEquals(20,$col->size);

		$pk = array(
		 'TABLE_SCHEMA' => 'tabletest',
		 'TABLE_NAME' => 'innodb2',
		 'COLUMN_NAME' => 'test2',
		);

		// Load column definition
		$col = Column::model()->findByPk($pk);

		$this->assertEquals('test2',$col->COLUMN_NAME);
		$this->assertEquals('varchar',$col->getDataType());
		$this->assertFalse($col->getAutoIncrement());
		$this->assertFalse($col->getIsNullable());
		$this->assertFalse($col->getIsPartOfPrimaryKey());
		$this->assertEquals('utf8_general_ci',$col->getCollation());
		$this->assertEquals(250,$col->size);
	}

	/**
	 * Checks if table has Primarykey
	 */
	public function testHasPrimaryKeyFalse()
	{
		$table = array(
		 'TABLE_SCHEMA' => 'tabletest',
		 'TABLE_NAME' => 'tabletest3',
		);

		// Load column definition
		$ta = Table::model()->findByPk($table);

		$this->assertFalse($ta->getHasPrimaryKey());
	}

	/**
	 * Record can't be updated cause its not new
	 *
	 * @expectedException CDbException
	 */
	public function testUpdateException()
	{
		$table = new Table();
		$table->setAttribute('TABLE_NAME', 'test2');

		$table->update();
	}


	/**
	 * Record can't be inserted cause its not new
	 *
	 * @expectedException CDbException
	 */
	public function testInsertException1()
	{
		$col2 = new Column();
		$col2->COLUMN_NAME = 'test2';

		$col2->setDataType('varchar');
		$col2->setCollation('utf8_general_ci');
		$col2->size=250;


		$column = array($col2);

		$table = array(
		'TABLE_SCHEMA' => 'tabletest',
		'TABLE_NAME' => 'tabletest3',
		);

		// Load column definition
		$ta = Table::model()->findByPk($table);
		$ta->insert($column);
	}

}