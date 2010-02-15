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


class ViewTest extends ChiveTestCase
{
	/**
	 * Setup test databases.
	 */
	protected function setUp()
	{
		$this->executeSqlFile('models/ViewTest.sql');
		
		View::$db = $this->createDbConnection('viewtest');
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