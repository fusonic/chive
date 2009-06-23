<?php

class RowTest extends TestCase
{

	//public static $enabled = false;

	/**
	 * Setup test databases.
	 */
	protected function setUp()
	{

		$this->executeSqlFile('models/Row.sql');
		$db = new CDbConnection('mysql:host='.DB_HOST.';dbname=rowtest', DB_USER, DB_PASSWORD);
		$db->charset='utf8';
		$db->active = true;
		Row::$db = $db;
		Row::$schema = "rowtest";


	}

	/**
	 * tests some config methods
	 */
	public function testConfig()
	{
		Row::$table = "data";

		$this->assertType('array', Row::model()->attributeLabels());
		$this->assertType('array', Row::model()->attributeNames());
		$this->assertType('array', Row::model()->safeAttributes());
		$this->assertType('object', Row::model()->getDbConnection());
		$this->assertType('string', Row::model()->tableName());
		$this->assertType('array', Row::model()->relations());
		$this->assertType('array', Row::model()->rules());
		$this->assertType('string', Row::model()->primaryKey());
	}


	/*
	 *
	 * tests:   mehrere Datatypes updaten oder inserten
	 *                upadte auf tabelle mit 1 oder mehreren pks
	 *
	 *			1.) Tables with primary keys
	 *				a.) Single column PKs
	 *						- int
	 *						- enum
	 *						- varchar
	 *					- NULL
	 *
	 *				b.) Muli-column PKs
	 *						- int, int (nullable) ... 1, NULL
	 *						- int, enum
	 *
	 *			2.) Content types
	 *
	 *			3.) Tables without primary keys
	 *               a) 2 rows besitzen selbe werte .. welches ändert sich?
	 *
	 *
	 */


	/**
	 * tries to read the row values
	 */
	public function testRead()
	{

		Row::$table = "data";
		$rows = Row::model()->findAllByAttributes(array('test1'=>1));

		$row = $rows[0];

		$this->assertType('numeric',$row->getAttribute('test1'));
		$this->assertType('numeric',$row->getAttribute('test2'));
		$this->assertType('string',$row->getAttribute('test3'));
		$this->assertType('numeric',$row->getAttribute('test4'));
		$this->assertType('string',$row->getAttribute('test5'));
		$this->assertType('string',$row->getAttribute('test6'));
		$this->assertType('numeric',$row->getAttribute('test7'));
		$this->assertType('string',$row->getAttribute('test8'));
		$this->assertType('string',$row->getAttribute('test9'));




		/* //
		 $row = $rows[0];
		 $row->setAttribute('test2',4);
		 $row->setAttribute('test3','blub blub blub');
		 $row->setAttribute('test4',443.56);

		 $row->save();
		 $row->refresh();

		 $row = Row::model()->findByAttributes(array('test2'=>4));
		 $row = $rows[0];
		 $this->assertEquals(4,$row->getAttribute('test2'));
		 $this->assertEquals('blub blub blub',$row->getAttribute('test3'));
		 $this->assertEquals(443.56,$row->getAttribute('test4'));*/
	}

	/**
	 * tries to update one row
	 */
	public function testUpdate()
	{
		/*$pk = array(
			'TABLE_SCHEMA' => 'rowtest',
			'TABLE_NAME' => 'data2',
			'COLUMN_NAME' => 'test1'
			);
			$row = Row::model()->findByPk($pk);*/

		
		Row::$table = "data2";

		$row = Row::model()->findAllByAttributes(array('test1'=>1));

		$row = $row[0];

		$row->setAttribute('test1',2);
		$row->setAttribute('test2',345345);
		$row->setAttribute('test3','testtesttesttest');
		$row->setAttribute('test4',433.43);
		$row->setAttribute('test5','2009-06-11');
		$row->setAttribute('test6','b');
		$row->setAttribute('test7','3');
		$row->setAttribute('test8','neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.');
		$row->setAttribute('test9','{"firstName": "John", "lastName": "Smith", "address": {"streetAddress": "21 2nd Street", "city": "New York", "state": "NY", "postalCode": 10021}, "phoneNumbers": ["212 555-1234", "646 555-4567"]}');

		//var_dump($row->update());
		//$this->assertType('string',$row->update());

	/*	Row::$table = "data2";
		
		$cmd = Row::$db->createCommand("select * from data2");
		//var_dump($cmd->queryAll(true));

		$row = Row::model()->findAllByAttributes(array('test1'=>2));
		$row = $row[0];

		$this->assertEquals(433.43,$row->getAttribute('test4'));
		$this->assertEquals('b',$row->getAttribute('test6'));
		$this->assertEquals('2',$row->getAttribute('test1'));
		$this->assertEquals('3',$row->getAttribute('test7'));
		$this->assertEquals('2009-06-11',$row->getAttribute('test5'));
		$this->assertEquals('testtesttesttest',$row->getAttribute('test3'));*/


	}

	/*
		$_GET['schema'] = "rowtest";
		$_GET['table'] = "data2";

	 $row = Row::model()->findByAttributes(array('test1'=>'1'));

	 var_dump($row);

	 /*
	 *
	 *
	 * $row = new Row();

		$row->setAttribute('spaltenname', 123);

		$row->save();

		$newRow = Row::model()->findByPk($pk);

		$row = Row::model()->findByPk(3);

		$row = Row::model()->findByPk(array('id'=>3, 'description'=>'test'));



		$this->assertType('string',$row->tableName());

		*
		*
		*/
	/**
	 * Tests to read database information.

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
		*/
}

?>