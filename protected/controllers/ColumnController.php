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


class ColumnController extends Controller
{
	public $schema;
	public $table;
	public $column;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = 'schema';

	public function __construct($id, $module=null)
	{
		$request = Yii::app()->getRequest();

		if($request->isAjaxRequest)
		{
			$this->layout = false;
		}

		$this->schema = $request->getParam('schema');
		$this->table = $request->getParam('table');
		$this->column = $request->getParam('column');

		parent::__construct($id, $module);
		$this->connectDb($this->schema);
	}

	public function actionCreate()
	{
		$column = new Column();
		$column->TABLE_NAME = $this->table;
		$table = Table::model()->findByPk(array('TABLE_SCHEMA' => $this->schema, 'TABLE_NAME' => $this->table));
		if(isset($_POST['Column']))
		{
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

			if($sql = $column->save())
			{
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('core', 'successAddColumn', array('{col}' => $column->COLUMN_NAME)),
					null,
					$sql);
				$response->refresh = true;

				foreach($addIndices AS $type => $indexName)
				{
					try
					{
						$index = new Index();
						$index->throwExceptions = true;
						$index->TABLE_NAME = $this->table;
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
			'select'=>'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'
		));

		CHtml::generateRandomIdPrefix();
		$data = array(
			'column' => $column,
			'table' => $table,
			'collations' => $collations,
		);
		$data['formBody'] = $this->renderPartial('formBody', $data, true);
		$this->render('form', $data);
	}

	public function actionUpdate()
	{
		$isSubmitted = false;
		$sql = false;
		$column = Column::model()->findByPk(array(
			'TABLE_SCHEMA' => $this->schema,
			'TABLE_NAME' => $this->table,
			'COLUMN_NAME' => $this->column,
		));
		if(isset($_POST['Column']))
		{
			$column->attributes = $_POST['Column'];
			$sql = $column->save();
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
		$data = array(
			'column' => $column,
			'collations' => $collations,
			'isSubmitted' => $isSubmitted,
			'sql' => $sql,
		);
		$data['formBody'] = $this->renderPartial('formBody', $data, true);
		$this->render('form', $data);
	}

	public function actionMove()
	{
		$pk = array(
			'TABLE_SCHEMA' => $this->schema,
			'TABLE_NAME' => $this->table,
			'COLUMN_NAME' => $this->column,
		);
		$column = Column::model()->findByPk($pk);

		$response = new AjaxResponse();
		try
		{
			$command = $column->move($_POST['command']);
			$response->addNotification('success',
				Yii::t('core', 'successMoveColumn', array('{col}' => $column->COLUMN_NAME)),
				null,
				$command);
		}
		catch(DbException $ex)
		{
			$response->addNotification('error',
				Yii::t('core', 'errorMoveColumn', array('{col}' => $column->COLUMN_NAME)),
				$ex->getText(),
				$ex->getSql());
			$response->refresh = true;
		}
		$this->sendJSON($response);
	}

	public function actionDrop()
	{
		$columns = (array)$_POST['column'];
		$response = new AjaxResponse();
		$droppedColumns = $droppedSqls = array();

		foreach($columns AS $column)
		{
			$pk = array(
				'TABLE_SCHEMA' => $this->schema,
				'TABLE_NAME' => $this->table,
				'COLUMN_NAME' => $column,
			);
			$column = Column::model()->findByPk($pk);
			$column->throwExceptions = true;
			try
			{
				$sql = $column->delete();
				$droppedColumns[] = $column->COLUMN_NAME;
				$droppedSqls[] = $sql;
			}
			catch(DbException $ex)
			{
				$response->addNotification('error',
					Yii::t('core', 'errorDropColumn', array('{col}' => $column->COLUMN_NAME)),
					null,
					$ex->getText());
				$response->refresh = true;
			}
		}

		$count = count($droppedColumns);
		if($count > 0)
		{
			$response->addNotification('success',
				Yii::t('core', 'successDropColumn', array($count, '{col}' => $droppedColumns[0], '{colCount}' => $count)),
				($count > 1 ? implode(', ', $droppedColumns) : null),
				implode("\n", $droppedSqls));
		}

		$this->sendJSON($response);
	}

}