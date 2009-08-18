<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
 * 
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */

class TriggerTest extends TestCase
{
	/**
	 * Setup test databases.
	 */
	protected function setUp()
	{
		$this->executeSqlFile('models/Trigger.sql');
		$db = new CDbConnection('mysql:host='.DB_HOST.';dbname=triggertest', DB_USER, DB_PASSWORD);
		$db->active = true;
		Trigger::$db = $db;
	}


	/**
	 * tries to load a trigger and check the attributes
	 */
	public function testLoad()
	{
		$triggerObj = Trigger::model()->findByPk(array(
			'TRIGGER_SCHEMA' => 'triggertest',
			'TRIGGER_NAME' => 'trigger1'
		));

		$this->assertType('trigger', $triggerObj);
		$this->assertEquals('triggertest',$triggerObj->TRIGGER_SCHEMA);
		$this->assertEquals('trigger1',$triggerObj->TRIGGER_NAME);

	}

	/**
	 * tests some config
	 */
	public function testConfig()
	{
		$t = new Trigger();

		$this->assertType('trigger', Trigger::Model());
		$this->assertType('string', $t->tableName());
		$this->assertType('array', $t->primaryKey());
	}

	/**
	 * tests return value of getCreateTrigger method
	 * and tries to insert a triggesr
	 */
	public function testGetCreateTrigger()
	{


		$triggerObj = Trigger::model()->findByPk(array(
			'TRIGGER_SCHEMA' => 'triggertest',
			'TRIGGER_NAME' => 'trigger1'
		));

		$createTrigger = $triggerObj->getCreateTrigger();

		$this->assertType('string', $createTrigger);


		$this->assertType('string', $triggerObj->delete());


		$cmd = Trigger::$db->createCommand($createTrigger);
		$this->assertEquals(0, $cmd->execute());

		$triggerObj = Trigger::model()->findByPk(array(
			'TRIGGER_SCHEMA' => 'triggertest',
			'TRIGGER_NAME' => 'trigger1'
		));

		$this->assertType('Trigger', $triggerObj);
	}


}