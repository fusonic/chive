<?php

class TableController extends CController
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

		Table::$db = $this->_db;

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

		$indices = array();
		foreach($table->indices AS $index) {
			$indices[$index->INDEX_NAME][] = $index;
		}

		$this->render('structure',array(
			'table' => $table,
			'indices'=>$indices,
		));
	}

	/**
	 * Browse the rows of a table
	 */
	public function actionBrowse($_query = false)
	{

		$db = $this->_db;
		$error = false;

		$pages = new CPagination;
		$pages->setPageSize(self::PAGE_SIZE);

		$sort = new Sort($db);
		$sort->multiSort = false;

		$sort->route = '/table/sql';

		if(!$_query)
			$_query = self::getDefaultQuery();

		$oSql = new Sql($_query);
		$oSql->applyCalculateFoundRows();

		if(!$oSql->hasLimit)
		{
			$offset = (isset($_GET['page']) ? (int)$_GET['page'] : 1) * self::PAGE_SIZE - self::PAGE_SIZE;
			$oSql->applyLimit(self::PAGE_SIZE, $offset, true);
		}

		$oSql->applySort($sort->getOrder(), true);

		$cmd = $db->createCommand($oSql->getQuery());
		$cmd->prepare();

		try
		{
			// Fetch data
			$data = $cmd->queryAll();

			#$data=array();
			#$cmd->execute();

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


		$this->render('browse',array(
			'data' => $data,
			'columns' => $columns,
			'query' => $oSql->getOriginalQuery(),
			'pages' => $pages,
			'sort' => $sort,
			'error' => $error,
			'table' => $this->_db->getSchema()->getTable($this->table),
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

		if(isset($_POST['Row']))
		{

			$row->isNewRecord = true;
			$row->attributes = $_POST['Row'];

			$sql = 'INSERT INTO ' . $db->quoteTableName($this->table) . ' (';

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
				$sql .= "\n\t" . $db->quoteValue($value);

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

				$response->addNotification('success', Yii::t('message', 'insertedNewRow'));
				$response->redirectUrl = '#tables/' . $this->table . '/browse';

			}
			catch (CDbException $ex)
			{
				$response->addNotification('error', Yii::t('message', 'insertingNewRowFailed'), $sql);
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
					Yii::t('message', 'errorDropTable', array('{table}' => $this->table)),
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

	public function actionDropIndex()
	{
		Table::$db = $this->_db;
		$table = $this->loadTable();

		// Get post vars
		$index = Yii::app()->request->getPost('index');
		$type = Yii::app()->request->getPost('type');

		$response = new AjaxResponse();
		try
		{
			$sql = $table->dropIndex($index, $type);
			$response->addNotification('success',
				Yii::t('message', 'successDropIndex', array('{index}' => $index)),
				null,
				$sql);
			$response->addData('success', true);
		}
		catch(DbException $ex)
		{
			$response->addNotification('error',
				Yii::t('message', 'errorDropIndex', array('{index}' => $index)),
				$ex->getText(),
				$ex->getSql());
			$response->addData('success', false);
		}
		$response->send();
	}

	public function actionCreateIndex()
	{
		Table::$db = $this->_db;
		$table = $this->loadTable();

		// Get post vars
		$index = Yii::app()->request->getPost('index');
		$type = Yii::app()->request->getPost('type');
		$columns = (array)Yii::app()->request->getPost('columns');

		$response = new AjaxResponse();
		if($_POST['type'] == 'PRIMARY')
		{
			$response->reload = true;
		}
		try
		{
			$sql = $table->createIndex($index, $type, $columns);
			$response->addNotification('success',
				Yii::t('message', 'successCreateIndex', array('{index}' => $index)),
				null,
				$sql);
			$response->reload = true;
		}
		catch(DbException $ex)
		{
			$response->addNotification('error',
				Yii::t('message', 'errorCreateIndex', array('{index}' => $index)),
				$ex->getText(),
				$ex->getSql());
		}
		$response->send();
	}

	public function actionAlterIndex()
	{
		Table::$db = $this->_db;
		$table = $this->loadTable();

		// Get post vars
		$index = Yii::app()->request->getPost('index');
		$type = Yii::app()->request->getPost('type');
		$columns = (array)Yii::app()->request->getPost('columns');

		$response = new AjaxResponse();
		try
		{
			$sql = $table->dropIndex($index, $type);
			$sql .= "\n\n" . $table->createIndex($index, $type, $columns);
			$response->addNotification('success',
				Yii::t('message', 'successAlterIndex', array('{index}' => $index)),
				null,
				$sql);
		}
		catch(DbException $ex)
		{
			$response->addNotification('error',
				Yii::t('message', 'errorAlterIndex', array('{index}' => $index)),
				$ex->getText(),
				$ex->getSql());
			$response->reload = true;
		}
		$response->send();
	}

	public function actionRenameIndex()
	{
		Table::$db = $this->_db;
		$table = $this->loadTable();

		// Get post vars
		$oldName = Yii::app()->request->getPost('oldName');
		$newName = Yii::app()->request->getPost('newName');
		$type = Yii::app()->request->getPost('type');

		$criteria = new CDbCriteria;
		$criteria->condition = 'TABLE_SCHEMA = :schema AND TABLE_NAME = :table AND INDEX_NAME = :index';
		$criteria->params = array(
			':schema' => $table->TABLE_SCHEMA,
			':table' => $table->TABLE_NAME,
			':index' => $oldName,
		);
		$indices = Index::model()->findAll($criteria);

		$columns = array();
		$type = 'index';
		foreach($indices AS $index)
		{
			$columns[] = $index->COLUMN_NAME;
			if($index->INDEX_TYPE == 'FULLTEXT')
			{
				$type = 'fulltext';
			}
			elseif($index->NON_UNIQUE)
			{
				$type = 'unique';
			}
		}

		$response = new AjaxResponse();
		try
		{
			$sql = $table->dropIndex($oldName, $type);
			$sql .= "\n\n" . $table->createIndex($newName, $type, $columns);
			$response->addNotification('success',
				Yii::t('message', 'successRenameIndex', array('{index}' => $newName)),
				null,
				$sql);
		}
		catch(DbException $ex)
		{
			$response->addNotification('error',
				Yii::t('message', 'errorRenameIndex', array('{index}' => $oldName)),
				$ex->getText(),
				$ex->getSql());
			$response->reload = true;
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
	public function loadTable($id=null)
	{
		if($this->_table===null)
		{
			if($id!==null || ($this->table && $this->schema))
			{
				$criteria = new CDbCriteria;
				$criteria->condition = 'TABLE_SCHEMA = :schema AND TABLE_NAME = :table';
				$criteria->params = array(
					'schema'=>$this->schema,
					'table'=>$this->table,
				);

				$table = Table::model()->find($criteria);
				$table->columns = Column::model()->findAll($criteria);
				$table->indices = Index::model()->findAll($criteria);

				$this->_table = $table;
			}

			if($this->_table===null)
				throw new CHttpException(500,'The requested table does not exist.');
		}
		return $this->_table;
	}

	/*
	 * Private functions
	 */
	private function getDefaultQuery()
	{
		return 'SELECT * FROM ' . $this->_db->quoteTableName($this->table) . "\n\t"
				. 'WHERE 1';
	}
}