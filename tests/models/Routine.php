<?php

class RoutineTest extends TestCase
{
	protected function setUp()
	{
		$this->executeSqlFile('models/Schema.sql');
		Routine::$db = new CDbConnection('mysql:host='.DB_HOST.';dbname=information_schema', DB_USER, DB_PASSWORD);
	
	}

	public function testModel()
	{
		$this->assertType('array',Routine::Model()->findAll());

	}

	public function testConfig()
	{
		$this->assertType('array',Routine::primaryKey());
		$this->assertType('string',Routine::tableName());
	}
	
	
	public function testDelete()
	{
		
		
	}
}

?>