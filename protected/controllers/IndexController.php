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


class IndexController extends Controller
{

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	private $_db;

	public $table;
	public $schema;
	public $index;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = 'schema';

	public function __construct($id, $module=null)
	{
		$request = Yii::app()->getRequest();

		if($request->isAjaxRequest)
			$this->layout = false;

		$this->table = $request->getParam('table');
		$this->schema = $request->getParam('schema');
		$this->index = $request->getParam('index');

		parent::__construct($id, $module);
		$this->connectDb($this->schema);
	}

	public function actionCreate()
	{
		$index = new Index();
		$index->TABLE_NAME = $this->table;
		$table = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => $this->schema,
			'TABLE_NAME' => $this->table,
		));

		if(isset($_POST['Index']))
		{
			$index->attributes = $_POST['Index'];
			$index->columns = $this->getColumnsFromRequest();

			if($sql = $index->save())
			{
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('core', 'successCreateIndex', array('{index}' => $index->INDEX_NAME)),
					null,
					$sql);
				$response->refresh = true;
				$this->sendJSON($response);
			}
		}

		$indexTypes = $table->getSupportedIndexTypes();
		if($table->getHasPrimaryKey())
		{
			unset($indexTypes['PRIMARY']);
		}

		$this->render('form', array(
			'index' => $index,
			'indexTypes' => $indexTypes,
			'addColumnData' => $this->getColumnSelect($table),
		));
	}

	public function actionCreateSimple()
	{
		// Get post vars
		$indexName = Yii::app()->request->getPost('index');
		$type = Yii::app()->request->getPost('type');
		$columns = (array)Yii::app()->request->getPost('columns');

		$response = new AjaxResponse();
		try
		{
			$index = new Index();
			$index->throwExceptions = true;
			$index->TABLE_SCHEMA = $this->schema;
			$index->TABLE_NAME = $this->table;
			$index->INDEX_NAME = $indexName;
			$index->setType($type);
			$index->columns = $this->getColumnsFromRequest();
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
				Yii::t('core', 'errorCreateIndex', array('{index}' => $indexName)),
				$ex->getText(),
				$ex->getSql());
		}
		$this->sendJSON($response);
	}

	public function actionUpdate()
	{
		$index = Index::model()->findByAttributes(array(
			'TABLE_SCHEMA' => $this->schema,
			'TABLE_NAME' => $this->table,
			'INDEX_NAME' => $this->index,
		));
		
		if($index == null)
		{
			$index = new Index();
			$index->TABLE_SCHEMA = $this->schema;
			$index->TABLE_NAME = $this->table;
			$index->INDEX_NAME = $this->index;
		}
		
		$table = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => $this->schema,
			'TABLE_NAME' => $this->table,
		));

		if(isset($_POST['Index']))
		{
			$index->attributes = $_POST['Index'];
			$index->columns = $this->getColumnsFromRequest();

			if($sql = $index->save())
			{
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('core', 'successAlterIndex', array('{index}' => $index->INDEX_NAME)),
					null,
					$sql);
				$response->refresh = true;
				$this->sendJSON($response);
			}
		}

		$indexTypes = $table->getSupportedIndexTypes();

		$this->render('form', array(
			'index' => $index,
			'indexTypes' => $indexTypes,
			'addColumnData' => $this->getColumnSelect($table),
		));
	}

	public function actionDrop()
	{
		// Get post vars
		$indexName = Yii::app()->request->getPost('index');

		$response = new AjaxResponse();
		try
		{
			$index = Index::model()->findByAttributes(array(
				'TABLE_SCHEMA' => $this->schema,
				'TABLE_NAME' => $this->table,
				'INDEX_NAME' => $indexName,
			));
			$index->throwExceptions = true;
			$sql = $index->delete();
			$response->addNotification('success',
				Yii::t('core', 'successDropIndex', array('{index}' => $index->INDEX_NAME)),
				null,
				$sql);
			$response->addData('success', true);
		}
		catch(DbException $ex)
		{
			$response->addNotification('error',
				Yii::t('core', 'errorDropIndex', array('{index}' => $indexName)),
				$ex->getText(),
				$ex->getSql());
			$response->addData('success', false);
		}
		$this->sendJSON($response);
	}

	private function getColumnSelect(Table $table)
	{
		$data = array(
			'' => Yii::t('core', 'selectColumnToAdd') . ':',
		);
		foreach($table->columns AS $column)
		{
			$data[$column->COLUMN_NAME] = $column->COLUMN_NAME;
		}
		return $data;
	}

	private function getColumnsFromRequest()
	{
		$columns = array();
		foreach((array)Yii::app()->request->getPost('columns') AS $column)
		{
			$col = new IndexColumn();
			$col->COLUMN_NAME = $column;
			if((int)@$_POST['keyLengths'][$column] > 0)
			{
				$col->SUB_PART = (int)$_POST['keyLengths'][$column];
			}
			$columns[] = $col;
		}
		return $columns;
	}

}