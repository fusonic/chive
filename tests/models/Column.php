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


	/**
	 * Tests the autoincrement methods
	 *
	 */
	public function testAutoIncrement()
	{
		$pk = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test1',
		);

		// Load column definition
		$col = Column::model()->findByPk($pk);
		$col->setAutoIncrement(false);
		$col->save();
		$col->refresh();
		$this->assertFalse($col->getAutoIncrement());

		$col->setAutoIncrement(true);
		$col->save();
		$col->refresh();
		$this->assertTrue($col->getAutoIncrement());
	}




	/**
	 *Tests the IsNullable methods
	 *
	 */
	public function testIsNullable()
	{
		$pk = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test3',
		);

		// Load column definition
		$col = Column::model()->findByPk($pk);
		$col->setIsNullable('Yes');
		$col->save();
		$col->refresh();

		$this->assertTrue($col->getIsNullable());

		$col->setIsNullable(null);
		$col->save();
		$col->refresh();

		$this->assertFalse($col->getIsNullable());

	}


	/**
	 *tests the Collation methods
	 *
	 */

	public function testCollation()
	{
		$pk = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test4',
		);

		// Load column definition
		$col = Column::model()->findByPk($pk);
		$col->setCollation('latin1_swedish_ci');
		$col->save();
		$col->refresh();
			
		$this->assertEquals('latin1_swedish_ci',$col->getCollation());

	}




	/**
	 * tests the DataType methods
	 *
	 */
	public function testDataType()
	{
		$pk = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test3',
		);

			
		// Load column definition
		$col = Column::model()->findByPk($pk);
		$this->assertEquals('varchar',$col->getDataType());

		$col->setDataType('FLOAT');
		$col->size=6;
		$col->scale=4;
		$col->save();
		$col->refresh();
		$this->assertEquals('float',$col->getDataType());
	}




	/**
	 * checks if the columntype is correct
	 *
	 */
	public function testGetColumnType()
	{
		$pk = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test5',
		);

		// Load column definition
		$col = Column::model()->findByPk($pk);
		$this->assertEquals('float(5, 2)',$col->getColumnType());
	}

	/**
	 *  Sets a value and checks if its correct
	 *
	 */
	public function testSetGetValues()
	{
		$pk = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test3',
		);

		// Load column definition
		$col = Column::model()->findByPk($pk);
		$col->setValues('test value 1234 test value []{}');
		$col->save();
		$col->refresh();
		$this->assertEquals('test value 1234 test value []{}',$col->getValues());
	}


	/**
	 *
	 * test the column definitons
	 *
	 */
	public function testGetColumnDefinition()
	{
		$pk = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test1',
		);

		// Load column definition
		$col = Column::model()->findByPk($pk);
		$this->assertEquals('int',$col->getDataType());
		$this->assertFalse($col->getIsNullable());
		$this->assertEquals('unsigned',$col->attribute);
		$this->assertEquals('auto_increment',$col->getAutoIncrement());
		$this->assertTrue($col->getIsPartOfPrimaryKey());
		$this->assertNull($col->COLUMN_DEFAULT);

		$pk1 = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test2',
		);

		// Load column definition
		$col = Column::model()->findByPk($pk1);

		$this->assertEquals('mediumint',$col->getDataType());
		$this->assertFalse($col->getIsNullable());
		$this->assertFalse($col->getAutoIncrement());
		$this->assertEquals('unsigned',$col->attribute);
		$this->assertEquals(3,$col->COLUMN_DEFAULT);
		$this->assertTrue($col->getIsPartOfPrimaryKey());
		$this->assertFalse($col->getAutoIncrement());

		$pk2 = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test3',
		);

		// Load column definition
		$col = Column::model()->findByPk($pk2);

		$this->assertEquals('varchar',$col->getDataType());
		$this->assertEquals(100,$col->size);
		$this->assertFalse($col->getIsNullable());
		$this->assertFalse($col->getAutoIncrement());
		$this->assertFalse($col->getIsPartOfPrimaryKey());
		$this->assertNull($col->COLUMN_DEFAULT);
		$this->assertEquals('latin1_swedish_ci',$col->getCollation());
		$this->assertFalse($col->getAutoIncrement());

		$pk3 = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test4',
		);

		// Load column definition
		$col = Column::model()->findByPk($pk3);
		$this->assertEquals('enum',$col->getDataType());
		$this->assertContains('a',$col->values);
		$this->assertContains('b',$col->values);
		$this->assertTrue($col->getIsNullable());
		$this->assertFalse($col->getIsPartOfPrimaryKey());
		$this->assertEquals('utf8_general_ci',$col->getCollation());
		$this->assertFalse($col->getAutoIncrement());

		$pk4 = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test5',
		);

		// Load column definition
		$col = Column::model()->findByPk($pk4);
		$this->assertEquals('float',$col->getDataType());
		$this->assertEquals('5',$col->size);
		$this->assertEquals('2',$col->scale);
		$this->assertEquals('',$col->attribute);
		$this->assertFalse($col->getIsNullable());
		$this->assertFalse($col->getIsPartOfPrimaryKey());
		$this->assertNull($col->getCollation());
		$this->assertFalse($col->getAutoIncrement());
	}



	/**
	 * Deletes all Columns and expect a DbException
	 * "you can't delete all columns"
	 *
	 * @expectedException CDbException
	 */
	public function testDeleteAllColumns()
	{
		$cols = Column::model()->findAllByAttributes(array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
		));

		foreach($cols AS  $col)
		{

			$col->delete();

		}

		$this->assertNull($col);
	}




	/**
	 * alter colums and check it afterwards
	 *
	 */
	public function testAlter()
	{
		$cols = Column::model()->findAllByAttributes(array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
		));

		$count = count($cols);

		foreach($cols AS $c => $col)
		{
			$col->setDataType('float');
			$col->size = 10;
			$col->scale = 2;
			$col->attribute = "unsigned zerofill";
			$col->setIsNullable(false);
			$col->save();
			$col->refresh();


			$pk = array(
			    'TABLE_SCHEMA' => 'columntest',
			    'TABLE_NAME' => 'test',
			    'COLUMN_NAME' => $col->COLUMN_NAME,
			);

			// Load column definition
			$col = Column::model()->findByPk($pk);

			$this->assertEquals('unsigned zerofill',$col->attribute);
			$this->assertEquals('float',$col->getDataType());
			$this->assertEquals(10,$col->size);
			$this->assertEquals(2,$col->scale);
			$this->assertNull($col->getCollation());
			$this->assertfalse($col->getIsNullable());

			($c == 0 ? $this->assertTrue($col->getAutoIncrement()) : $this->assertFalse($col->getAutoIncrement()));

			if($c == 0 || $c==1)
			{
				$this->assertTrue($col->getIsPartOfPrimaryKey());
			}
			else
			{
				$this->assertFalse($col->getIsPartOfPrimaryKey());
			}
		}
	}

	/**
	 * alter colums and check it afterwards
	 *
	 */
	public function testAlter2()
	{
		$cols = Column::model()->findAllByAttributes(array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
		));



		foreach($cols AS $c => $col)
		{


			$col->setDataType('tinyint');
			$col->size=1;
			$col->setIsNullable(true);
			$col->COLUMN_DEFAULT=2;
			$col->save();
			$col->refresh();


			$pk = array(
			    'TABLE_SCHEMA' => 'columntest',
			    'TABLE_NAME' => 'test',
			    'COLUMN_NAME' => $col->COLUMN_NAME,
			);

			// Load column definition
			$col = Column::model()->findByPk($pk);


			$this->assertEquals('tinyint',$col->getDataType());
			$this->assertEquals(1,$col->size);
			$this->assertNull($col->getCollation());


			($c == 0 ? $this->assertNull($this->COLUMN_DEFAULT) : $this->assertEquals(2,$col->COLUMN_DEFAULT));

			($c == 0 ? $this->assertTrue($col->getAutoIncrement()) : $this->assertFalse($col->getAutoIncrement()));

			if($c == 0 || $c==1)
			{
				$this->assertTrue($col->getIsPartOfPrimaryKey());
				$this->assertFalse($col->getIsNullable());
				$this->assertEquals('unsigned',$col->attribute);
					
			}
			else
			{
				$this->assertFalse($col->getIsPartOfPrimaryKey());
				$this->assertTrue($col->getIsNullable());
				$this->assertEquals('',$col->attribute);

			}

		}

	}

	/**
	 * alter colums and check it afterwards
	 *
	 */
	public function testAlter3()
	{

		$cols = Column::model()->findAllByAttributes(array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
		));


		foreach($cols AS $c => $col)
		{

			//primary key can't be timestamp, start at column test3
			if($c > 1)
			{


				if($c==3)
				{
					$fixture='on update current_timestamp';
					$col->attribute=$fixture;

				}

				$col->setDataType('timestamp');
				$col->COLUMN_DEFAULT='2008-11-26 04:12:44';
				$col->setIsNullable(false);
				$col->save();
				$col->refresh();


				$pk = array(
			    'TABLE_SCHEMA' => 'columntest',
			    'TABLE_NAME' => 'test',
			    'COLUMN_NAME' => $col->COLUMN_NAME,
				);

				// Load column definition
				$col = Column::model()->findByPk($pk);


				$this->assertEquals('timestamp',$col->getDataType());
				$this->assertFalse($col->getIsNullable());
				$this->assertEquals('2008-11-26 04:12:44',$col->COLUMN_DEFAULT);
				$this->assertNull($col->getCollation());


				$pk = array(
			    'TABLE_SCHEMA' => 'columntest',
			    'TABLE_NAME' => 'test',
			    'COLUMN_NAME' => $col->COLUMN_NAME,
				);

				// Load column definition
				$col = Column::model()->findByPk($pk);


				if($c==3)
				{
					$this->assertEquals($fixture,strtolower($col->attribute));
				}

				$this->assertNull($col->size);

			}

		}
	}


	public function testAlter4()
	{

		$cols = Column::model()->findAllByAttributes(array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
		));

		// Try with array too
		$values = "a\nb\nc\nd\ne";



		foreach($cols AS $c => $col)
		{
			//enum can't be auto_increment, start at column test3
			if($c > 0)
			{




				$col->setDataType('ENUM');
				$col->setIsNullable(true);
				$col->setCollation('latin1_swedish_ci');
				$col->values=$values;
				$col->COLUMN_DEFAULT = 'a';
					
				$col->save();
				$col->refresh();


				$pk = array(
			    'TABLE_SCHEMA' => 'columntest',
			    'TABLE_NAME' => 'test',
			    'COLUMN_NAME' => $col->COLUMN_NAME,
				);

				// Load column definition
				$col = Column::model()->findByPk($pk);

				$this->assertEquals('enum',$col->getDataType());
				$this->assertEquals($values, $col->values);

				($c==1 ? $this->assertFalse($col->getIsNullable()) : $this->assertTrue($col->getIsNullable()));

			}

		}
	}


	public function testAlter5()
	{

		$cols = Column::model()->findAllByAttributes(array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
		));


		$values = "a\nb\nc\nd";
		

		$values_arr = array('a','b','c','d');

		foreach($cols AS $c => $col)
		{
			if($c > 1)
			{
				$col->setDataType('set');
				$col->setCollation('latin1_swedish_ci');
				$col->values=($c%2 == 0 ? $values : $values_arr);
					
				$col->save();
				$col->refresh();


				$pk = array(
			    'TABLE_SCHEMA' => 'columntest',
			    'TABLE_NAME' => 'test',
			    'COLUMN_NAME' => $col->COLUMN_NAME,
				);

				// Load column definition
				$col = Column::model()->findByPk($pk);

				$this->assertEquals('set',$col->getDataType());

				$this->assertEquals($values, $col->values);

			}


		}
	}


	public function testConfigFunctions()
	{


		// Create new schema
		$column = new column();

		// Check return types
		$this->assertTrue(is_array($column->safeAttributes()));
		$this->assertTrue(is_array($column->attributeLabels()));
		$this->assertTrue(is_array($column->rules()));
		$this->assertTrue(is_array($column->relations()));
		$this->assertTrue(is_array($column->getDataTypes()));
	}


	/*$fixture_numeric = array(
	 'bit',
	 'tinyint',
	 'bool',
	 'smallint',
	 'mediumint',
	 'int',
	 'bigint',
	 'float',
	 'double',
	 'decimal'
	 );

	 $fixture_numeric = array(
	 'char',
	 'varchar',
	 'tinytext',
	 'text',
	 'mediumtext',
	 'longtext',
	 'tinyblob',
	 'blob' => 'blob',
	 'mediumblob',
	 'longblob',
	 'binary',
	 'varbinary',
	 'enum',
	 'set'
	 );

	 $fixture_date = array(
	 'date',
	 'datetime',
	 'timestamp',
	 'time',
	 'year'
	 );*/


}

?>