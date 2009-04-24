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

	public function getViewPath()
	{
		return parent::getViewPath();


		// @todo (rponudic) is this needed anymore?
		if(($module=$this->getModule())===null)
			$module=Yii::app();
		return $module->getViewPath().'/table';
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
	 * Insert a new row
	 * If creation is successful, the browser will be redirected to the 'browse' page.
	 */
	public function actionInsert()
	{

		$db = $this->_db;

		Row::$db = $this->_db;
		$row = new Row;

		$functions = array(
			'',
			'ASCII',
			'CHAR',
			'MD5',
			'SHA1',
			'ENCRYPT',
			'RAND',
			'LAST_INSERT_ID',
			'UNIX_TIMESTAMP',
			'COUNT',
			'AVG',
			'SUM',
			'SOUNDEX',
			'LCASE',
			'UCASE',
			'NOW',
			'PASSWORD',
			'OLD_PASSWORD',
			'COMPRESS',
			'UNCOMPRESS',
			'CURDATE',
			'CURTIME',
			'UTC_DATE',
			'UTC_TIME',
			'UTC_TIMESTAMP',
			'FROM_DAYS',
			'FROM_UNIXTIME',
			'PERIOD_ADD',
			'PERIOD_DIFF',
			'TO_DAYS',
			'USER',
			'WEEKDAY',
			'CONCAT',
			'HEX',
			'UNHEX',
		);

		//predie($_POST);

		if(isset($_POST['Row']))
		{

			$row->isNewRecord = true;
			$row->attributes = $_POST['Row'];

			$sql = 'INSERT INTO ' . $db->quoteTableName($this->view) . ' (';

			$attributesCount = count($row->getAttributes());

			$i = 0;
			foreach($row->getAttributes() AS $attribute=>$value)
			{
				$sql .= "\n\t" . $attribute;

				$i++;

				if($i < $attributesCount)
					$sql .= ', ';
			}

			$sql .= "\n" . ') VALUES (';

			$i = 0;
			foreach($row->getAttributes() AS $attribute=>$value)
			{
				// NULL value
				if(isset($_POST[$attribute]['null']))
				{
					$sql .= "\n\t" . 'NULL';
				}

				// FUNCTION
				elseif(isset($_POST[$attribute]['function']) && $_POST[$attribute]['function'])
				{
					$sql .= "\n\t" . $functions[$_POST[$attribute]['function']] . '(' . $db->quoteValue($value) . ')';
				}

				// RAW
				else
				{
					$sql .= "\n\t" . $db->quoteValue($value);
				}

				$i++;

				if($i < $attributesCount)
					$sql .= ', ';


			}

			$sql .= "\n" . ')';

			$cmd = $db->createCommand($sql);

			$response = new AjaxResponse();

			try
			{
				$cmd->prepare();
				$cmd->execute();

				$response->addNotification('success', Yii::t('message', 'successInsertRow'), null, $sql);
				$response->redirectUrl = '#tables/' . $this->view . '/browse';

			}
			catch (CDbException $ex)
			{
				$response->addNotification('error', Yii::t('message', 'errorInsertRow'), $sql);
			}

			$response->send();

		}

		/*
		$table = $this->loadView();

		if(isset($_POST['sent'])) {

			$builder = $this->_db->getCommandBuilder();

			$data = array();
			foreach($table->columns AS $column) {
				$data[$column->COLUMN_NAME] = $_POST[$column->COLUMN_NAME];
			}

			$cmd = $builder->createInsertCommand($this->view, $data);

			try
			{
				$cmd->prepare();
				$cmd->execute();
				Yii::app()->end('redirect:' . $this->schema . '#tables/' . $this->view . '/browse');
			}
			catch(CDbException $ex)
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				//$this->addError('SCHEMA_NAME', Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
				return false;
			}

		}
		*/

		$this->render('insert',array(
			'row'=>$row,
			//'table'=>$table,
			'functions'=>$functions,
		));



	}
/**
	 * Truncates tables
	 */
	public function actionTruncate()
	{
		$response = new AjaxResponse();
		$response->reload = true;
		$tables = (array)$_POST['tables'];
		$truncatedTables = $truncatedSqls = array();

		foreach($tables AS $table)
		{
			$pk = array(
				'TABLE_SCHEMA' => $this->schema,
				'TABLE_NAME' => $table
			);
			$table = Table::model()->findByPk($pk);
			try
			{
				$sql = $table->truncate();
				$truncatedTables[] = $table->TABLE_NAME;
				$truncatedSqls[] = $sql;
			}
			catch(DbException $ex)
			{
				$response->addNotification('error',
					Yii::t('message', 'errorTruncateTable', array('{table}' => $this->view)),
					$ex->getText(),
					$ex->getSql());
			}
		}

		$count = count($truncatedTables);
		if($count > 0)
		{
			$response->addNotification('success',
				Yii::t('message', 'successTruncateTable', array($count, '{table}' => $truncatedTables[0], '{tableCount}' => $count)),
				($count > 1 ? implode(', ', $truncatedTables) : null),
				implode("\n", $truncatedSqls));
		}

		$response->send();
	}

	/**
	 * Drops the table
	 */
	public function actionDrop()
	{
		$response = new AjaxResponse();
		$response->reload = true;
		$tables = (array)$_POST['tables'];
		$droppedTables = $droppedSqls = array();

		foreach($tables AS $table)
		{
			$pk = array(
				'TABLE_SCHEMA' => $this->schema,
				'TABLE_NAME' => $table
			);
			$table = Table::model()->findByPk($pk);
			try
			{
				$sql = $table->drop();
				$droppedTables[] = $table->TABLE_NAME;
				$droppedSqls[] = $sql;
			}
			catch(DbException $ex)
			{
				$response->addNotification('error',
					Yii::t('message', 'errorDropTable', array('{table}' => $this->view)),
					$ex->getText(),
					$ex->getSql());
			}
		}

		$count = count($droppedTables);
		if($count > 0)
		{
			$response->addNotification('success',
				Yii::t('message', 'successDropTable', array($count, '{table}' => $droppedTables[0], '{tableCount}' => $count)),
				($count > 1 ? implode(', ', $droppedTables) : null),
				implode("\n", $droppedSqls));
		}

		$response->send();
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