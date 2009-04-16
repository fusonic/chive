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
	private $_db;

	public $schema;
	public $isSent;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = 'schema';

	public function __construct($id, $module=null) {

		$request = Yii::app()->getRequest();
		$this->schema = $request->getParam('schema');

		if($request->isAjaxRequest && $this->schema && $request->pathInfo != 'schemata/update')
			$this->layout = '_schema';

		elseif($request->isAjaxRequest)
			$this->layout = false;

		$this->_db = new CDbConnection('mysql:host='.Yii::app()->user->host.';dbname=' . $this->schema, Yii::app()->user->name, Yii::app()->user->password);
		$this->_db->charset='utf8';
		$this->_db->active = true;

		parent::__construct($id, $module);

	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
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
			array('deny',
					'users'=>array('?'),
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

	public function actionSql($_query = false, $_execute = true) {

		$db = $this->_db;

		$request = Yii::app()->getRequest();
		$query = $_query ? $_query : $request->getParam('query');

		if($query) {

			$pages = new CPagination;
			$pages->setPageSize(self::PAGE_SIZE);

			$sort = new Sort($db);
			$sort->multiSort = false;

			$sort->route = '/schema/sql';

			$oSql = new Sql($query);
			$oSql->applyCalculateFoundRows();

			if(!$oSql->hasLimit)
			{
				$offset = (isset($_GET['page']) ? (int)$_GET['page'] : 1) * self::PAGE_SIZE - self::PAGE_SIZE;
				$oSql->applyLimit(self::PAGE_SIZE, $offset, true);
			}

			$oSql->applySort($sort->getOrder(), true);

			$query = $oSql->getOriginalQuery();

			if($_execute)
			{

				$cmd = $db->createCommand($oSql->getQuery());

				try
				{
					// Fetch data
					$data = $cmd->queryAll();

					$total = (int)$db->createCommand('SELECT FOUND_ROWS()')->queryScalar();
					$pages->setItemCount($total);

					$columns = array();

					// Fetch column headers
					if($total > 0) {
						$columns = array_keys($data[0]);
					}


				}
				catch (Exception $ex)
				{
					$error = $ex->getMessage();
				}

			}


		}

		$this->render('sql', array(
			'data' => $data,
			'columns' => $columns,
			'query' => $query,
			'pages' => $pages,
			'sort' => $sort,
			'error' => $error,
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
		$schemata = (array)$_POST['schema'];

		foreach($schemata AS $schema)
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


	/*
	 * Bookmark actions
	 */

	public function actionShowBookmark()
	{

		$id = Yii::app()->getRequest()->getParam('id');
		$bookmark = Yii::app()->user->settings->get('bookmarks', 'database', $this->schema, 'id', $id);

		self::actionSql($bookmark['query'], false);

	}

}