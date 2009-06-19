<?php

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
	 * and tries to insert a trigger
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

		$this->fail('Deletion of triggers not yet implemented!');
		$cmd = Trigger::$db->createCommand($createTrigger);
		$this->assertEquals(0, $cmd->execute());

		$triggerObj = Trigger::model()->findByPk(array(
			'TRIGGER_SCHEMA' => 'triggertest',
			'TRIGGER_NAME' => 'trigger1'
		));

		$this->assertType('Trigger', $triggerObj);
	}


}