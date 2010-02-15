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


class RowTest extends ChiveTestCase
{

	/**
	 * Setup test databases.
	 */
	protected function setUp()
	{

		$this->executeSqlFile('models/RowTest.sql');
		
		Row::$db = $this->createDbConnection('rowtest');
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
		$this->assertType('object', Row::model()->getDbConnection());
		$this->assertType('string', Row::model()->tableName());
		$this->assertType('array', Row::model()->relations());
		$this->assertType('array', Row::model()->rules());
		$this->assertType('string', Row::model()->primaryKey());
	}



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

		$this->assertEquals('1',$row->getAttribute('test1'));
		$this->assertEquals('43534534',$row->getAttribute('test2'));
		$this->assertEquals('Test',$row->getAttribute('test3'));
		$this->assertEquals('332.43',$row->getAttribute('test4'));
		$this->assertEquals('2009-11-15 00:00:00',$row->getAttribute('test5'));
		$this->assertEquals('a',$row->getAttribute('test6'));
		$this->assertEquals('1',$row->getAttribute('test7'));
		$this->assertContains('Sed ut perspiciatis,',$row->getAttribute('test8'));
		$this->assertContains('<ingredient amount="1" unit=',$row->getAttribute('test9'));

	}

	/**
	 * tries to update one row
	 */
	public function testUpdate()
	{

		Row::$table = "data";

		$row = Row::model()->findByAttributes(array('test1'=>1));

		$row->setAttribute('test1', '2');
		$row->setAttribute('test2', '345345');
		$row->setAttribute('test3','testtesttesttest');
		$row->setAttribute('test4', '433.43');
		$row->setAttribute('test5','2009-06-11');
		$row->setAttribute('test6','b');
		$row->setAttribute('test7','3');
		$row->setAttribute('test8','neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.');
		$row->setAttribute('test9','{"firstName": "John", "lastName": "Smith", "address": {"streetAddress": "21 2nd Street", "city": "New York", "state": "NY", "postalCode": 10021}, "phoneNumbers": ["212 555-1234", "646 555-4567"]}');
        $row->setFunction('test3',4);
 
		
		$row->save();
		
		$test = Row::model()->findByAttributes(array('test1'=>2));
			
		$this->assertEquals('2',$row->getAttribute('test1'));
		$this->assertEquals('345345',$row->getAttribute('test2'));
		$this->assertEquals('testtesttesttest',$row->getAttribute('test3'));
		$this->assertEquals('433.43',$row->getAttribute('test4'));
		$this->assertEquals('2009-06-11',$row->getAttribute('test5'));
		$this->assertEquals('b',$row->getAttribute('test6'));
		$this->assertEquals('3',$row->getAttribute('test7'));
		$this->assertContains('neque porro quisquam est, qu',$row->getAttribute('test8'));
		$this->assertContains('{"firstName": "John", "l',$row->getAttribute('test9'));

		$this->assertEquals(4,$row->getFunction('test3'));
		
		$this->assertType('array',$row->getIdentifier());
		/*
		 * @todo(dmoesslang): cant select data2
		 *
		 Row::$table = "data2";

		 $row = Row::model()->findByAttributes(array('test1'=>1));

		 $row->setAttribute('test1', '2');
		 $row->setAttribute('test2', '345345');
		 $row->setAttribute('test3','testtesttesttest');
		 $row->setAttribute('test4', '433.43');
		 $row->setAttribute('test5','2009-06-11');
		 $row->setAttribute('test6','b');
		 $row->setAttribute('test7','3');
		 $row->setAttribute('test8','neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.');
		 $row->setAttribute('test9','{"firstName": "John", "lastName": "Smith", "address": {"streetAddress": "21 2nd Street", "city": "New York", "state": "NY", "postalCode": 10021}, "phoneNumbers": ["212 555-1234", "646 555-4567"]}');

		 $test = Row::model()->findByAttributes(array('test1'=>2));




		 $this->assertEquals('2',$row->getAttribute('test1'));
		 $this->assertEquals('345345',$row->getAttribute('test2'));
		 $this->assertEquals('testtesttesttest',$row->getAttribute('test3'));
		 $this->assertEquals('433.43',$row->getAttribute('test4'));
		 $this->assertEquals('2009-06-11 00:00:00',$row->getAttribute('test5'));
		 $this->assertEquals('b',$row->getAttribute('test6'));
		 $this->assertEquals('3',$row->getAttribute('test7'));
		 $this->assertContains('neque porro quisquam est, qu',$row->getAttribute('test8'));
		 $this->assertContains('{"firstName": "John", "l',$row->getAttribute('test9'));

		 Row::$table = "data3";

		 $row = Row::model()->findByAttributes(array('test1'=>1));

		 $row->setAttribute('test1', '2');
		 $row->setAttribute('test2', '345345');
		 $row->setAttribute('test3','testtesttesttest');
		 $row->setAttribute('test4', '433.43');
		 $row->setAttribute('test5','2009-06-11');
		 $row->setAttribute('test6','b');
		 $row->setAttribute('test7','3');
		 $row->setAttribute('test8','neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.');
		 $row->setAttribute('test9','{"firstName": "John", "lastName": "Smith", "address": {"streetAddress": "21 2nd Street", "city": "New York", "state": "NY", "postalCode": 10021}, "phoneNumbers": ["212 555-1234", "646 555-4567"]}');

		 $row->save();

		 $urow = Row::model()->findByAttributes(array('test1'=>2));


		 //$row = new Row();
		 //var_dump($row);





		 /*
		 * @todo(mburtscher): Seems not to be finished ...
		 */

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


	public function testDelete()
	{

		Row::$table = "data";

		$row = Row::model()->findByAttributes(array('test1'=>1));

		$row->delete();

		$this->assertNull(Row::model()->findByAttributes(array('test1'=>1)));

			
		/*	@todo(dmoesslang): cant select data2
		 	
		Row::$table = "data2";

		$row = Row::model()->findAllByAttributes(array('test1'=>1));
		die(var_dump(count($row)));

		die(var_dump($row));

		$row->delete();

		$this->assertNull(Row::model()->findByAttributes(array('test1'=>1)));

		Row::$table = "data3";



		$row = Row::model()->findByPk(array('test1'=>1, 'test2'=>123412));
		die(var_dump($row));
		$row->delete();

		$this->assertNull(Row::model()->findByAttributes(array('test1'=>1)));*/

	}

	public function testInsert()
	{
		Row::$table = "data";

		$row = new Row();

		$row->setAttribute('test1', '2');
		$row->setAttribute('test2', '345345');
		$row->setAttribute('test3','testtesttesttest');
		$row->setAttribute('test4', '433.43');
		$row->setAttribute('test5','2009-06-11');
		$row->setAttribute('test6','b');
		$row->setAttribute('test7','3');
		$row->setAttribute('test8','neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.');
		$row->setAttribute('test9','{"firstName": "John", "lastName": "Smith", "address": {"streetAddress": "21 2nd Street", "city": "New York", "state": "NY", "postalCode": 10021}, "phoneNumbers": ["212 555-1234", "646 555-4567"]}');

		$row->save();

		$urow = Row::model()->findByAttributes(array('test1'=>2));

		$this->assertEquals('2',$row->getAttribute('test1'));
		$this->assertEquals('345345',$row->getAttribute('test2'));
		$this->assertEquals('testtesttesttest',$row->getAttribute('test3'));
		$this->assertEquals('433.43',$row->getAttribute('test4'));
		$this->assertEquals('2009-06-11',$row->getAttribute('test5'));
		$this->assertEquals('b',$row->getAttribute('test6'));
		$this->assertEquals('3',$row->getAttribute('test7'));
		$this->assertEquals('neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.',$row->getAttribute('test8'));
		$this->assertEquals('{"firstName": "John", "lastName": "Smith", "address": {"streetAddress": "21 2nd Street", "city": "New York", "state": "NY", "postalCode": 10021}, "phoneNumbers": ["212 555-1234", "646 555-4567"]}',$row->getAttribute('test9'));
			

	}



}