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
		var_dump($DataType->check('float', DataType::SUPPORTS_SCALE));
	}


	public function testBaseType()
	{
		$DataType = new DataType();
		var_dump($DataType->getBaseType('float'));
	}

	public function testGetInputType()
	{
		$DataType = new DataType();
		var_dump($DataType->getInputType('int'));

	}


}
?>