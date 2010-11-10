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


class ColumnTest extends ChiveTestCase
{
	/**
	 * Setup test databases.
	 */
	protected function setUp()
	{
		$this->executeSqlFile('models/ColumnTest.sql');
		
		Table::$db = 
		ActiveRecord::$db = $this->createDbConnection('columntest');
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
		$col->throwExceptions = true;
		$this->assertType('string', $col->delete());

		// Load table definition
		$table = Table::model()->findByPk(array('TABLE_SCHEMA' => 'columntest', 'TABLE_NAME' => 'test'));

		// Check column count
		$this->assertEquals($columnCount-1, count($table->columns));
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
		$this->assertType('string', $col->move('AFTER test3'));

		// Load column definition
		$col = Column::model()->findByPk($pk);

		// Check new position
		$this->assertEquals(4, $col->ORDINAL_POSITION);

		// Move column to first position
		$this->assertType('string', $col->move('FIRST'));

		// Load column definition
		$col = Column::model()->findByPk($pk);

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

		//set AutoIncrement to false
		$col->setAutoIncrement(false);
		$this->assertType('string',$col->save());
		$col = Column::model()->findByPk($pk);
		$this->assertFalse($col->getAutoIncrement());

		//set AutoIncrement to true
		$col->setAutoIncrement(true);
		$this->assertType('string',$col->save());
		$col = Column::model()->findByPk($pk);
		$this->assertTrue($col->getAutoIncrement());
	}

	/**
	 * Tests the IsNullable methods
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

		$col->setIsNullable(true);
		$this->assertType('string',$col->save());
		$col = Column::model()->findByPk($pk);
		$this->assertTrue($col->getIsNullable());

		$col->setIsNullable(false);
		$this->assertType('string',$col->save());
		$col = Column::model()->findByPk($pk);
		$this->assertFalse($col->getIsNullable());
	}

	/**
	 * tests the Collation methods
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

		//set COllation to latin1_swedish_ci
		$col->setCollation('latin1_swedish_ci');
		$this->assertType('string',$col->save());

		$col = Column::model()->findByPk($pk);
		$this->assertEquals('latin1_swedish_ci',$col->getCollation());

		// Load integer column
		$col = Column::model()->findByPk(array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test1',
		));

		// Set collation
		$col->setCollation('latin1_swedish_ci');
		// Save column
		$this->assertType('string', $col->save());
	}

	/**
	 * tests the DataType methods
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
		$this->assertType('string',$col->save());

		$col = Column::model()->findByPk($pk);
		$this->assertEquals('float',$col->getDataType());
		$this->assertEquals(6, $col->size);
		$this->assertEquals(4, $col->scale);
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
	 */
	public function testSetGetValues()
	{
		$pk = array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'test3',
		);

		// Set random
		$rand = md5(microtime());

		// Load column definition
		$col = Column::model()->findByPk($pk);
		$col->setValues($rand . ' []{} \'');
		$this->assertType('string', $col->save());
		$col->refresh();
		$this->assertEquals($rand . ' []{} \'', $col->getValues());
	}


	/**
	 *
	 * test the column definitons
	 *
	 */
	public function testGetColumnDefinition()
	{
		// Load column definition
		$col = Column::model()->findByPk(array(
		'TABLE_SCHEMA' => 'columntest',
		'TABLE_NAME' => 'test',
		'COLUMN_NAME' => 'test1'
		));

		$this->assertEquals('int',$col->getDataType());
		$this->assertFalse($col->getIsNullable());
		$this->assertEquals('unsigned',$col->attribute);
		$this->assertEquals('auto_increment',$col->getAutoIncrement());
		$this->assertTrue($col->getIsPartOfPrimaryKey());
		$this->assertNull($col->COLUMN_DEFAULT);


		// Load column definition
		$col = Column::model()->findByPk(array(
		'TABLE_SCHEMA' => 'columntest',
		'TABLE_NAME' => 'test',
		'COLUMN_NAME' => 'test2'
		));
		$this->assertEquals('mediumint',$col->getDataType());
		$this->assertFalse($col->getIsNullable());
		$this->assertFalse($col->getAutoIncrement());
		$this->assertEquals('unsigned',$col->attribute);
		$this->assertEquals(3,$col->COLUMN_DEFAULT);
		$this->assertTrue($col->getIsPartOfPrimaryKey());
		$this->assertFalse($col->getAutoIncrement());


		// Load column definition
		$col = Column::model()->findByPk(array(
		'TABLE_SCHEMA' => 'columntest',
		'TABLE_NAME' => 'test',
		'COLUMN_NAME' => 'test3'
		));
		$this->assertEquals('varchar',$col->getDataType());
		$this->assertEquals(100,$col->size);
		$this->assertFalse($col->getIsNullable());
		$this->assertFalse($col->getAutoIncrement());
		$this->assertFalse($col->getIsPartOfPrimaryKey());
		$this->assertNull($col->COLUMN_DEFAULT);
		$this->assertEquals('latin1_swedish_ci',$col->getCollation());
		$this->assertFalse($col->getAutoIncrement());

		// Load column definition
		$col = Column::model()->findByPk(array(
		'TABLE_SCHEMA' => 'columntest',
		'TABLE_NAME' => 'test',
		'COLUMN_NAME' => 'test4'
		));
		$this->assertEquals('enum',$col->getDataType());
		$this->assertContains('a',$col->values);
		$this->assertContains('b',$col->values);
		$this->assertTrue($col->getIsNullable());
		$this->assertFalse($col->getIsPartOfPrimaryKey());
		$this->assertEquals('utf8_general_ci',$col->getCollation());
		$this->assertFalse($col->getAutoIncrement());



		// Load column definition
		$col = Column::model()->findByPk(array(
		'TABLE_SCHEMA' => 'columntest',
		'TABLE_NAME' => 'test',
		'COLUMN_NAME' => 'test5'
		));
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
	 */
	public function testDeleteAllColumns()
	{
		$cols = Column::model()->findAllByAttributes(array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
		));

		$i = 0;
		$count = count($cols);
		foreach($cols AS  $col)
		{
			$col->throwExceptions = true;

			if($i == $count - 1)
			{
				$this->setExpectedException('CDbException');
			}

			$this->assertType('string', $col->delete());

			$i++;
		}

	}



	/**
	 * Record can't be deleted cause its new
	 *
	 * @expectedException CDbException
	 */
	public function testDeleteNewRecord()
	{
		$col = new Column();
		$col->delete();
	}

	/**
	 * Record can't be updated cause its new
	 *
	 * @expectedException CDbException
	 */
	public function testUpdateNewRecord()
	{
		$col = new Column();
		$col->update();
	}


	/**
	 * alter colums and check it afterwards
	 * set Datatype to float
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
			$this->assertType('string', $col->save());

			$pk = array(
			    'TABLE_SCHEMA' => 'columntest',
			    'TABLE_NAME' => 'test',
			    'COLUMN_NAME' => $col->COLUMN_NAME,
			);
				
			$this->assertEquals('unsigned zerofill',$col->attribute);
			$this->assertEquals('float',$col->getDataType());
			$this->assertEquals(10,$col->size);
			$this->assertEquals(2,$col->scale);
			$this->assertNull($col->getCollation());
			$this->assertfalse($col->getIsNullable());

			// col1 is AutoIncrement
			if($c == 0)
			{
				$this->assertTrue($col->getAutoIncrement());
			}
			else
			{
				$this->assertFalse($col->getAutoIncrement());
			}

			// col 1 and 2 are PrimaryKey
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
	 * set Datatype to tinyint
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

			$this->assertType('string', $col->save());

			$pk = array(
			    'TABLE_SCHEMA' => 'columntest',
			    'TABLE_NAME' => 'test',
			    'COLUMN_NAME' => $col->COLUMN_NAME,
			);

			// Load column definition
			$col = Column::model()->findByPk($pk);

			$this->assertEquals('tinyint',$col->getDataType());
			$this->assertEquals(1,$col->size);

			//tinyint hasn't got a collation
			$this->assertNull($col->getCollation());

			//col1 has no ColumnDefault
			if($c == 0)
			{
				$this->assertNull($col->COLUMN_DEFAULT);
			}
			else
			{
				$this->assertEquals(2,$col->COLUMN_DEFAULT);
			}

			//col1 has AutoIncrement
			if($c == 0)
			{
				$this->assertTrue($col->getAutoIncrement());
			}
			else
			{
				$this->assertFalse($col->getAutoIncrement());
			}

			//col1 and col2 are Primarykey
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
	 * set DataType to timesamp
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
				$this->assertType('string', $col->save());

				// Load column definition
				$col = Column::model()->findByPk(array(
			    'TABLE_SCHEMA' => 'columntest',
			    'TABLE_NAME' => 'test',
			    'COLUMN_NAME' => $col->COLUMN_NAME
				));

				$this->assertEquals('timestamp',$col->getDataType());
				$this->assertFalse($col->getIsNullable());
				$this->assertEquals('2008-11-26 04:12:44',$col->COLUMN_DEFAULT);
				$this->assertNull($col->getCollation());

				// Load column definition
				$col = Column::model()->findByPk(array(
			    'TABLE_SCHEMA' => 'columntest',
			    'TABLE_NAME' => 'test',
			    'COLUMN_NAME' => $col->COLUMN_NAME
				));

				if($c==3)
				{
					$this->assertEquals($fixture,strtolower($col->attribute));
				}

				$this->assertNull($col->size);
			}
		}
	}


	/**
	 * Test to set DataType to ENUM and test it afterwards
	 */
	public function testAlter4()
	{
		$cols = Column::model()->findAllByAttributes(array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
		));

		$values = "a\nb\nc\nd\ne";

		foreach($cols AS $c => $col)
		{
			if($c > 0)
			{
				$col->setDataType('ENUM');
				$col->setIsNullable(true);
				$col->setCollation('latin1_swedish_ci');
				$col->values=$values;
				$col->COLUMN_DEFAULT = 'a';

				$this->assertType('string', $col->save());

				// Load column definition
				$col = Column::model()->findByPk(array(
				    'TABLE_SCHEMA' => 'columntest',
				    'TABLE_NAME' => 'test',
				    'COLUMN_NAME' => $col->COLUMN_NAME
				));

				$this->assertEquals('enum',$col->getDataType());
				$this->assertEquals($values, $col->values);

				if($c==1)
				{
					$this->assertFalse($col->getIsNullable());
				}
				else
				{
					$this->assertTrue($col->getIsNullable());
				}
			}
		}
	}

	/**
	 * Test the set DataType and sets the values (array and string)
	 */
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
			//col1 can't be DataType set
			if($c > 1)
			{
				$col->setDataType('set');
				$col->setCollation('latin1_swedish_ci');
				$col->values = ($c % 2 == 0 ? $values : $values_arr);

				$this->assertType('string',$col->save());

				// Load column definition
				$col = Column::model()->findByPk(array(
				    'TABLE_SCHEMA' => 'columntest',
				    'TABLE_NAME' => 'test',
				    'COLUMN_NAME' => $col->COLUMN_NAME
				));

				$this->assertEquals('set', $col->getDataType());
				$this->assertEquals($values, $col->values);
			}
		}
	}


	/**
	 * tests to insert a new Column in to the Table
	 */
	public function testInsert()
	{
		$col = new Column();
		$col->TABLE_SCHEMA = 'columntest';
		$col->TABLE_NAME = 'test';
		$col->COLUMN_NAME = 'testnew';

		$col->setDataType('DOUBLE');
		$col->COLUMN_DEFAULT=1;
		$col->size=20;
		$col->scale=5;
		$col->setCollation('utf8_general_ci');

		$this->assertType('string', $col->save());

		// Load column definition
		$col = Column::model()->findByPk(array(
			'TABLE_SCHEMA' => 'columntest',
			'TABLE_NAME' => 'test',
			'COLUMN_NAME' => 'testnew'
			));

			$this->assertEquals('double', $col->getDataType());
			$this->assertEquals(1.00000, $col->COLUMN_DEFAULT);
			$this->assertNull($col->getCollation());
			$this->assertEquals(20,$col->size);
			$this->assertEquals(5,$col->scale);

	}


	/**
	 * tests to insert a new Column with wrong Name
	 */
	public function testAlterWrongCOLUMNNAME()
	{
		$col = new Column();
		$col->TABLE_SCHEMA = 'columntest';
		$col->TABLE_NAME = 'test';
		$col->COLUMN_NAME = 'test \'`';

		$col->setDataType('float');
		$col->COLUMN_DEFAULT=1;
		$col->size = 10;
		$col->scale=5;

		$this->assertFalse($col->insert());
	}


	/**
	 * tests to alter the Column and Set a new Name
	 */
	public function testAlterNewCOLUMNNAME()
	{
		// Load column definition
		$col = Column::model()->findByPk(array(
			    'TABLE_SCHEMA' => 'columntest',
			    'TABLE_NAME' => 'test',
			    'COLUMN_NAME' => 'test3',
		));

		$col->COLUMN_NAME='testnew';

		$this->assertType('string', $col->save());

		// Load column definition
		$col = Column::model()->findByPk(array(
			    'TABLE_SCHEMA' => 'columntest',
			    'TABLE_NAME' => 'test',
			    'COLUMN_NAME' => 'testnew',
		));

		$this->assertEquals('testnew',$col->COLUMN_NAME);
	}


	/**
	 * Record can't be inserted cause its not new
	 *
	 * @expectedException CDbException
	 */
	public function testInsertNotNew()
	{


		$pk = array(
			    'TABLE_SCHEMA' => 'columntest',
			    'TABLE_NAME' => 'test',
			    'COLUMN_NAME' => 'test5',
		);
		// Load column definition
		$col = Column::model()->findByPk($pk);
		$col->insert();
	}

	/**
	 * tests some Config Functions
	 */
	public function testConfigFunctions()
	{
		// Create new schema
		$column = new column();

		// Check return types
		$this->assertTrue(is_array($column->attributeLabels()));
		$this->assertTrue(is_array($column->rules()));
		$this->assertTrue(is_array($column->relations()));
		$this->assertTrue(is_array($column->getDataTypes()));
	}
}