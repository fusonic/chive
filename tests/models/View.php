<?php

class ViewTest extends TestCase
{
	/**
	 * Setup test databases.
	 */
	protected function setUp()
	{
		$this->executeSqlFile('models/View.sql');
		$db = new CDbConnection('mysql:host='.DB_HOST.';dbname=viewtest', DB_USER, DB_PASSWORD);
		$db->active = true;
		View::$db = $db;
	}


	/**
	 * tests some config
	 */
	public function testConfig()
	{
		$v = new View();

		$this->assertType('view', View::Model());
		$this->assertType('string', $v->tableName());
		$this->assertType('array', $v->primaryKey());
		$this->assertType('array', $v->relations());
		$this->assertType('array', $v->attributeLabels());
	}


	/**
	 * tries to read a view
	 */
	public function testGet()
	{
		$viewObj = View::model()->findByPk(array(
		'TABLE_SCHEMA' => 'viewtest',
		'TABLE_NAME' => 'view1'
		));

		$this->assertEquals('viewtest', $viewObj->TABLE_SCHEMA);
		$this->assertEquals('view1', $viewObj->TABLE_NAME);
	}

	/**
	 * tries to delete a view
	 */
	public function testDelete()
	{
		$viewObj = View::model()->findByPk(array(
		'TABLE_SCHEMA' => 'viewtest',
		'TABLE_NAME' => 'view1'
		));

		$this->assertType('string', $viewObj->delete());

		$viewObj = View::model()->findByPk(array(
		'TABLE_SCHEMA' => 'viewtest',
		'TABLE_NAME' => 'view1'
		));

		$this->assertNull($viewObj);
	}
	
	/**
	 * tries to alter a view
	 * @todo(mburtscher): try to execute it! 
	 */
	public function testAlter()
	{
		$viewObj = View::model()->findByPk(array(
		'TABLE_SCHEMA' => 'viewtest',
		'TABLE_NAME' => 'view1'
		));
				
		$this->assertType('string', $viewObj->getAlterView());	
	}

	
	/**
	 * tries to create a new view
	 */
	public function testCreate()
	{
		$viewObj = View::model()->findByPk(array(
		'TABLE_SCHEMA' => 'viewtest',
		'TABLE_NAME' => 'view1'
		));
		
		$createView = $viewObj->getCreateView();
		
		$this->assertType('string',$createView);
		$this->assertType('string',$viewObj->delete());
		
		$cmd = View::$db->createCommand($createView);
		$this->assertEquals(0, $cmd->execute());
		
		$viewObj = View::model()->findByPk(array(
		'TABLE_SCHEMA' => 'viewtest',
		'TABLE_NAME' => 'view1'
		));
		
		$this->assertType('View', $viewObj);
	}

}