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
	public function actionBrowse()
	{

		$browsePage = new BrowsePage();

		$browsePage->schema = $this->schema;
		$browsePage->table = $this->table;
		$browsePage->db = $this->db;
		$browsePage->route = '#tables/' . $this->table . '/browse';

		$browsePage->run();

		$this->render('../global/browse', array(
			'model' => $browsePage
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

		$browsePage = new BrowsePage($query);

		$browsePage->schema = $this->schema;
		$browsePage->table = $this->table;
		$browsePage->db = $this->db;
		$browsePage->route = '#tables/' . $this->table . '/browse';
		$browsePage->execute = (bool)$query;

		$browsePage->run();

		$this->render('../global/browse', array(
			'model' => $browsePage
		));

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

}