<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2010 Fusonic GmbH
 *
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */


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
		
		if(!($table instanceof Table))
		{
			$response = new AjaxResponse();
			$response->addNotification("error", 
				Yii::t("core", "tableLoadErrorTitle", array("{table}" => $this->table)),
				Yii::t("core", "tableLoadErrorMessage", array("{table}" => $this->table))); 
			$response->executeJavaScript("sideBar.loadTables(schema)");
			$this->sendJSON($response);
		}
		
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
		
		// Indices (seperate for each column)
		$indicesRaw = Index::model()->findAllByAttributes(array(
			'TABLE_SCHEMA' => $table->TABLE_SCHEMA,
			'TABLE_NAME' => $table->TABLE_NAME,
		));

		// Triggers
		$table->triggers = Trigger::model()->findAllByAttributes(array(
			'EVENT_OBJECT_SCHEMA' => $table->TABLE_SCHEMA,
			'EVENT_OBJECT_TABLE' => $table->TABLE_NAME,
		));

		$this->render('structure',array(
			'table' => $table,
			'canAlter' => Yii::app()->user->privileges->checkTable($table->TABLE_SCHEMA, $table->TABLE_NAME, 'ALTER'),
			'foreignKeys' => $foreignKeys,
			'indicesRaw' => $indicesRaw,
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
		$browsePage->route = 'schema/' . $this->schema . '/tables/' . $this->table . '/browse';
		$browsePage->formTarget = 'schema/' . $this->schema . '/tables/' . $this->table . '/browse';

		$browsePage->run();

		if($browsePage->table !== null)
		{
			$this->table = $browsePage->table;
		}
		
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

			$table->columns = array(
				$column,
			);

			if($sql = $table->insert())
			{
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('core', 'successAddTable', array('{table}' => $table->TABLE_NAME)),
					null,
					$sql);
				$response->redirectUrl = '#tables/' . $table->TABLE_NAME . '/structure';
				$response->executeJavaScript('sideBar.loadTables(schema);');

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
						Yii::t('core', 'successCreateIndex', array('{index}' => $index->INDEX_NAME)),
						null,
						$sql);
						$response->refresh = true;
					}
					catch(DbException $ex)
					{
						$response->addNotification('error',
							Yii::t('core', 'errorCreateIndex', array('{index}' => $index->INDEX_NAME)),
							$ex->getText(),
							$ex->getSql());
					}
				}

				$this->sendJSON($response);
			}
		}

		$collations = Collation::model()->findAll(array(
			'order' => 'COLLATION_NAME',
			'select' => 'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'
			));

		CHtml::generateRandomIdPrefix();
		
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

		$browsePage = new BrowsePage();

		$browsePage->schema = $this->schema;
		$browsePage->table = $this->table;
		$browsePage->db = $this->db;
		$browsePage->route = 'schema/' . $this->schema . '/tables/' . $this->table . '/sql';
		$browsePage->formTarget = 'schema/' . $this->schema . '/tables/' . $this->table . '/sql';
		$browsePage->execute = (bool)Yii::app()->getRequest()->getParam('query');

		$browsePage->run();

		if($browsePage->table !== null)
		{
			$this->table = $browsePage->table;
		}

		$this->render('../global/browse', array(
			'model' => $browsePage
		));

	}

	public function actionSearch() {

		$operatorConfig = array(				// needs value
			'LIKE'				=> 		array( 'needsValue' => true ),
			'NOT LIKE'			=> 		array( 'needsValue' => true ),
			'='					=> 		array( 'needsValue' => true ),
			'!=' 				=> 		array( 'needsValue' => true ),
			'REGEXP'			=> 		array( 'needsValue' => "?" ),
			'NOT REGEXP'		=> 		array( 'needsValue' => "?" ),
			'IS NULL'			=> 		array( 'needsValue' => false ),
			'IS NOT NULL' 		=> 		array( 'needsValue' => false ),
		);

		$operators = array_keys($operatorConfig);
		$config = array_values($operatorConfig);

		Row::$db = $this->db;
		Row::$schema = $this->schema;
		Row::$table = $this->table;

		$row = new Row();

		$commandBuilder = $this->db->getCommandBuilder();
		
		if(isset($_POST['Row']))
		{
			$criteria = new CDbCriteria();

			$i = 0;
			foreach($_POST['Row'] AS $column=>$value)
			{
				$operator = $operators[$_POST['operator'][$column]];

				if(strlen($value)>0)
				{
					$criteria->condition .= ($i>0 ? ' AND ' : ' ') . $this->db->quoteColumnName($column) . ' ' . $operator . ' ' . $this->db->quoteValue($value);

					$i++;
				}
				elseif(isset($_POST['operator'][$column]) && $config[$_POST['operator'][$column]]['needsValue'] === false)
				{
					$criteria->condition .= ($i>0 ? ' AND ' : ' ') . $this->db->quoteColumnName($column) . ' ' . $operator;

					$i++;
				}

			}
			
			$query = $this->db->getCommandBuilder()->createFindCommand($this->table, $criteria)->getText();
		}
		elseif(isset($_POST['query']))
		{
			$query = $_POST['query'];
		}
		
		if(isset($query))
		{
			$browsePage = new BrowsePage();

			$browsePage->schema = $this->schema;
			$browsePage->table = $this->table;
			$browsePage->db = $this->db;
			$browsePage->route = 'schema/' . $this->schema . '/tables/' . $this->table . '/browse';
			$browsePage->formTarget = 'schema/' . $this->schema . '/tables/' . $this->table . '/sql';
			$browsePage->execute = true;
			$browsePage->query = $query;

			$browsePage->run();

			$this->render('../global/browse', array(
				'model' => $browsePage
			));	
		}
		else
		{
			$this->render('../table/search', array(
				'row' => $row,
				'operators'=> $operators,
			));
		}

	}

	/**
	 * Truncates tables
	 */
	public function actionTruncate()
	{
		$response = new AjaxResponse();
		$response->refresh = true;
		$response->executeJavaScript('sideBar.loadTables(schema);');
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
					Yii::t('core', 'errorTruncateTable', array('{table}' => $this->table)),
					$ex->getText(),
					$ex->getSql());
			}
		}

		$count = count($truncatedTables);
		if($count > 0)
		{
			$response->addNotification('success',
			Yii::t('core', 'successTruncateTable', array($count, '{table}' => $truncatedTables[0], '{tableCount}' => $count)),
			($count > 1 ? implode(', ', $truncatedTables) : null),
			implode("\n", $truncatedSqls));
		}

		$this->sendJSON($response);
	}

	/**
	 * Drops tables
	 */
	public function actionDrop()
	{
		$response = new AjaxResponse();
		if(!Yii::app()->getRequest()->getParam('redirectOnSuccess'))
		{
			$response->refresh = true;
		}
		$response->executeJavaScript('sideBar.loadTables(schema);');

		$tables = (array)$_POST['tables'];
		$droppedTables = $droppedSqls = array();

		foreach($tables AS $table)
		{
			$tableObj = Table::model()->findByPk(array(
				'TABLE_SCHEMA' => $this->schema,
				'TABLE_NAME' => $table
			));
			$tableObj->throwExceptions = true;
			try
			{
				$sql = $tableObj->delete();
				$droppedTables[] = $table;
				$droppedSqls[] = $sql;
			}
			catch(DbException $ex)
			{
				$response->addNotification('error',
				Yii::t('core', 'errorDropTable', array('{table}' => $table)),
				$ex->getText(),
				$ex->getSql());
			}
		}

		$count = count($droppedTables);
		if($count > 0)
		{
			$response->addNotification('success',
				Yii::t('core', 'successDropTable', array($count, '{table}' => $droppedTables[0], '{tableCount}' => $count)),
				($count > 1 ? implode(', ', $droppedTables) : null),
				implode("\n", $droppedSqls));
				
			if(Yii::app()->getRequest()->getParam('redirectOnSuccess'))
			{
				$response->redirectUrl = '#tables';
			}
		}

		$this->sendJSON($response);
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

			CHtml::generateRandomIdPrefix();
			$this->render('form', array(
				'table' => $table,
				'collations' => $collations,
				'storageEngines' => StorageEngine::getSupportedEngines(),
				'isSubmitted' => $isSubmitted,
				'sql' => $sql,
			));
	}

	/**
	 * Shows the export page for this table.
	 */
	public function actionExport()
	{
		$exportPage = new ExportPage('objects', $this->schema);
		$exportPage->setSelectedObjects('t:' . $this->table);
		$exportPage->run();

		$this->render('../global/export', array(
			'model' => $exportPage,
		));
	}

	/**
	 * Shows the import page for this table.
	 */
	public function actionImport()
	{
		$importPage = new ImportPage();
		$importPage->db = $this->db;
		$importPage->table = $this->table;
		$importPage->schema = $this->schema;
		$importPage->formTarget = Yii::app()->urlManager->baseUrl . '/schema/' . $this->schema . '/tables/' . $this->table . '/import';

		$res = $importPage->run();

		if($res instanceof AjaxResponse)
		{
			$this->sendJSON($res);
		}
		else
		{
			$this->render('../global/import', array(
				'model' => $importPage,
			));
		}
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