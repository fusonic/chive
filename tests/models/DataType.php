<?php

class DataTypeTest extends TestCase
{

	/**
	 * Setup test
	 */
	protected function setUp()
	{

	}

	public function testCheck()
	{
		$DataType = new DataType();

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
		'SUPPORTS_COLLATION',
		'SUPPORTS_INDEX',
		'SUPPORTS_UNIQUE',
		'SUPPORTS_FULLTEXT',
		'SUPPORTS_SIZE',
		'SUPPORTS_SCALE',
		'SUPPORTS_VALUES',
		'SUPPORTS_UNSIGNED',
		'SUPPORTS_UNSIGNED_ZEROFILL',
		'SUPPORTS_ON_UPDATE_CURRENT_TIMESTAMP',
		'SUPPORTS_AUTO_INCREMENT');


			$options_string = array('GROUP','INPUT_TYPE');

			foreach($types as $type)
			{
				foreach($options_bool as $option)
				{
					$this->assertNotType('bool',$DataType->check($type,$option));
				}
					
				foreach($options_string as $option)
				{
					$this->assertNotType('string',$DataType->check($type,$option));
				}
			}
	}


	public function testBaseType()
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

			$DataType = new DataType();
			foreach($types as $type)
			{
				$this->assertEquals($type,$DataType->getBaseType($type));
			}

	}

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
?>