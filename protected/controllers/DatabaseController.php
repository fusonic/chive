<?php

class DatabaseController extends CController
{
	const PAGE_SIZE = 10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction = 'list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_database;

	public $schema;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = 'database';

	public function __construct($id, $module=null) {

		if(Yii::app()->request->isAjaxRequest)
			$this->layout = false;

		$request = Yii::app()->getRequest();
		$this->schema = $request->getParam('schema');

		parent::__construct($id, $module);

	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
					'expression' => !Yii::app()->user->isGuest,
			),
			array('deny',  // deny all users
					'users'=>array('*'),
			),
		);
	}

	/**
	 * Shows a particular user.
	 */
	public function actionShow()
	{

		$database = $this->loadDatabase();

		$criteria = new CDbCriteria;
		$criteria->condition = 'TABLE_SCHEMA = :schema';
		$criteria->params = array(
			':schema' => $this->schema,
		);

		// Sort
		$sort = new CSort('Table');
		$sort->attributes = array(
			'TABLE_NAME' => 'name',
			'TABLE_ROWS' => 'rows',
			'TABLE_COLLATION' => 'collation',
			'ENGINE' => 'engine',
			'DATA_LENGTH' => 'datalength',
			'DATA_FREE' => 'datafree',
		);
		$sort->applyOrder($criteria);

		$this->_database->tables = Table::model()->findAll($criteria);
		$this->_database->tableCount = Table::model()->count($criteria);

		$this->render('show',array(
			'database' => $database,
			'sort' => $sort,
		));
	}

	/**
	 * Creates a new user.
	 * If creation is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionCreate()
	{
		$database = new Database;
		if(isset($_POST['Database']))
		{
			$database->attributes = $_POST['Database'];
			if($database->save())
			{
				Yii::app()->end('redirect:database/' . $database->SCHEMA_NAME);
			}
		}

		$collations = Collation::model()->findAll(array(
			'order' => 'COLLATION_NAME',
			'select'=> 'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'
		));

		$this->render('form', array(
			'database' => $database,
			'collations' => $collations,
		));
	}

	/**
	 * Updates a particular user.
	 * If update is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionUpdate()
	{
		$isSubmitted = false;
		$database = $this->loadDatabase();
		if(isset($_POST['Database']))
		{
			$database->attributes = $_POST['Database'];
			if($database->save())
			{
				$isSubmitted = true;
			}
		}

		$collations = Collation::model()->findAll(array(
			'order' => 'COLLATION_NAME',
			'select'=>'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'
		));

		$this->render('form', array(
			'database' => $database,
			'collations' => $collations,
			'helperId' => 'helper_' . mt_rand(1000, 9999),
			'isSubmitted' => $isSubmitted,
		));
	}

	/**
	 * Deletes a particular user.
	 * If deletion is successful, the browser will be redirected to the 'list' page.
	 */
	public function actionDrop()
	{
		foreach($_POST['schema'] AS $schema)
		{
			try
			{
				$database = Database::model()->findByPk($schema);
				$database->delete();
			}
			catch(Exception $ex) { }
		}
		Yii::app()->end();
	}

	/**
	 * Lists all users.
	 */
	public function actionList()
	{

		$criteria = new CDbCriteria();

		// Pagination
		$pages = new CPagination(Database::model()->count($criteria));
		$pages->pageSize = self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		// Sort
		$sort = new CSort('Database');
		$sort->attributes = array(
			'SCHEMA_NAME' => 'name',
			'tableCount' => 'tableCount',
			'DEFAULT_COLLATION_NAME' => 'collation',
		);
		$sort->defaultOrder = 'SCHEMA_NAME ASC';
		$sort->applyOrder($criteria);

		$criteria->group = 'SCHEMA_NAME';
		$criteria->select = 'COUNT(*) AS tableCount';

		$databaseList = Database::model()->with(array(
			"table" => array('select' => 'COUNT(??.TABLE_NAME) AS tableCount'),
			"collation"
		))->together()->findAll($criteria);

		$this->render('list',array(
			'databaseList' => $databaseList,
			'databaseCount' => $pages->getItemCount(),
			'databaseCountThisPage' => min($pages->getPageSize(), $pages->getItemCount() - $pages->getCurrentPage() * $pages->getPageSize()),
			'pages' => $pages,
			'sort' => $sort,
		));
	}

	/**
	 * Manages all users.
	 */
	public function actionAdmin()
	{
		$this->processAdminCommand();

		$criteria=new CDbCriteria;

		$pages=new CPagination(User::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('User');
		$sort->applyOrder($criteria);

		$userList=User::model()->findAll($criteria);

		$this->render('admin',array(
			'userList'=>$userList,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadDatabase($id=null)
	{
		if($this->_database===null)
		{
			if($id!==null || isset($_GET['schema']))
			{
				$criteria = new CDbCriteria;
				$criteria->condition = 'SCHEMA_NAME = :schema';
				$criteria->params = array(
					':schema' => $this->schema,
				);

				$this->_database = Database::model()->find($criteria);

			}

			if($this->_database===null)
			{
				throw new CHttpException(500,'The requested database does not exist.');
			}
		}
		return $this->_database;
	}

	/**
	 * Executes any command triggered on the admin page.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
		{
			$this->loadUser($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}

/*	public function createUrl($route,$params=array(),$ampersand='&')
	{
		if($route==='')
			$route=$this->getId() . '/' . $this->getAction()->getId();
		else if(strpos($route,'/') === false)
			$route=$this->getId() . '/' . $route;
		if($route[0]!=='/' && ($module=$this->getModule())!==null)
			$route=$module->getId().'/'.$route;

		return Yii::app()->createUrl(trim($route,'/'),$params,$ampersand);
	}*/

}