<?php

class TableController extends Controller
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_table;
	private $_db;

	public $table;
	public $schema;

	public $isSent = false;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = '_table';

	public function __construct($id, $module=null) {

		$request = Yii::app()->getRequest();

		$this->table = $request->getParam('table');
		$this->schema = $request->getParam('schema');

		// @todo (rponudic) work with parameters!
		$this->_db = new CDbConnection('mysql:host='.Yii::app()->user->host.';dbname=' . $this->schema, Yii::app()->user->name, Yii::app()->user->password);
		$this->_db->charset='utf8';
		$this->_db->active = true;

		Table::$db = Column::$db = Index::$db = $this->_db;

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
		$table = $this->loadTable();

		// Indices
		$sql = 'SELECT * FROM STATISTICS '
			. 'WHERE TABLE_SCHEMA = :tableSchema '
			. 'AND TABLE_NAME = :tableName '
			. 'GROUP BY INDEX_NAME '
			. 'ORDER BY INDEX_NAME = \'PRIMARY\' DESC, INDEX_NAME';
		$params = array(
			'tableSchema' => $table->TABLE_SCHEMA,
			'tableName' => $table->TABLE_NAME,
		);
		$table->indices = Index::model()->findAllBySql($sql, $params);

		foreach($table->indices AS $index)
		{
			$index->columns = IndexColumn::model()->findAllByAttributes(array('TABLE_SCHEMA' => $table->TABLE_SCHEMA, 'TABLE_NAME' => $table->TABLE_NAME, 'INDEX_NAME' => $index->INDEX_NAME));
		}

		$this->render('structure',array(
			'table' => $table,
			'canAlter' => Yii::app()->user->privileges->checkTable($table->TABLE_SCHEMA, $table->TABLE_NAME, 'ALTER'),
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

		#$_query = 'SELECT * FROM test ORDER BY id DESC LIMIT 0, 10';
		//$_query = ' USE test';

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
				$pages->route = '#tables/'.$this->table.'/browse';

				// Sorting
				$sort = new Sort($db);
				$sort->multiSort = false;
				$sort->route = '#tables/'.$this->table.'/browse';

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
			'table' => $this->_db->getSchema()->getTable($this->table),
			'response' => $response,
			'type' => $type,
		));

	}

	public function actionCreate()
	{
		$this->layout = false;

		$table = new Table();
		$column = new Column();
		if(isset($_POST['Table'], $_POST['Column']))
		{
			$table->attributes = $_POST['Table'];
			$column->attributes = $_POST['Column'];

			/*
			 * Add index
			 */
			$addIndices = array();
			if(isset($_POST['createIndexPrimary']))
			{
				$column->createPrimaryKey = true;
			}
			if(isset($_POST['createIndex']))
			{
				$addIndices['INDEX'] = $column->COLUMN_NAME;
			}
			if(isset($_POST['createIndexUnique']))
			{
				$column->createUniqueKey = true;
			}
			if(isset($_POST['createIndexFulltext']))
			{
				$addIndices['FULLTEXT'] = $column->COLUMN_NAME . (array_search($column->COLUMN_NAME, $addIndices) !== false ? '_fulltext' : '');
			}

			if($sql = $table->insert(array($column)))
			{
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('message', 'successAddTable', array('{table}' => $table->TABLE_NAME)),
					null,
					$sql);
				$response->redirectUrl = '#tables/' . $table->TABLE_NAME . '/structure';

				foreach($addIndices AS $type => $indexName)
				{
					try
					{
						$index = new Index();
						$index->throwExceptions = true;
						$index->TABLE_NAME = $table->TABLE_NAME;
						$index->TABLE_SCHEMA = $this->schema;
						$index->INDEX_NAME = $indexName;
						$index->setType($type);
						$indexCol = new IndexColumn();
						$indexCol->COLUMN_NAME = $column->COLUMN_NAME;
						$index->columns = array($indexCol);
						$sql = $index->save();

						$response->addNotification('success',
							Yii::t('message', 'successCreateIndex', array('{index}' => $index->INDEX_NAME)),
							null,
							$sql);
						$response->reload = true;
					}
					catch(DbException $ex)
					{
						$response->addNotification('error',
							Yii::t('message', 'errorCreateIndex', array('{index}' => $index->INDEX_NAME)),
							$ex->getText(),
							$ex->getSql());
					}
				}

				$response->send();
			}
		}

		$collations = Collation::model()->findAll(array(
			'order' => 'COLLATION_NAME',
			'select' => 'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'
		));

		CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3);
		$data = array(
			'table' => $table,
			'column' => $column,
			'collations' => $collations,
			'storageEngines' => StorageEngine::getSupportedEngines(),
		);
		$data['columnForm'] = $this->renderPartial('/column/formBody', $data, true);
		$this->render('form', $data);
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

			$query = $db->getCommandBuilder()->createFindCommand($this->table, $criteria)->getText();

			self::actionBrowse($query);

		}
		else
		{
			$this->render('search', array(
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

			$sql = 'INSERT INTO ' . $db->quoteTableName($this->table) . ' (';

			$attributesCount = count($row->getAttributes());

			$i = 0;
			foreach($row->getAttributes() AS $attribute=>$value)
			{
				$sql .= "\n\t" . $db->quoteColumnName($attribute);

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
				$response->redirectUrl = '#tables/' . $this->table . '/browse';

			}
			catch (CDbException $ex)
			{
				$ex = new DbException($cmd);
				$response->addNotification('error', Yii::t('message', 'errorInsertRow'), $ex->getText(), $sql);
			}

			$response->send();

		}

		/*
		$table = $this->loadTable();

		if(isset($_POST['sent'])) {

			$builder = $this->_db->getCommandBuilder();

			$data = array();
			foreach($table->columns AS $column) {
				$data[$column->COLUMN_NAME] = $_POST[$column->COLUMN_NAME];
			}

			$cmd = $builder->createInsertCommand($this->table, $data);

			try
			{
				$cmd->prepare();
				$cmd->execute();
				Yii::app()->end('redirect:' . $this->schema . '#tables/' . $this->table . '/browse');
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
					Yii::t('message', 'errorTruncateTable', array('{table}' => $this->table)),
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
			$tableObj = Table::model()->findByPk(array(
				'TABLE_SCHEMA' => $this->schema,
				'TABLE_NAME' => $table
			));
			try
			{
				$sql = $tableObj->delete();
				$droppedTables[] = $table;
				$droppedSqls[] = $sql;
			}
			catch(DbException $ex)
			{
				$response->addNotification('error',
					Yii::t('message', 'errorDropTable', array('{table}' => $table)),
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
	 * Updates a table.
	 */
	public function actionUpdate()
	{
		$this->layout = false;

		$isSubmitted = false;
		$sql = false;
		$table = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => $this->schema,
			'TABLE_NAME' => $this->table,
		));

		if(isset($_POST['Table']))
		{
			$table->attributes = $_POST['Table'];
			$sql = $table->save();
			if($sql)
			{
				$isSubmitted = true;
			}
		}

		$collations = Collation::model()->findAll(array(
			'order' => 'COLLATION_NAME',
			'select'=>'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'
		));

		CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3);
		$this->render('form', array(
			'table' => $table,
			'collations' => $collations,
			'storageEngines' => StorageEngine::getSupportedEngines(),
			'isSubmitted' => $isSubmitted,
			'sql' => $sql,
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
	public function loadTable($id = null)
	{
		if($this->_table === null)
		{
			if($id !== null || ($this->table && $this->schema))
			{
				$pk = array(
					'TABLE_SCHEMA' => $this->schema,
					'TABLE_NAME' => $this->table,
				);
				$table = Table::model()->findByPk($pk);
				$table->columns = Column::model()->findAllByAttributes($pk);
				$this->_table = $table;
			}

			if($this->_table === null)
			{
				throw new CHttpException(500,'The requested table does not exist.');
			}
		}
		return $this->_table;
	}

	/*
	 * Private functions
	 */
	private function getDefaultQuery()
	{
		return 'SELECT * FROM ' . $this->_db->quoteTableName($this->table) .
			"\n\t" . 'WHERE 1';
	}

}