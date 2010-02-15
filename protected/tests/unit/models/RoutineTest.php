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


class RoutineTest extends ChiveTestCase
{
	
	/**
	 * set up the database with function and procedure
	 */
	protected function setUp()
	{
		$this->executeSqlFile('models/RoutineTest.sql');
		
		Routine::$db = $this->createDbConnection('routinetest');
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