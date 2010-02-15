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


class ForeignKeyTest extends ChiveTestCase
{
	/**
	 * Setup test databases.
	 */
	protected function setUp()
	{
		$this->executeSqlFile('models/ForeignKeyTest.sql');

		Column::$db =
		ForeignKey::$db =
		Index::$db =
		Routine::$db =
		Row::$db =
		Schema::$db =
		Table::$db =
		Trigger::$db =
		View::$db = $this->createDbConnection('tabletest');
	}

	/**
	 * Tests some config
	 */
	public function testConfig()
	{
		$fk = new ForeignKey();

		$this->assertType('string',$fk->tableName());
		$this->assertType('array',$fk->primaryKey());
	}

	/**
	 * tries to delete a Foreignkey
	 */
	public function testDelete()
	{
		$fk = ForeignKey::model()->findBySql('SELECT * FROM KEY_COLUMN_USAGE '
		. 'WHERE TABLE_SCHEMA = :tableSchema '
		. 'AND TABLE_NAME = :tableName '
		. 'AND COLUMN_NAME = :columnName '
		. 'AND REFERENCED_TABLE_SCHEMA IS NOT NULL', array(
			'tableSchema' => 'tabletest',
			'tableName' => 'product_order',
			'columnName' => 'customer_id'));

		$this->assertNotNull($fk);

		$this->assertType('string', $fk->delete());

		$fk = ForeignKey::model()->findBySql('SELECT * FROM KEY_COLUMN_USAGE '
		. 'WHERE TABLE_SCHEMA = :tableSchema '
		. 'AND TABLE_NAME = :tableName '
		. 'AND COLUMN_NAME = :columnName '
		. 'AND REFERENCED_TABLE_SCHEMA IS NOT NULL', array(
			'tableSchema' => 'tabletest',
			'tableName' => 'product_order',
			'columnName' => 'customer_id'));

		$this->assertNull($fk);
	}


	/**
	 * tries to insert a Foreignkey with Datatype int and varchar
	 */
	public function testOnUDRestrict()
	{
		$foreignKey = new ForeignKey();
		$foreignKey->TABLE_SCHEMA = 'tabletest';
		$foreignKey->TABLE_NAME = 'product';
		$foreignKey->COLUMN_NAME = 'fk';
		$foreignKey->setReferences('tabletest.product_order.no');

		$foreignKey->onUpdate = 'CASCADE';
		$foreignKey->onDelete = 'RESTRICT';

		$this->assertType('string', $foreignKey->save());

		$fk = ForeignKey::model()->findBySql('SELECT * FROM KEY_COLUMN_USAGE '
		. 'WHERE TABLE_SCHEMA = :tableSchema '
		. 'AND TABLE_NAME = :tableName '
		. 'AND COLUMN_NAME = :columnName '
		. 'AND REFERENCED_TABLE_SCHEMA IS NOT NULL', array(
			'tableSchema' => 'tabletest',
			'tableName' => 'product',
			'columnName' => 'fk'));

		$this->assertNotNull($fk);
		$this->assertEquals('tabletest.product_order.no',$fk->getReferences());
		$this->assertEquals('CASCADE', $fk->onUpdate);
		$this->assertEquals('', $fk->onDelete);
	}

	/**
	 * tries to insert a Foreignkey with Datatype int and varchar
	 */
	public function testInsert()
	{
		$foreignKey = new ForeignKey();
		$foreignKey->TABLE_SCHEMA = 'tabletest';
		$foreignKey->TABLE_NAME = 'product';
		$foreignKey->COLUMN_NAME = 'fk';
		$foreignKey->setReferences('tabletest.product_order.no');

		$foreignKey->onUpdate = 'CASCADE';
		$foreignKey->onDelete = 'NO ACTION';

		$this->assertType('string', $foreignKey->save());

		$fk = ForeignKey::model()->findBySql('SELECT * FROM KEY_COLUMN_USAGE '
		. 'WHERE TABLE_SCHEMA = :tableSchema '
		. 'AND TABLE_NAME = :tableName '
		. 'AND COLUMN_NAME = :columnName '
		. 'AND REFERENCED_TABLE_SCHEMA IS NOT NULL', array(
			'tableSchema' => 'tabletest',
			'tableName' => 'product',
			'columnName' => 'fk'));

		$this->assertNotNull($fk);
		$this->assertEquals('tabletest.product_order.no',$fk->getReferences());
		$this->assertEquals('CASCADE', $fk->onUpdate);
		$this->assertEquals('NO ACTION', $fk->onDelete);

		$foreignKey = new ForeignKey();
		$foreignKey->TABLE_SCHEMA = 'tabletest';
		$foreignKey->TABLE_NAME = 'product3';
		$foreignKey->COLUMN_NAME = 'fk';
		$foreignKey->setReferences('tabletest.product4.var');


		$foreignKey->onUpdate = 'NO ACTION';
		$foreignKey->onDelete = 'CASCADE';
		$this->assertType('string', $foreignKey->save());

		$fk = ForeignKey::model()->findBySql('SELECT * FROM KEY_COLUMN_USAGE '
		. 'WHERE TABLE_SCHEMA = :tableSchema '
		. 'AND TABLE_NAME = :tableName '
		. 'AND COLUMN_NAME = :columnName '
		. 'AND REFERENCED_TABLE_SCHEMA IS NOT NULL', array(
			'tableSchema' => 'tabletest',
			'tableName' => 'product3',
			'columnName' => 'fk'));

		$this->assertNotNull($fk);
		$this->assertEquals('tabletest.product4.var',$fk->getReferences());
		$this->assertEquals('NO ACTION', $fk->onUpdate);
		$this->assertEquals('CASCADE', $fk->onDelete);


		// add a foreignkey with on update and on delete with set null
		$foreignKey = new ForeignKey();
		$foreignKey->TABLE_SCHEMA = 'tabletest';
		$foreignKey->TABLE_NAME = 'product5';
		$foreignKey->COLUMN_NAME = 'fk';
		$foreignKey->setReferences('tabletest.product3.id');


		$foreignKey->onUpdate = 'SET NULL';
		$foreignKey->onDelete = 'SET NULL';
		$this->assertType('string', $foreignKey->save());

		$fk = ForeignKey::model()->findBySql('SELECT * FROM KEY_COLUMN_USAGE '
		. 'WHERE TABLE_SCHEMA = :tableSchema '
		. 'AND TABLE_NAME = :tableName '
		. 'AND COLUMN_NAME = :columnName '
		. 'AND REFERENCED_TABLE_SCHEMA IS NOT NULL', array(
			'tableSchema' => 'tabletest',
			'tableName' => 'product5',
			'columnName' => 'fk'));

		$this->assertNotNull($fk);
		$this->assertEquals('tabletest.product3.id',$fk->getReferences());
		$this->assertEquals('SET NULL', $fk->onUpdate);
		$this->assertEquals('SET NULL', $fk->onDelete);
	}

	/**
	 * tries to update a foreignkey and add on Update and on Delete properties
	 */
	public function testUpdate()
	{
		$fk = ForeignKey::model()->findBySql('SELECT * FROM KEY_COLUMN_USAGE '
		. 'WHERE TABLE_SCHEMA = :tableSchema '
		. 'AND TABLE_NAME = :tableName '
		. 'AND COLUMN_NAME = :columnName '
		. 'AND REFERENCED_TABLE_SCHEMA IS NOT NULL', array(
			'tableSchema' => 'tabletest',
			'tableName' => 'product_order',
			'columnName' => 'customer_id'));

		$this->assertEquals('NO ACTION', $fk->onDelete);
		$this->assertEquals('CASCADE', $fk->onUpdate);

		$this->assertEquals('tabletest.customer.id',$fk->getReferences());

		$fk->setReferences('tabletest.product2.id');

		$fk->onUpdate = 'NO ACTION';
		$fk->onDelete = 'CASCADE';
		$this->assertType('string', $fk->update());

		$this->assertType('string', $fk->update());

		$fk = ForeignKey::model()->findBySql('SELECT * FROM KEY_COLUMN_USAGE '
		. 'WHERE TABLE_SCHEMA = :tableSchema '
		. 'AND TABLE_NAME = :tableName '
		. 'AND COLUMN_NAME = :columnName '
		. 'AND REFERENCED_TABLE_SCHEMA IS NOT NULL', array(
			  'tableSchema' => 'tabletest',
			  'tableName' => 'product_order',
		      'columnName' => 'customer_id'));

		$this->assertEquals('tabletest.product2.id',$fk->getReferences());
		$this->assertEquals('NO ACTION', $fk->onUpdate);
		$this->assertEquals('CASCADE', $fk->onDelete);

		/**
		 * try to set a foreignkey with on update and
		 * on delete on a column with propertie not null
		 */
		$fk = ForeignKey::model()->findBySql('SELECT * FROM KEY_COLUMN_USAGE '
		. 'WHERE TABLE_SCHEMA = :tableSchema '
		. 'AND TABLE_NAME = :tableName '
		. 'AND COLUMN_NAME = :columnName '
		. 'AND REFERENCED_TABLE_SCHEMA IS NOT NULL', array(
			  'tableSchema' => 'tabletest',
			  'tableName' => 'product_order',
		      'columnName' => 'customer_id'));

		$fk->onUpdate = 'SET NULL';
		$fk->onDelete = 'SET NULL';
		$this->assertFalse($fk->update());
	}

	/**
	 * tries to update a Foreignkey with wrong reference
	 */
	public function testUpdate2()
	{
		$fk = ForeignKey::model()->findBySql('SELECT * FROM KEY_COLUMN_USAGE '
		. 'WHERE TABLE_SCHEMA = :tableSchema '
		. 'AND TABLE_NAME = :tableName '
		. 'AND COLUMN_NAME = :columnName '
		. 'AND REFERENCED_TABLE_SCHEMA IS NOT NULL', array(
			'tableSchema' => 'tabletest',
			'tableName' => 'product_order',
			'columnName' => 'customer_id'));

		$this->assertEquals('tabletest.customer.id',$fk->getReferences());

		//set wrong reference --> expect false as update() return value
		$fk->setReferences('');

		$this->assertFalse($fk->update());
	}

	/**
	 * tries to insert a foreignkey which is not new
	 * @expectedException CDbException
	 */
	public function testInsertNotNew()
	{
		$fk = ForeignKey::model()->findBySql('SELECT * FROM KEY_COLUMN_USAGE '
		. 'WHERE TABLE_SCHEMA = :tableSchema '
		. 'AND TABLE_NAME = :tableName '
		. 'AND COLUMN_NAME = :columnName '
		. 'AND REFERENCED_TABLE_SCHEMA IS NOT NULL', array(
			'tableSchema' => 'tabletest',
			'tableName' => 'product_order',
			'columnName' => 'customer_id'));

		$fk->insert();
	}

	/**
	 * tries to update a foreignkey which is new
	 * causes expection: cannot be updated because it is new
	 * @expectedException CDbException
	 */
	public function testUpdateIsNew()
	{
		$foreignKey = new ForeignKey();
		$foreignKey->TABLE_SCHEMA = 'tabletest';
		$foreignKey->TABLE_NAME = 'product';
		$foreignKey->COLUMN_NAME = 'fk';

		$foreignKey->setReferences('tabletest.product_order.no');
		$foreignKey->update();
	}

	/**
	 * tries to delete a foreignkey which is new
	 * causes expection: cannot be deleted because it is new
	 * @expectedException CDbException
	 */
	public function testDeleteIsNew()
	{
		$foreignKey = new ForeignKey();
		$foreignKey->TABLE_SCHEMA = 'tabletest';
		$foreignKey->TABLE_NAME = 'product';
		$foreignKey->COLUMN_NAME = 'fk';
		$foreignKey->setReferences('tabletest.product_order.no');
		$foreignKey->delete();
	}

	/**
	 * tries to add a foreignkey to a column and references to a column with
	 * an other datatype
	 */
	public function testInsertOnWrongDataType()
	{
		$foreignKey = new ForeignKey();
		$foreignKey->TABLE_SCHEMA = 'tabletest';
		$foreignKey->TABLE_NAME = 'product3';
		$foreignKey->COLUMN_NAME = 'price';
		$foreignKey->setReferences('tabletest.product4.var');

		$this->assertFalse($foreignKey->save());
	}
}
