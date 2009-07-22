<?php

class PrivilegesController extends Controller
{

	/**
	 * @var Default layout for this controller
	 */
	public $layout = 'schema';

	public function __construct($id, $module = null)
	{
		$request = Yii::app()->getRequest();

		if($request->isAjaxRequest)
		{
			$this->layout = false;
		}

		parent::__construct($id, $module);
		$this->connectDb();
	}

	/**
	 * Connects to the specified schema and assigns it to all models which need it.
	 *
	 * @return	CDbConnection
	 */
	protected function connectDb()
	{
		// Connect to database
		$this->db = new CDbConnection('mysql:host=' . Yii::app()->user->host . ';dbname=mysql',
			Yii::app()->user->name,
			Yii::app()->user->password);

		$this->db->charset='utf8';
		$this->db->active = true;

		// Assign to all models which need it
		User::$db = $this->db;

		// Return connection
		return $this->db;
	}

	public function actionUsers()
	{
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

		$users = User::model()->findAll($criteria);

		$this->render('users', array(
			'users' => $users,
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
			try
			{
				$sql = $userObj->delete();
				$droppedUsers[] = $user;
				$droppedSqls[] = $sql;
			}
			catch(DbException $ex)
			{
				$response->addNotification('error',
					Yii::t('message', 'errorDropUser', array('{user}' => $user)),
					$ex->getText()/*,
					$ex->getSql()*/);
			}
		}

		$count = count($droppedUsers);
		if($count > 0)
		{
			$response->addNotification('success',
				Yii::t('message', 'successDropUser', array($count, '{user}' => $droppedUsers[0], '{userCount}' => $count)),
				($count > 1 ? implode(', ', $droppedUsers) : null)/*,
				implode("\n", $droppedSqls)*/);
		}

		$response->send();
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
					Yii::t('message', 'successAddUser', array('{user}' => $user->User, '{host}' => $user->Host)),
					null/*,
					$sql*/);
				$response->refresh = true;
				$response->send();
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
			'User' => $_GET['user'],
			'Host' => $_GET['host'],
		));
		if(isset($_POST['User']))
		{
			$user->attributes = $_POST['User'];
			if($sql = $user->save())
			{
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('message', 'successUpdateUser', array('{user}' => $user->User, '{host}' => $user->Host)),
					null/*,
					$sql*/);
				$response->refresh = true;
				$response->send();
			}
		}

		$this->render('userForm', array(
			'user' => $user,
		));
	}

}