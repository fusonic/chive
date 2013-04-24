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


class PrivilegesController extends Controller
{

	/**
	 * @var Default layout for this controller
	 */
	public $layout = 'schema';

	private $user, $host, $schema;

	public function __construct($id, $module = null)
	{
		$request = Yii::app()->getRequest();

		if($request->isAjaxRequest)
		{
			$this->layout = false;
		}

		// Get parameters from request
		$request = Yii::app()->getRequest();
		$user = $request->getParam('user');
		if($user)
		{
			$data = User::splitId($user);
			$this->user = $data['User'];
			$this->host = $data['Host'];
		}
		$this->schema = trim($request->getParam('schema'));

		parent::__construct($id, $module);
		$this->connectDb(null);
	}

	/**
	 * Connects to the specified schema and assigns it to all models which need it.
	 *
	 * @return	CDbConnection
	 */
	protected function connectDb($schema)
	{
		// Connect to database
		$connectionString = 'mysql:host=' . Yii::app()->user->host . ';port=' . Yii::app()->user->port . ';dbname=mysql';
		$this->db = new CDbConnection($connectionString, Yii::app()->user->name, Yii::app()->user->password);

		$this->db->charset = 'utf8';
		$this->db->emulatePrepare = true;
		$this->db->active = true;

		// Assign to all models which need it
		ActiveRecord::$db =
		SchemaPrivilege::$db = $this->db;

		// Return connection
		return $this->db;
	}

	public function actionUsers()
	{
		// Create criteria
		$criteria = new CDbCriteria();

		// Pagination
		$pages = new Pagination(User::model()->count($criteria));
		$pages->setupPageSize('pageSize', 'privileges.users');
		$pages->applyLimit($criteria);
		$pages->route = '#privileges/users';

		// Sort
		$sort = new CSort('User');
		$sort->attributes = array(
			'User' => 'username',
			'Host' => 'host',
			'Password = \'\'' => 'password',
		);
		$sort->defaultOrder = 'User ASC';
		$sort->route = '#privileges/users';
		$sort->applyOrder($criteria);

		// Fetch users
		$users = User::model()->findAll($criteria);

		// Render
		$this->render('users', array(
			'users' => $users,
			'pages' => $pages,
			'sort' => $sort,
		));
	}

	public function actionSchemata()
	{
		// Create criteria
		$criteria = new CDbCriteria();
		$criteria->condition = 'Host = :host AND User = :user';
		$criteria->params = array(
			':host' => $this->host,
			':user' => $this->user,
		);

		// Pagination
		$pages = new Pagination(User::model()->count($criteria));
		$pages->setupPageSize('pageSize', 'privileges.userSchemata');
		$pages->applyLimit($criteria);
		$pages->route = '#privileges/users/' . base64_encode($this->user . '@' . $this->host) . '/schemata';

		// Sort
		$sort = new CSort('User');
		$sort->attributes = array(
			'User' => 'username',
			'Host' => 'host',
			'Password = \'\'' => 'password',
		);
		$sort->defaultOrder = 'User ASC';
		$sort->route = '#privileges/users/' . base64_encode($this->user . '@' . $this->host) . '/schemata';
		$sort->applyOrder($criteria);

		// Fetch schemata
		$schemata = SchemaPrivilege::model()->findAll($criteria);

		// Render
		$this->render('userSchemata', array(
			'schemata' => $schemata,
			'user' => $this->user,
			'host' => $this->host,
			'pages' => $pages,
			'sort' => $sort,
		));
	}

	public function actionDropUsers()
	{
		$response = new AjaxResponse();
		$response->refresh = true;
		$users = (array)$_POST['users'];
		$droppedUsers = $droppedSqls = array();

		foreach($users AS $user)
		{
			$pk = User::splitId($user);
			$userObj = User::model()->findByPk($pk);
			$userObj->throwExceptions = true;
			try
			{
				$sql = $userObj->delete();
				$droppedUsers[] = '\'' . $userObj->User . '\'@\'' . $userObj->Host . '\'';
				$droppedSqls[] = $sql;
			}
			catch(DbException $ex)
			{
				$response->addNotification('error',
					Yii::t('core', 'errorDropUser', array('{user}' => '\'' . $userObj->User . '\'@\'' . $userObj->Host . '\'')),
					$ex->getText(),
					$ex->getSql());
			}
		}

		$count = count($droppedUsers);
		if($count > 0)
		{
			$response->addNotification('success',
				Yii::t('core', 'successDropUser', array($count, '{user}' => $droppedUsers[0], '{userCount}' => $count)),
				($count > 1 ? implode(', ', $droppedUsers) : null),
				implode("\n", $droppedSqls));
		}

		$this->sendJSON($response);
	}

	/**
	 * Create a new user.
	 */
	public function actionCreateUser()
	{
		$user = new User();
		if(isset($_POST['User']))
		{
			$user->attributes = $_POST['User'];
			if($sql = $user->save())
			{
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('core', 'successAddUser', array('{user}' => $user->User, '{host}' => $user->Host)),
					null,
					$sql);
				$response->refresh = true;
				$this->sendJSON($response);
			}
		}

		$this->render('userForm', array(
			'user' => $user,
		));
	}

	/**
	 * Update an existing user.
	 */
	public function actionUpdateUser()
	{
		$user = User::model()->findByPk(array(
			'User' => $this->user,
			'Host' => $this->host,
		));
		if(isset($_POST['User']))
		{
			$user->attributes = $_POST['User'];
			if($sql = $user->save())
			{
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('core', 'successUpdateUser', array('{user}' => $user->User, '{host}' => $user->Host)),
					null,
					$sql);

				$this->logoutIfPasswordChanged($user);

				$response->refresh = true;
				$this->sendJSON($response);
			}
		}

		$this->render('userForm', array(
			'user' => $user,
		));
	}

	public function logoutIfPasswordChanged($user)
	{
		if(Yii::app()->user->name == $user->User
			   && isset($_POST['User']["plainPassword"]))
		{
			Yii::app()->user->logout();
		}
	}

	public function actionCreateSchema()
	{
		$schema = new SchemaPrivilege();
		$schema->User = $this->user;
		$schema->Host = $this->host;
		if(isset($_POST['SchemaPrivilege']))
		{
			$schema->attributes = $_POST['SchemaPrivilege'];
			if($sql = $schema->save())
			{
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('core', 'successAddSchemaSpecificPrivileges', array('{user}' => $schema->User, '{host}' => $schema->Host, '{schema}' => $schema->Db)),
					null/*,
					$sql*/);
				$response->refresh = true;
				$this->sendJSON($response);
			}
		}

		// Prepare all schemata
		$schemata = $existing = array();
		$allSchemata = Schema::model()->findAll();
		$allExisting = SchemaPrivilege::model()->findAllByAttributes(array(
			'User' => $this->user,
			'Host' => $this->host,
		));
		foreach($allExisting AS $existing1)
		{
			$existing[] = $existing1->Db;
		}
		foreach($allSchemata AS $schema1)
		{
			if(array_search($schema1->SCHEMA_NAME, $existing) === false)
			{
				$schemata[$schema1->SCHEMA_NAME] = $schema1->SCHEMA_NAME;
			}
		}

		$this->render('schemaPrivilegeForm', array(
			'schema' => $schema,
			'schemata' => $schemata,
		));
	}

	public function actionUpdateSchema()
	{
		$schema = SchemaPrivilege::model()->findByPk(array(
			'Host' => $this->host,
			'User' => $this->user,
			'Db' => $this->schema,
		));
		if(isset($_POST['SchemaPrivilege']))
		{
			$schema->attributes = $_POST['SchemaPrivilege'];
			if($sql = $schema->save())
			{
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('core', 'successUpdateSchemaSpecificPrivileges', array('{user}' => $schema->User, '{host}' => $schema->Host, '{schema}' => $schema->Db)),
					null/*,
					$sql*/);
				$response->refresh = true;
				$this->sendJSON($response);
			}
		}

		$this->render('schemaPrivilegeForm', array(
			'schema' => $schema,
		));
	}

	public function actionDropSchema()
	{
		$response = new AjaxResponse();
		$response->refresh = true;
		$schemata = (array)$_POST['schemata'];
		$droppedSchemata = $droppedSqls = array();

		foreach($schemata AS $schema)
		{
			$schemaObj = SchemaPrivilege::model()->findByPk(array(
				'Host' => $this->host,
				'User' => $this->user,
				'Db' => $schema,
			));
			try
			{
				$sql = $schemaObj->delete();
				$droppedSchemata[] = $schema;
				$droppedSqls[] = $sql;
			}
			catch(DbException $ex)
			{
				$response->addNotification('error',
					Yii::t('core', 'errorDropSchemaSpecificPrivileges', array('{user}' => $user)),
					$ex->getText()/*,
					$ex->getSql()*/);
			}
		}

		$count = count($droppedSchemata);
		if($count > 0)
		{
			$tArgs = array(
				$count,
				'{user}' => $this->user,
				'{host}' => $this->host,
				'{schema}' => $droppedSchemata[0],
				'{schemaCount}' => $count,
			);
			$response->addNotification('success',
				Yii::t('core', 'successDropSchemaSpecificPrivileges', $tArgs),
				($count > 1 ? implode(', ', $droppedSchemata) : null)/*,
				implode("\n", $droppedSqls)*/);
		}

		$this->sendJSON($response);
	}

}
