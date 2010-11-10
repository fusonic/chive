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


class IndexTest extends ChiveTestCase
{

	/**
	 * Setup test databases.
	 */
	protected function setUp()
	{
		$this->executeSqlFile('models/IndexTest.sql');

		Table::$db =
		Index::$db = $this->createDbConnection('indextest');
	}

	/**
	 * Loads indices of test table.
	 */
	public function testLoad()
	{
		// Load table
		$table = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => 'indextest',
			'TABLE_NAME' => 'table1',
		));

		// Check index count
		$this->assertEquals(4, count($table->indices));

		// Check index 1
		$index = $table->indices[0];
		$this->assertEquals($table->TABLE_NAME, $index->table->TABLE_NAME);
		$this->assertEquals('PRIMARY', $index->INDEX_NAME);
		$this->assertEquals('PRIMARY', $index->getType());

		// Check index 2
		$index = $table->indices[1];
		$this->assertEquals('unique', $index->INDEX_NAME);
		$this->assertEquals('UNIQUE', $index->getType());

		// Check index 2
		$index = $table->indices[2];
		$this->assertEquals('index', $index->INDEX_NAME);
		$this->assertEquals('INDEX', $index->getType());

		// Check index 2
		$index = $table->indices[3];
		$this->assertEquals('fulltext', $index->INDEX_NAME);
		$this->assertEquals('FULLTEXT', $index->getType());
	}

	/**
	 * Checks deletion of indices.
	 */
	public function testDelete()
	{
		// Load table
		$table = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => 'indextest',
			'TABLE_NAME' => 'table2',
		));

		// Delete all indices
		foreach($table->indices AS $index)
		{
			$this->assertNotSame(false, $index->delete());
		}

		// Reload table
		$table->refresh();

		// Check index count
		$this->assertEquals(0, count($table->indices));
	}

	/**
	 * Checks what happens when deleting auto_increment primary key.
	 *
	 * @expectedException DbException
	 */
	public function testDeleteAutoIncrement()
	{
		// Load table
		$table = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => 'indextest',
			'TABLE_NAME' => 'table1',
		));
		
		// Delete key
		$index = $table->indices[0];
		$res = $index->delete();

		// Check result
		$this->assertEquals(false, $res);

		// Set throwExceptions and try again
		$index->throwExceptions = true;
		$index->delete();
	}

	/**
	 * Test update type/name/columns.
	 */
	public function testUpdate()
	{
		// Load index
		$index = Index::model()->findByAttributes(array(
			'INDEX_NAME' => 'PRIMARY',
			'TABLE_NAME' => 'table2',
			'TABLE_SCHEMA' => 'indextest',
		));
		$index->throwExceptions = true;

		// Change properties
		$index->INDEX_NAME = 'newname';
		$index->setType('UNIQUE');

		// Add new column
		$columns = $index->columns;
		$col = new IndexColumn();
		$col->COLUMN_NAME = 'pk';
		$columns[] = $col;
		$col = new IndexColumn();
		$col->COLUMN_NAME = 'varchar';
		$col->SUB_PART = 10;
		$columns[] = $col;
		$index->columns = $columns;

		// Try saving
		$index->save();

		// Reload index and load index columns
		$index->refresh();
		$cols = IndexColumn::model()->findAllByAttributes(array('TABLE_SCHEMA' => $index->TABLE_SCHEMA, 'TABLE_NAME' => $index->TABLE_NAME, 'INDEX_NAME' => $index->INDEX_NAME));

		// Check properties
		$this->assertEquals(2, count($cols));
		$this->assertEquals('newname', $index->INDEX_NAME);
		$this->assertEquals('UNIQUE', $index->getType());
		$this->assertNull($cols[0]->SUB_PART);
		$this->assertEquals(10, $cols[1]->SUB_PART);

		// Create PRIMARY
		$index = new Index();
		$index->TABLE_NAME = 'table2';
		$index->TABLE_SCHEMA = 'indextest';
		$index->INDEX_NAME = 'PRIMARY';
		$index->setType('PRIMARY');
		$index->columns = $columns;
		$index->save();

		// Refresh
		$index->refresh();

		// Check
		$this->assertEquals('PRIMARY', $index->INDEX_NAME);
		$this->assertEquals('PRIMARY', $index->getType());
	}

	/**
	 * Tests to update a new record
	 *
	 * @expectedException CException
	 */
	public function testUpdateNew()
	{
		$index = new Index();
		$index->update();
	}

	/**
	 * Tests to delete a new record
	 *
	 * @expectedException CException
	 */
	public function testDeleteNew()
	{
		$index = new Index();
		$index->delete();
	}

	/**
	 * Tests to insert a existing record.
	 *
	 * @expectedException CException
	 */
	public function testInsertExisting()
	{
		$index = Index::model()->findByAttributes(array(
			'INDEX_NAME' => 'index',
			'TABLE_NAME' => 'table2',
			'TABLE_SCHEMA' => 'indextest',
		));
		$index->insert();
	}

	public function testCreate()
	{
		// Create index
		$index = new Index();
		$index->TABLE_NAME = 'table2';
		$index->TABLE_SCHEMA = 'indextest';
		$index->INDEX_NAME = 'newname';
		$index->setType('UNIQUE');

		// Add new column
		$columns = $index->columns;
		$col = new IndexColumn();
		$col->COLUMN_NAME = 'pk';
		$columns[] = $col;
		$col = new IndexColumn();
		$col->COLUMN_NAME = 'varchar';
		$col->SUB_PART = 10;
		$columns[] = $col;
		$index->columns = $columns;

		// Try saving
		$index->save();

		// Reload index and load index columns
		$index->refresh();
		$cols = IndexColumn::model()->findAllByAttributes(array('TABLE_SCHEMA' => $index->TABLE_SCHEMA, 'TABLE_NAME' => $index->TABLE_NAME, 'INDEX_NAME' => $index->INDEX_NAME));

		// Check properties
		$this->assertEquals(2, count($cols));
		$this->assertEquals('newname', $index->INDEX_NAME);
		$this->assertEquals('UNIQUE', $index->getType());
		$this->assertNull($cols[0]->SUB_PART);
		$this->assertEquals(10, $cols[1]->SUB_PART);
	}

	/**
	 * Tests some config functions.
	 */
	public function testConfigFunctions()
	{
		// Create new schema
		$index = new Index();

		// Check return types
		$this->assertTrue(is_array($index->attributeLabels()));
		$this->assertTrue(is_array($index->rules()));
		$this->assertTrue(is_array($index->relations()));
		$this->assertTrue(is_array(Index::getIndexTypes()));
	}


	/**
	 * Tests to add an Index to a Column with Type Fulltext
	 */

	public function testSetTypeFullText()
	{

		// Create index
		$index = new Index();
		$index->TABLE_NAME = 'table2';
		$index->TABLE_SCHEMA = 'indextest';
		$index->INDEX_NAME = 'newname';
		$index->setType('FULLTEXT');

		// Add new column
		$columns = $index->columns;
		$col = new IndexColumn();
		$col->COLUMN_NAME = 'pk';
		$columns[] = $col;
		$col = new IndexColumn();
		$col->COLUMN_NAME = 'varchar';
		$col->SUB_PART = 10;
		$columns[] = $col;
		$index->columns = $columns;

		// Try saving
		$index->save();

		// Reload index and load index columns
		$index->refresh();
		$cols = IndexColumn::model()->findAllByAttributes(
		array(
		'TABLE_SCHEMA' => $index->TABLE_SCHEMA,
		 'TABLE_NAME' => $index->TABLE_NAME, 
		 'INDEX_NAME' => $index->INDEX_NAME
		));

		// Check properties
		$this->assertEquals('newname', $index->INDEX_NAME);
		$this->assertEquals('FULLTEXT', $index->getType());

	}



	public function testUpdatePrimaryKey()
	{
		// Load index
		$index = Index::model()->findByAttributes(array(
			'INDEX_NAME' => 'UNIQUE',
			'TABLE_NAME' => 'table4',
			'TABLE_SCHEMA' => 'indextest',
		));
		$index->setType('PRIMARY');

		// Try saving
		$index->save();

		// Reload index and load index columns
		$index->refresh();


		// Check properties
		$this->assertEquals('PRIMARY', $index->INDEX_NAME);
		$this->assertEquals('PRIMARY', $index->getType());
	}

}