<?php

class ForeignKeyTest extends TestCase
{

	//public static $enabled = false;

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
	 *
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
	 *
	 * trys to set and get the References of the Foreignkey
	 *
	 */
	public function testReferences()
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


		$fk->setReferences('tabletest.customer.test');

		$fk->save();
		$fk->refresh();

		$this->assertEquals('tabletest.customer.test',$fk->getReferences());

	}


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

		$fk->delete();
		$fk->refresh();


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


	public function testInsert()
	{
		$foreignKey = new ForeignKey();
		$foreignKey->TABLE_SCHEMA = 'tabletest';
		$foreignKey->TABLE_NAME = 'product';
		$foreignKey->COLUMN_NAME = 'fk';

		$foreignKey->setReferences('tabletest.product_order.no');
		$foreignKey->save();


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


		$fk->setReferences('tabletest.customer.test');

		$fk->update();
		$fk->refresh();

		$this->assertEquals('tabletest.customer.test',$fk->getReferences());

	}

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


		$fk->setReferences('');

		
		$this->assertFalse($fk->update());

	}

	public function testDelete2()
	{
		$foreignKey = new ForeignKey();
		$foreignKey->TABLE_SCHEMA = 'tabletest';
		$foreignKey->TABLE_NAME = 'product';
		$foreignKey->COLUMN_NAME = 'fk';

		$foreignKey->setReferences('tabletest.product_order.no');
		$foreignKey->save();

		$this->assertFalse($foreignKey->delete());	
	}







}