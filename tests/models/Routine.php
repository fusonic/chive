<?php

class RoutineTest extends TestCase
{
	/**
	 * set up the database with function and procedure
	 */
	protected function setUp()
	{
		$this->executeSqlFile('models/Routine.sql');
		Routine::$db = new CDbConnection('mysql:host='.DB_HOST.';dbname=routinetest', DB_USER, DB_PASSWORD);
		Routine::$db->active = true;
	}

	/**
	 * tests the Model method
	 */ 
	public function testModel()
	{
		$this->assertType('array',Routine::Model()->findAll());
	}

	/**
	 * tests some config 
	 */
	public function testConfig()
	{
		$routin = new Routine();

		$this->assertType('array',$routin->primaryKey());
		$this->assertType('string',$routin->tableName());
	}


	/**
	 * tries to delete a procedure
	 */
	public function testDelete()
	{
		$routineObj = Routine::model()->findByPk(array(
				'ROUTINE_SCHEMA' => 'routinetest',
				'ROUTINE_NAME' => 'test_procedure',
		));

		$this->assertType('string',$routineObj->delete());
	}

	/**
	 * tries to get the create statement of the routine
	 */
	public function testGetRoutine()
	{
		$routineObj = Routine::model()->findByPk(array(
				'ROUTINE_SCHEMA' => 'routinetest',
				'ROUTINE_NAME' => 'test_procedure',
		));

		$this->assertType('string',$routineObj->getCreateRoutine());	
	}

	/**
	 * tries to delete a function
	 */
	public function testDeleteFunction()
	{
		$function = Routine::model()->findByPk(array(
				'ROUTINE_SCHEMA' => 'routinetest',
				'ROUTINE_NAME' => 'test_function',
		));

		$this->assertType('string',$function->delete());
	}

	/**
	 * tries to get the create statement of a function
	 */
	public function testGetRoutineFunction()
	{
		$function = Routine::model()->findByPk(array(
				'ROUTINE_SCHEMA' => 'routinetest',
				'ROUTINE_NAME' => 'test_function',
		));

		$this->assertType('string',$function->getCreateRoutine());
	}



}

?>