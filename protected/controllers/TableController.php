<?php

class TableController extends CController
{
	const PAGE_SIZE=20;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_table;
	private $_db;

	public $tableName;
	public $schemaName;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = "database";

	public function __construct($id, $module=null) {

		$this->_db = new CDbConnection('mysql:host=web;dbname=' . $_GET['schema'], Yii::app()->user->name, Yii::app()->user->password);
		$this->_db->charset='utf8';
		$this->_db->active = true;

		$this->tableName = $_GET['table'];
		$this->schemaName = $_GET['schema'];

		if(Yii::app()->request->isAjaxRequest) {
			$this->layout = "table";
		}

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
	public function actionStructure()
	{

		$criteria = new CDbCriteria;
		$criteria->condition = 'TABLE_SCHEMA = :schema AND TABLE_NAME = :table';
		$criteria->params = array(
			'schema'=>$this->schemaName,
			'table'=>$this->tableName,
		);

		$columns = Column::model()->findAll($criteria);

		$this->render('structure',array(
			'columns'=>$columns,
		));
	}

	/**
	 * Shows a particular user.
	 */
	public function actionBrowse($_sql)
	{

		// Total count of entries
		$count = $this->_db->createCommand('SELECT COUNT(*) FROM '.$_GET['table'])->queryScalar();

		$pages=new CPagination($count);
		$pages->pageSize=self::PAGE_SIZE;

		$dc=$this->_db->createCommand('SELECT * FROM '.$_GET['table'].' LIMIT '.$pages->getCurrentPage()*self::PAGE_SIZE.','.self::PAGE_SIZE);
		$data=$dc->queryAll();

		// Fetch column headers
		$columns=array();
		foreach($data[0] AS $key=>$value) {
			$columns[] = $key;
		}

		$this->render('browse',array(
			'data'=>$data,
			'columns'=>$columns,
			'pages'=>$pages,
		));

	}

	public function actionSql() {

		predie($_POST);

		$sql = $_POST['sql'];

		self::actionBrowse($sql);

	}

	/**
	 * Creates a new user.
	 * If creation is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionCreate()
	{
	}

	/**
	 * Updates a particular user.
	 * If update is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionUpdate()
	{
	}

	/**
	 * Deletes a particular user.
	 * If deletion is successful, the browser will be redirected to the 'list' page.
	 */
	public function actionDelete()
	{
	}

	/**
	 * Lists all users.
	 */
	public function actionList()
	{
		$criteria=new CDbCriteria;

		$pages=new CPagination(Database::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$criteria->group = 'SCHEMA_NAME';
		$criteria->select = 'COUNT(*) AS tableCount';

		$databaseList = Database::model()->with(array(
			"table" => array('select'=>'COUNT(*) AS tableCount')
		))->together()->findAll($criteria);

		$this->render('list',array(
			'databaseList'=>$databaseList,
			'pages'=>$pages,
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
	public function loadTable($id=null)
	{

		if($this->_table===null)
		{
			if($id!==null || isset($_GET['schema'], $_GET['table']))
			{
				$criteria = new CDbCriteria;
				$criteria->params = array(
					':schema'=>$this->schemaName,
					':table'=>$this->tableName,
				);
				$criteria->condition = 'TABLE_SCHEMA = :schema AND TABLE_NAME = :table';

				$this->_table = Table::model()->find($criteria);

			}

			if($this->_table===null)
				throw new CHttpException(500,'The requested table does not exist.');
		}
		return $this->_table;
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
}