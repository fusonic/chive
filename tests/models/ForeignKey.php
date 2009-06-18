<?php

class ForeignKeyTest extends TestCase
{
	/**
	 * Setup test databases.
	 */
	protected function setUp()
	{
		$this->executeSqlFile('models/ForeignKey.sql');
		$db = new CDbConnection('mysql:host='.DB_HOST.';dbname=tabletest', DB_USER, DB_PASSWORD);
		$db->charset='utf8';
		$db->active = true;

		Column::$db =
		ForeignKey::$db =
		Index::$db =
		Routine::$db =
		Row::$db =
		Schema::$db =
		Table::$db =
		Trigger::$db =
		View::$db = $db;

	}

	/**
	 * Tests some config
	 */
	public function testConfig()
	{
		$fk = new ForeignKey();

		$this->assertType('string',$fk->tableName());
		$this->assertType('array',$fk->primaryKey());
		$this->assertType('array',$fk->safeAttributes());
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
	 * tries to insert a Foreignkey
	 */
	public function testInsert()
	{
		$foreignKey = new ForeignKey();
		$foreignKey->TABLE_SCHEMA = 'tabletest';
		$foreignKey->TABLE_NAME = 'product';
		$foreignKey->COLUMN_NAME = 'fk';

		$foreignKey->setReferences('tabletest.product_order.no');
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
			
		$this->assertEquals('tabletest.customer.id',$fk->getReferences());


		$fk->setReferences('tabletest.product2.id');

		// Verschiedene "on update"/"on delete"
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
	 * tries to delete a foreignkey which is new
	 * @expectedException CDbException
	 */
	public function testDelete2()
	{
		$foreignKey = new ForeignKey();
		$foreignKey->TABLE_SCHEMA = 'tabletest';
		$foreignKey->TABLE_NAME = 'product';
		$foreignKey->COLUMN_NAME = 'fk';

		$foreignKey->setReferences('tabletest.product_order.no');

		$foreignKey->delete();
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





}