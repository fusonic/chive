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


class ForeignKeyController extends Controller
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

	public function actionUpdate()
	{
		$isSubmitted = false;
		$sql = false;
		$foreignKey = ForeignKey::model()->findBySql('SELECT * FROM KEY_COLUMN_USAGE '
			. 'WHERE TABLE_SCHEMA = :tableSchema '
			. 'AND TABLE_NAME = :tableName '
			. 'AND COLUMN_NAME = :columnName '
			. 'AND REFERENCED_TABLE_SCHEMA IS NOT NULL', array(
			'tableSchema' => $this->schema,
			'tableName' => $this->table,
			'columnName' => $this->column,
		));
		if(!$foreignKey)
		{
			$foreignKey = new ForeignKey();
			$foreignKey->TABLE_SCHEMA = $this->schema;
			$foreignKey->TABLE_NAME = $this->table;
			$foreignKey->COLUMN_NAME = $this->column;
		}
		if(isset($_POST['ForeignKey']))
		{
			$foreignKey->attributes = $_POST['ForeignKey'];
			if($foreignKey->getReferences() && $sql = $foreignKey->save())
			{
				$isSubmitted = true;
			}
			elseif(!$foreignKey->getReferences() && $sql = $foreignKey->delete())
			{
				$isSubmitted = true;
			}
		}

		CHtml::generateRandomIdPrefix();

		// Column data
		$columns = array('' => '');
		$tables = Table::model()->findAllByAttributes(array(
			'TABLE_SCHEMA' => $this->schema,
		));
		foreach($tables AS $table)
		{
			if(StorageEngine::check($table->ENGINE, StorageEngine::SUPPORTS_FOREIGN_KEYS))
			{
				$columns[$table->TABLE_NAME] = array();
				foreach($table->columns AS $column) {
					$columns[$table->TABLE_NAME][$this->schema . '.' . $table->TABLE_NAME . '.' . $column->COLUMN_NAME] = $column->COLUMN_NAME;
				}
			}
		}

		// "On-Actions"
		$onActions = array(
			'' => '',
			'CASCADE' => 'CASCADE',
			'SET NULL' => 'SET NULL',
			'NO ACTION' => 'NO ACTION',
			'RESTRICT' => 'RESTRICT',
		);

		$this->render('form', array(
			'foreignKey' => $foreignKey,
			'columns' => $columns,
			'onActions' => $onActions,
			'sql' => $sql,
			'isSubmitted' => $isSubmitted,
		));
	}

}