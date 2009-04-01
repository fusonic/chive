<?php

class DatabaseController extends CController
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_database;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = "database";

	public function __construct($id, $module=null) {

		if(Yii::app()->request->isAjaxRequest)
			$this->layout = false;

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
		$this->render('show',array('database'=>$this->loadDatabase()));
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
				echo 'redirect:database/' . $database->SCHEMA_NAME;
				Yii::app()->end();
			}
		}

		$collations = Collation::model()->findAll(array('order' => 'COLLATION_NAME', 'select'=>'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'));

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
		$database = $this->loadDatabase();
		if(isset($_POST['Database']))
		{
			$database->attributes = $_POST['Database'];
			if($database->save())
			{
				$helperId = "helper_" . mt_rand(1000, 9999);
				echo '<span id="' . $helperId . '" />'
					. '<script type="text/javascript">'
					. '$("#' . $helperId . '").parents("tr").prev().children("td").effect("highlight", {}, 2000);'
					. '$("#' . $helperId . '").parents("tr").remove();'
					. '</script>';
				Yii::app()->end();
			}
		}

		$collations = Collation::model()->findAll(array('order' => 'COLLATION_NAME', 'select'=>'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'));

		$this->render('form', array(
			'database' => $database,
			'collations' => $collations,
		));
	}

	/**
	 * Deletes a particular user.
	 * If deletion is successful, the browser will be redirected to the 'list' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadUser()->delete();
			$this->redirect(array('list'));
		}
		else
		throw new CHttpException(500,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all users.
	 */
	public function actionList()
	{

		$collations = Collation::model()->findAll(array('order' => 'COLLATION_NAME', 'select'=>'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'));

		$criteria=new CDbCriteria;

		$pages=new CPagination(Database::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$criteria->group = 'SCHEMA_NAME';
		$criteria->select = 'COUNT(*) AS tableCount';

		$databaseList = Database::model()->with(array(
			"table" => array('select'=>'COUNT(*) AS tableCount'),
			"collation"
		))->together()->findAll($criteria);

		$this->render('list',array(
			'databaseList'=>$databaseList,
			'pages'=>$pages,
			'collations'=>$collations
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
				$this->_database = Database::model()->find("SCHEMA_NAME = '" . $_GET['schema'] . "'");
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
}