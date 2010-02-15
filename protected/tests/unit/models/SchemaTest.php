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


class SchemaTest extends ChiveTestCase
{

	protected function setUp()
	{
		$this->executeSqlFile('models/SchemaTest.sql');
		
		Schema::$db = $this->createDbConnection('information_schema');
	}

	/*
	 * Tests to read database information.
	 */
	public function testRead()
	{
		// Load schema
		$schema = Schema::model()->findByPk('schematest2');

		// Check properties
		$this->assertEquals('schematest2', $schema->SCHEMA_NAME);
		$this->assertEquals('latin7', $schema->DEFAULT_CHARACTER_SET_NAME);
		$this->assertEquals('latin7_general_cs', $schema->DEFAULT_COLLATION_NAME);
	}

	/*
	 * Tests to create a new database.
	 */
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

	/*
	 * Tests to fail inserting.
	 */
	public function testInsertFails()
	{
		// Create new schema
		$schema = new Schema();

		// Set properties
		$schema->SCHEMA_NAME = 'schematest2';

		// Try to insert, this cannot work
		$schema->save();

		// Check if schema has errors
		$this->assertEquals(true, $schema->hasErrors());
	}

	/**
	 * Tests to insert a database which is not new.
	 *
	 * @expectedException CDbException
	 */
	public function testInsertExisting()
	{
		// Load schema
		$schema = Schema::model()->findByPk('schematest2');

		// Call insert instead of update -> Exception should be thrown.
		$schema->insert();
	}

	/**
	 * Tests to fail updating.
	 */
	public function testUpdateFails()
	{
		// Create new schema
		$schema = new Schema(array('SCHEMA_NAME' => 'schematest1'));

		// Save schema
		$schema->save();

		// Change schema charset to something non-existing
		$schema->DEFAULT_COLLATION_NAME = md5(microtime());

		// Try to save
		$schema->save();

		// Schema should have errors
		$this->assertEquals(true, $schema->hasErrors());
	}

	/**
	 * Tests to update a database which is new.
	 *
	 * @expectedException CDbException
	 */
	public function testUpdateNew()
	{
		// Create new schema
		$schema = new Schema(array('SCHEMA_NAME' => 'schematest1'));

		// Call update instead of save/insert -> Exception should be thrown.
		$schema->update();
	}

	/**
	 * Tests to delete a database which is new.
	 *
	 * @expectedException CDbException
	 */
	public function testDeleteNew()
	{
		// Create new schema
		$schema = new Schema(array('SCHEMA_NAME' => 'schematest1'));

		// Call delete -> Exception should be thrown.
		$schema->delete();
	}

	/**
	 * Tests some config functions.
	 */
	public function testConfigFunctions()
	{
		// Create new schema
		$schema = new Schema();

		// Check return types
		$this->assertEquals(true, is_array($schema->attributeLabels()));
		$this->assertEquals(true, is_array($schema->rules()));
		$this->assertEquals(true, is_array($schema->relations()));
	}

	/**
	 * Tests to update a database.
	 */
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

	/**
	 * Tests to drop a database.
	 */
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