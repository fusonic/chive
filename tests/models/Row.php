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
		Row::$table = "data";


	}

	/**
	 * tests some config methods
	 */
	public function testConfig()
	{
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
	 *          update auf tabelle mit gleichnamigen spalten
	 *          upadte auf tabelle mit 1 oder mehreren pks
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
	 *
	 * Tests to update row attributes
	 */

	public function testUpdate()
	{


		//$rows = Row::model()->findAllByAttributes(array('test1'=>1));

		/* //	 var_dump($row);
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

	/*
	 public function testUpdate2()
	 {
		$_GET['schema'] = "rowtest";
		$_GET['table'] = "data2";

	 $row = Row::model()->findByAttributes(array('test1'=>'1'));

	 var_dump($row);

	 }


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