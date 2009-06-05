<?php

class ViewController extends Controller
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_view;
	private $_db;

	public $view;
	public $schema;

	public $isSent = false;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = '_view';

	public function __construct($id, $module=null) {

		$request = Yii::app()->getRequest();

		$this->view = $request->getParam('view');
		$this->schema = $request->getParam('schema');

		// @todo (rponudic) work with parameters!
		$this->_db = new CDbConnection('mysql:host='.Yii::app()->user->host.';dbname=' . $this->schema, Yii::app()->user->name, Yii::app()->user->password);
		$this->_db->charset = 'utf8';
		$this->_db->active = true;

		View::$db = Column::$db = $this->_db;

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

	/**
	 * Shows the table structure
	 */
	public function actionStructure()
	{
		$view = $this->loadView();

		$this->render('structure',array(
			'view' => $view,
		));
	}

	/**
	 * Browse the rows of a table
	 */
	public function actionBrowse($_query = false)
	{

		$db = $this->_db;
		$error = false;

		$response = new AjaxResponse();

		// Profiling
		$profiling = Yii::app()->user->settings->get('profiling');

		if(!$_query)
		{
			$queries = (array)self::getDefaultQuery();
		}
		else
		{
			if($profiling)
			{
				$cmd = $db->createCommand('FLUSH STATUS');
				$cmd->execute();

				$cmd = $db->createCommand('SET PROFILING = 1');
				$cmd->execute();
			}

			$splitter = new SqlSplitter($_query);
			$queries = $splitter->getQueries();

		}

		$queryCount = count($queries);

		$i = 1;
		foreach($queries AS $query)
		{

			$sqlQuery = new SqlQuery($query);
			$type = $sqlQuery->getType();

			// SELECT
			if($type == "select")
			{

				// Pagination
				$pages = new CPagination;
				$pages->setPageSize(self::PAGE_SIZE);
				$pages->route = '#tables/'.$this->view.'/browse';

				// Sorting
				$sort = new Sort($db);
				$sort->multiSort = false;
				$sort->route = '#tables/'.$this->view.'/browse';

				$sqlQuery->applyCalculateFoundRows();

				// Apply limit
				if(!$sqlQuery->getLimit())
				{
					$offset = (isset($_GET['page']) ? (int)$_GET['page'] : 1) * self::PAGE_SIZE - self::PAGE_SIZE;
					$sqlQuery->applyLimit(self::PAGE_SIZE, $offset, true);
				}

				// Apply sort
				$sqlQuery->applySort($sort->getOrder(), true);


			}

			// OTHER
			elseif($type == "insert" || $type == "update" || $type == "delete")
			{
				#predie("insert / update / delete statement");

			}
			elseif($type == "show")
			{
				// show create table etc.

			}
			elseif($type == "analyze" || $type == "optimize" || $type == "repair" || $type == "check")
			{
				// Table functions
			}
			elseif($type == "use")
			{
				$name = $sqlQuery->getDatabase();
				if($queryCount == 1 && $name && $this->schema != $name)
				{
					$response->redirectUrl = Yii::app()->baseUrl . '/schema/' . $name . '#sql';
					$response->addNotification('success', Yii::t('message', 'successChangeDatabase', array('{name}' => $name)));
				}
			}
			elseif($type == "create")
			{


				//$name = $sqlQuery->getTable();
			}
			else
			{

			}

			// Prepare query for execution
			$cmd = $db->createCommand($sqlQuery->getQuery());
			$cmd->prepare();

			if($sqlQuery->returnsResultSet())
			{

				try
				{
					// Fetch data
					$data = $cmd->queryAll();

					if($type == 'select')
					{
						$total = (int)$db->createCommand('SELECT FOUND_ROWS()')->queryScalar();
						$pages->setItemCount($total);
					}

					$columns = array();

					// Fetch column headers
					if($total > 0 || isset($data[0]))
					{
						$columns = array_keys($data[0]);
					}


				}
				catch (Exception $ex)
				{
					$error = $ex->getMessage();
				}


			}
			else
			{

				try
				{
					// Measure time
					$start = microtime(true);
					$result = $cmd->execute();
					$time = round(microtime(true) - $start, 10);

					$response->addNotification('success', Yii::t('message', 'successExecuteQuery'), Yii::t('message', 'affectedRowsQueryTime', array($result,  '{rows}'=>$result, '{time}'=>$time)), $sqlQuery->getQuery());


				}
				catch(CDbException $ex)
				{
					$dbException = new DbException($cmd);
					$response->addNotification('error', Yii::t('message', 'sqlErrorOccured', array('{errno}'=>$dbException->getCode(), '{errmsg}'=>$dbException->getText())));
				}

			}

			$i++;


		}

		if($profiling)
		{
			$cmd = $db->createCommand('select
					state,
					SUM(duration) as total,
					count(*)
				FROM information_schema.profiling
				GROUP BY state
				ORDER by total desc');

			$cmd->prepare();
			$profileData = $cmd->queryAll();

			if(count($profileData))
			{
				$test = '<table>';

				foreach($profileData AS $item)
				{
					$test .= '<tr>';

					$i = 0;
					foreach($item AS $value)
					{
						$test .= '<td style="padding: 2px; min-width: 80px;">' . ($i == 0 ? '<b>' . ucfirst($value) . '</b>' : $value)  . '</td>';
						$i++;
					}


					$test .= '</tr>';
				}

				$test .= '</table>';

				$response->addNotification('info', 'Profling results (sorted by execution time)', $test, null, array('isSticky'=>false));
			}

		}


		$this->render('browse',array(
			'data' => $data,
			'columns' => $columns,
			'query' => $sqlQuery->getOriginalQuery(),
			'pages' => $pages,
			'sort' => $sort,
			'error' => $error,
			'table' => $this->_db->getSchema()->getTable($this->view),
			'response' => $response,
			'type' => $type,
		));

	}

	/*
	 * Execute Sql
	 */
	public function actionSql() {

		$query = Yii::app()->getRequest()->getParam('query');

		if(!$query)
		{
			$this->isSent = true;

			$this->render('browse',array(
				'data' => array(),
				'query' => self::getDefaultQuery(),
			));
		}
		else
			self::actionBrowse($query);

	}

	public function actionSearch() {

		$operators = array(
			'LIKE',
			'NOT LIKE',
			'=',
			'!=',
			'REGEXP',
			'NOT REGEXP',
			'IS NULL',
			'IS NOT NULL',
		);

		Row::$db = $this->_db;
		$row = new Row;

		$db = $this->_db;
		$commandBuilder = $this->_db->getCommandBuilder();

		if(isset($_POST['Row']))
		{

			$this->isSent = true;

			$criteria = new CDbCriteria;

			$i = 0;
			foreach($_POST['Row'] AS $column=>$value) {

				if($value)
				{
					$operator = $operators[$_POST['operator'][$column]];
					$criteria->condition .= ($i>0 ? ' AND ' : ' ') . $db->quoteColumnName($column) . ' ' . $operator . ' ' . $db->quoteValue($value);

					$i++;
				}

			}

			$query = $db->getCommandBuilder()->createFindCommand($this->view, $criteria)->getText();

			self::actionBrowse($query);

		}
		else
		{
			$this->render('/table/search', array(
				'row' => $row,
				'operators'=>$operators,
			));
		}

	}

	/**
	 * Creates a view.
	 */
	public function actionCreate()
	{
		$this->layout = false;

		$view = new View();

		if(isset($_POST['query']))
		{
			$query = $_POST['query'];
			$cmd = $this->_db->createCommand($query);
			try
			{
				$cmd->prepare();
				$cmd->execute();
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('message', 'successAddView'),
					null,
					$query);
				$response->refresh = true;
				$response->send();
			}
			catch(CDbException $ex)
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				$view->addError(null, Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			}
		}
		else
		{
			$query = 'CREATE VIEW ' . $this->_db->quoteTableName('name_of_view') . ' AS' . "\n"
				. '-- Definition start' . "\n\n"
				. '-- Definition end';
		}

		CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3);
		$this->render('form', array(
			'view' => $view,
			'query' => $query,
		));
	}

	/**
	 * Drops tables
	 */
	public function actionDrop()
	{
		$response = new AjaxResponse();
		$response->refresh = true;
		$views = (array)$_POST['views'];
		$droppedViews = $droppedSqls = array();

		foreach($views AS $view)
		{
			$viewObj = View::model()->findByPk(array(
				'TABLE_SCHEMA' => $this->schema,
				'TABLE_NAME' => $view,
			));
			try
			{
				$sql = $viewObj->delete();
				$droppedViews[] = $view;
				$droppedSqls[] = $sql;
			}
			catch(DbException $ex)
			{
				$response->addNotification('error',
					Yii::t('message', 'errorDropView', array('{view}' => $view)),
					$ex->getText(),
					$ex->getSql());
			}
		}

		$count = count($droppedViews);
		if($count > 0)
		{
			$response->addNotification('success',
				Yii::t('message', 'successDropView', array($count, '{view}' => $droppedViews[0], '{viewCount}' => $count)),
				($count > 1 ? implode(', ', $droppedViews) : null),
				implode("\n", $droppedSqls));
		}

		$response->send();
	}

	/**
	 * Updates a view.
	 */
	public function actionUpdate()
	{
		$this->layout = false;

		$view = View::model()->findByPk(array(
			'TABLE_SCHEMA' => $this->schema,
			'TABLE_NAME' => $this->view,
		));

		if(isset($_POST['query']))
		{
			$query = $_POST['query'];
			$cmd = $this->_db->createCommand($query);
			try
			{
				$cmd->prepare();
				$cmd->execute();
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('message', 'successAlterView', array('{view}' => $view->TABLE_NAME)),
					null,
					$query);
				$response->refresh = true;
				$response->send();
			}
			catch(CDbException $ex)
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				$view->addError(null, Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			}
		}
		else
		{
			$query = $view->getAlterView();
		}

		CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3);
		$this->render('form', array(
			'view' => $view,
			'query' => $query,
		));
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

		$pages=new CPagination(Schema::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$criteria->group = 'SCHEMA_NAME';
		$criteria->select = 'COUNT(*) AS tableCount';

		$schemaList = Schema::model()->with(array(
			"table" => array('select'=>'COUNT(*) AS tableCount')
		))->together()->findAll($criteria);

		$this->render('list',array(
			'schemaList'=>$schemaList,
			'pages'=>$pages,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadView($id = null)
	{
		if($this->_view === null)
		{
			if($id !== null || ($this->view && $this->schema))
			{

				$criteria = new CDbCriteria;
				$criteria->condition = 'TABLE_SCHEMA = :schema AND TABLE_NAME = :table';
				$criteria->params = array(
					':schema' => $this->schema,
					':table' => $this->view,
				);

				$pk = array(
					'TABLE_SCHEMA' => $this->schema,
					'TABLE_NAME' => $this->view,
				);
				$view = View::model()->find($criteria);
				$view->columns = Column::model()->findAll($criteria);
				$this->_view = $view;
			}

			if($this->_view === null)
			{
				throw new CHttpException(500,'The requested view does not exist.');
			}
		}

		return $this->_view;
	}

	/*
	 * Private functions
	 */
	private function getDefaultQuery()
	{
		return 'SELECT * FROM ' . $this->_db->quoteTableName($this->view) .
			"\n\t" . 'WHERE 1';
	}

}