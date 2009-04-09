<?php

class SchemaController extends CController
{
	const PAGE_SIZE = 10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction = 'list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_schema;

	public $schema;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = 'schema';

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

	public function actionIndex()
	{
		$this->render('index');
	}

	/**
	 * Shows a particular user.
	 */
	public function actionShow()
	{

		$schema = $this->loadSchema();

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

		$this->_schema->tables = Table::model()->findAll($criteria);
		$this->_schema->tableCount = Table::model()->count($criteria);

		$this->render('show',array(
			'schema' => $schema,
			'sort' => $sort,
		));
	}

	/**
	 * Creates a new user.
	 * If creation is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionCreate()
	{
		$schema = new Schema;
		if(isset($_POST['Schema']))
		{
			$schema->attributes = $_POST['Schema'];
			if($schema->save())
			{
				Yii::app()->end('redirect:schema/' . $schema->SCHEMA_NAME);
			}
		}

		$collations = Collation::model()->findAll(array(
			'order' => 'COLLATION_NAME',
			'select'=> 'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'
		));

		$this->render('form', array(
			'schema' => $schema,
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
		$schema = $this->loadSchema();
		if(isset($_POST['Schema']))
		{
			$schema->attributes = $_POST['Schema'];
			if($schema->save())
			{
				$isSubmitted = true;
			}
		}

		$collations = Collation::model()->findAll(array(
			'order' => 'COLLATION_NAME',
			'select'=>'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'
		));

		$this->render('form', array(
			'schema' => $schema,
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
				$schema = Schema::model()->findByPk($schema);
				$schema->delete();
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
		$pages = new CPagination(Schema::model()->count($criteria));
		$pages->pageSize = self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		// Sort
		$sort = new CSort('Schema');
		$sort->attributes = array(
			'SCHEMA_NAME' => 'name',
			'tableCount' => 'tableCount',
			'DEFAULT_COLLATION_NAME' => 'collation',
		);
		$sort->defaultOrder = 'SCHEMA_NAME ASC';
		$sort->applyOrder($criteria);

		$criteria->group = 'SCHEMA_NAME';
		$criteria->select = 'COUNT(*) AS tableCount';

		$schemaList = Schema::model()->with(array(
			'table' => array('select' => 'COUNT(??.TABLE_NAME) AS tableCount'),
		))->together()->findAll($criteria);

		$this->render('list',array(
			'schemaList' => $schemaList,
			'schemaCount' => $pages->getItemCount(),
			'schemaCountThisPage' => min($pages->getPageSize(), $pages->getItemCount() - $pages->getCurrentPage() * $pages->getPageSize()),
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
	public function loadSchema($id=null)
	{
		if($this->_schema===null)
		{
			if($id!==null || isset($_GET['schema']))
			{
				$criteria = new CDbCriteria;
				$criteria->condition = 'SCHEMA_NAME = :schema';
				$criteria->params = array(
					':schema' => $this->schema,
				);

				$this->_schema = Schema::model()->find($criteria);

			}

			if($this->_schema===null)
			{
				throw new CHttpException(500,'The requested schema does not exist.');
			}
		}
		return $this->_schema;
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