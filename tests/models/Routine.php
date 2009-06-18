<?php

class RoutineTest extends TestCase
{
	protected function setUp()
	{
		$this->executeSqlFile('models/Routine.sql');
		Routine::$db = new CDbConnection('mysql:host='.DB_HOST.';dbname=routinetest', DB_USER, DB_PASSWORD);
		Routine::$db->active = true;
	}

	public function testModel()
	{
		$this->assertType('array',Routine::Model()->findAll());

	}

	public function testConfig()
	{
		$routin = new Routine();
		
		$this->assertType('array',$routin->primaryKey());
		$this->assertType('string',$routin->tableName());
	}


	public function testDelete()
	{
		$routineObj = Routine::model()->findByPk(array(
				'ROUTINE_SCHEMA' => 'routinetest',
				'ROUTINE_NAME' => 'test_procedure',
		));

		$this->assertType('string',$routineObj->delete());
			
	}

	public function testGetRoutine()
	{
		$routineObj = Routine::model()->findByPk(array(
				'ROUTINE_SCHEMA' => 'routinetest',
				'ROUTINE_NAME' => 'test_procedure',
		));

		$this->assertType('string',$routineObj->getCreateRoutine());
			
	}
}

?>