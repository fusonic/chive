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


class DataTypeTest extends CTestCase
{
	
	/**
	 * test if the check method returns the correct values
	 */
	public function testCheck()
	{
		$types = array(
			'bit',
			'tinyint',
			'bool',
			'smallint',
			'mediumint',
			'int',
			'bigint',
			'float',
			'double',
			'decimal',
			'char',
			'varchar',
			'tinytext',
			'text',
			'mediumtext',
			'longtext',
			'tinyblob',
			'blob',
			'mediumblob',
			'longblob',
			'binary',
			'varbinary',
		    'enum',
			'set',
			'date',
			'datetime',
			'timestamp',
			'time',
			'year'
		);

		$options_bool = array(
			DataType::SUPPORTS_COLLATION,
			DataType::SUPPORTS_INDEX,
			DataType::SUPPORTS_UNIQUE,
			DataType::SUPPORTS_FULLTEXT,
			DataType::SUPPORTS_SIZE,
			DataType::SUPPORTS_SCALE,
			DataType::SUPPORTS_VALUES,
			DataType::SUPPORTS_UNSIGNED,
			DataType::SUPPORTS_UNSIGNED_ZEROFILL,
			DataType::SUPPORTS_ON_UPDATE_CURRENT_TIMESTAMP,
			DataType::SUPPORTS_AUTO_INCREMENT
		);

		$options_string = array(DataType::GROUP,DataType::INPUT_TYPE);

		foreach($types as $type)
		{
			foreach($options_bool as $option)
			{
				$this->assertType('bool', DataType::check($type, $option));
			}
			foreach($options_string as $option)
			{
				$this->assertType('string', DataType::check($type, $option));
			}
		}
	}

	/**
	 * tests whether the method BaseType returns the correct values
	 */
	public function testBaseType()
	{
		$types = array(
			'bit(1)' => 'bit',
			'tinyint(2)' => 'tinyint',
			'bool' => 'bool',
			'smallint(4)' => 'smallint',
			'mediumint(8)' => 'mediumint',
			'int(10)' => 'int',
			'bigint(16)' => 'bigint',
			'float(1)' => 'float',
			'float' => 'float',
			'float(1,1)' => 'float',
			'double(14,4)' => 'double', 
			'decimal(3,5)'=> 'decimal',
			'char' => 'char',
			'varchar(10)' => 'varchar',
			'tinytext' => 'tinytext',
			'text' => 'text',
			'mediumtext' =>'mediumtext',
			'longtext' => 'longtext',
			'tinyblob' => 'tinyblob',
			'blob' => 'blob',
			'mediumblob' => 'mediumblob',
			'longblob' => 'longblob',
			'binary' => 'binary',
			'varbinary' => 'varbinary',
		    'enum(1,2,3)' => 'enum',
			'enum(\'1\',\'2\')' => 'enum',
			'set(1,2,3)' => 'set',
		    'set(\'1\',\'2\')' => 'set',
			'date' => 'date',
		    'date(YYYY-MM-DD)'=> 'date',
		    'datetime(YYYY-MM-DD HH:MM:SS)' => 'datetime',
		    'datetime' => 'datetime',
			'timestamp'=> 'timestamp',
			'time(HH:MM:SS)' => 'time',
		    'time' => 'time',
			'year' => 'year'
		);

		foreach($types as $type => $expected)
		{
			$this->assertEquals($expected, DataType::getBaseType($type));
		}
	}

	/** 
	 * test if the method getInputType returns the correct inputtype
	 */
	public function testGetInputType()
	{
		$DataType = new DataType();
		$this->assertEquals('number',$DataType->getInputType('int'));
		$this->assertEquals('checkbox',$DataType->getInputType('bool'));
		$this->assertEquals('file',$DataType->getInputType('blob'));
		$this->assertEquals('single',$DataType->getInputType('binary'));
		$this->assertEquals('text',$DataType->getInputType('text'));
		$this->assertEquals('select-multiple',$DataType->getInputType('set'));
		$this->assertEquals('date',$DataType->getInputType('date'));
		$this->assertEquals('datetime',$DataType->getInputType('datetime'));
		$this->assertEquals('number',$DataType->getInputType('year'));
	}

}