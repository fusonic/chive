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
	 * tests some config 
	 */
	public function testConfig()
	{
		$this->assertType('Routine', Routine::model());
		
		$routine = new Routine();
		$this->assertType('array', $routine->primaryKey());
		$this->assertType('string', $routine->tableName());
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

		$this->assertType('string', $routineObj->delete());
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

		$createRoutine = $routineObj->getCreateRoutine();
		
		$this->assertType('string', $createRoutine);
		$this->assertType('string', $routineObj->delete());
		
		$cmd = Routine::$db->createCommand($createRoutine);
		$this->assertEquals(0, $cmd->execute());
		
		$routineObj = Routine::model()->findByPk(array(
			'ROUTINE_SCHEMA' => 'routinetest',
			'ROUTINE_NAME' => 'test_procedure',
		));
		
		$this->assertType('Routine', $routineObj);
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

		$this->assertType('string', $function->delete());
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

		$createRoutine = $function->getCreateRoutine();
		
		$this->assertType('string', $createRoutine);
		
		$this->assertType('string', $createRoutine);
		$this->assertType('string', $function->delete());
		
		$cmd = Routine::$db->createCommand($createRoutine);
		$this->assertEquals(0, $cmd->execute());
		
		$function = Routine::model()->findByPk(array(
			'ROUTINE_SCHEMA' => 'routinetest',
			'ROUTINE_NAME' => 'test_function',
		));
		
		$this->assertType('Routine', $function);
	}

}
