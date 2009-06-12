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

	public $table;
	public $schema;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = '_table';

	public function __construct($id, $module = null)
	{
		$request = Yii::app()->getRequest();

		$this->table = $request->getParam('table');
		$this->schema = $request->getParam('schema');

		parent::__construct($id, $module);
		$this->connectDb($this->schema);
	}

	/**
	 * Shows the table structure
	 */
	public function actionStructure()
	{
		$table = $this->loadTable();

		// Constraints
		if(StorageEngine::check($table->ENGINE, StorageEngine::SUPPORTS_FOREIGN_KEYS))
		{
			$foreignKeys = array();
			$sql = 'SELECT * FROM KEY_COLUMN_USAGE '
				. 'WHERE TABLE_SCHEMA = :tableSchema '
				. 'AND TABLE_NAME = :tableName '
				. 'AND REFERENCED_TABLE_SCHEMA IS NOT NULL';
			$table->foreignKeys = ForeignKey::model()->findAllBySql($sql, array(
				'tableSchema' => $table->TABLE_SCHEMA,
				'tableName' => $table->TABLE_NAME,
			));
			foreach($table->foreignKeys AS $key)
			{
				$foreignKeys[] = $key->COLUMN_NAME;
			}
		}
		else
		{
			$foreignKeys = false;
		}

		// Indices
		$sql = 'SELECT * FROM STATISTICS '
			. 'WHERE TABLE_SCHEMA = :tableSchema '
			. 'AND TABLE_NAME = :tableName '
			. 'GROUP BY INDEX_NAME '
			. 'ORDER BY INDEX_NAME = \'PRIMARY\' DESC, INDEX_NAME';
		$table->indices = Index::model()->findAllBySql($sql, array(
			'tableSchema' => $table->TABLE_SCHEMA,
			'tableName' => $table->TABLE_NAME,
		));

		foreach($table->indices AS $index)
		{
			$index->columns = IndexColumn::model()->findAllByAttributes(array('TABLE_SCHEMA' => $table->TABLE_SCHEMA, 'TABLE_NAME' => $table->TABLE_NAME, 'INDEX_NAME' => $index->INDEX_NAME));
		}

		// Triggers
		$table->triggers = Trigger::model()->findAllByAttributes(array(
			'EVENT_OBJECT_SCHEMA' => $table->TABLE_SCHEMA,
			'EVENT_OBJECT_TABLE' => $table->TABLE_NAME,
		));

		$this->render('structure',array(
			'table' => $table,
			'canAlter' => Yii::app()->user->privileges->checkTable($table->TABLE_SCHEMA, $table->TABLE_NAME, 'ALTER'),
			'foreignKeys' => $foreignKeys,
		));
	}

	/**
	 * Browse the rows of a table
	 */
	public function actionBrowse($_query = false)
	{

		$db = $this->db;
		$error = false;
		$isSent = false;

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
				$pages = new Pagination();
				$pages->setupPageSize('pageSize', 'schema.table.browse');
				$pages->applyLimit($criteria);
				$pages->route = '#tables/'.$this->table.'/browse';

				// Sorting
				$sort = new Sort($db);
				$sort->multiSort = false;
				$sort->route = '#tables/'.$this->table.'/browse';

				$sqlQuery->applyCalculateFoundRows();

				$pageSize = $pages->getPageSize();

				// Apply limit
				if(!$sqlQuery->getLimit())
				{
					$offset = (isset($_GET['page']) ? (int)$_GET['page'] : 1) * $pageSize - $pageSize;
					$sqlQuery->applyLimit($pageSize, $offset, true);
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
				$response->reload = true;

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

						$keyData = array();
					}

					$columns = array();

					// Fetch column headers
					if($total > 0 || isset($data[0]))
					{
						$columns = array_keys($data[0]);
					}

					$isSent = true;


				}
				catch (CDbException $ex)
				{
					$ex = new DbException($cmd);
					$response->addNotification('error', Yii::t('message', 'executingQueryFailed'), $ex->getText());
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

				$response->addNotification('info', 'Profling results (sorted by execution time)', $test, null);
			}

		}


		$this->render('browse',array(
			'data' => $data,
			'columns' => $columns,
			'query' => $sqlQuery->getOriginalQuery(),
			'isSent' => $isSent,
			'pages' => $pages,
			'sort' => $sort,
			'error' => $error,
			'table' => $this->db->getSchema()->getTable($this->table),
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

		$row = new Row;

		$db = $this->db;
		$commandBuilder = $this->db->getCommandBuilder();

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
	 * Drops tables
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
	 * Loads the current table.
	 *
	 * @return	Table
	 */
	public function loadTable()
	{
		if(is_null($this->_table))
		{
			$pk = array(
				'TABLE_SCHEMA' => $this->schema,
				'TABLE_NAME' => $this->table,
			);
			$this->_table = Table::model()->findByPk($pk);
			$this->_table->columns = Column::model()->findAllByAttributes($pk);

			if(is_null($this->_table))
			{
				throw new CHttpException(500, 'The requested table does not exist.');
			}
		}
		return $this->_table;
	}

	/*
	 * Private functions
	 */
	private function getDefaultQuery()
	{
		return 'SELECT * FROM ' . $this->db->quoteTableName($this->table) .
			"\n\t" . 'WHERE 1';
	}

}