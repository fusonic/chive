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


class RowController extends Controller
{
	public $schema;
	public $table;
	public $view;
	
	/**
	 * @var Table
	 */
	public $_table;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = 'schema';

	public function __construct($id, $module=null)
	{
		if(Yii::app()->request->isAjaxRequest)
			$this->layout = false;
			
		$request = Yii::app()->getRequest();
		
		$this->schema =	$request->getParam('schema');
		$this->table = $request->getParam('table');
		
		$this->_table = Table::model()->findByPk(array(
			'TABLE_SCHEMA' => $this->schema,
			'TABLE_NAME' => $this->table,
		));
		
		if($this->_table->TABLE_TYPE == 'VIEW')
		{
			$this->view = $this->table;
		}
		
		parent::__construct($id, $module);
		$this->connectDb($this->schema);
		
		// Set row values
		Row::$db = $this->db;
		Row::$schema = $this->schema;
		Row::$table = $this->table;
		
	}

	/**
	 * Insert a new row
	 * 
	 */
	public function actionInsert()
	{
		$response = new AjaxResponse();
		
		if($this->view)
		{
			$this->layout = '_view';
		}
		else
		{
			$this->layout = '_table';
		}

		$row = new Row();
		
		if(isset($_POST['Row']))
		{
			
			foreach($_POST['Row'] AS $attribute=>$value) 
			{
				// NULL value
				if(isset($_POST[$attribute]['null']))
				{
					$row->setAttribute($attribute, null);
				}
	
				// RAW
				// @todo (rponudic) implement multiple sets
				elseif('type' == 'multiple_set')
				{
					
				}
				
				// FILE
				elseif(isset($_FILES['Row']['tmp_name'][$attribute]) && is_file($_FILES['Row']['tmp_name'][$attribute]))
				{
					
					$file = '0x' . bin2hex(file_get_contents($_FILES['Row']['tmp_name'][$attribute]));
					$row->setHex($attribute);
					$row->setAttribute($attribute, $file);
					
				}
				
				// DEFAULT
				else
				{
					$row->setAttribute($attribute, $value);
				}
				
				// FUNCTIONS
				if(isset($_POST[$attribute]['function']) && $_POST[$attribute]['function'])
				{
					$row->setFunction($attribute, $_POST[$attribute]['function']);
				}
				
			}
			
			try 
			{
				$sql = $row->insert();
				
				// Try to find last inserted id if this table has an auto increment value
				if($this->_table->getHasAutoIncrementColumn())
				{
					$notification = Yii::t('core', 'successInsertRowWithIdX', array(
						"{id}" => $this->db->getLastInsertID(),
					));	
				}
				else
				{
					$notification = Yii::t('core', 'successInsertRow');
				}
				
				$response->addNotification('success', $notification, null, $sql);
				
				if($_POST['insertAndReturn'])
				{
					$response->refresh = true;
				}
				else
				{
					$response->redirectUrl = '#tables/' . $this->table . '/browse';
				}
				
			}
			catch (DbException $ex) 
			{
				$response->refresh = false;
				$response->addNotification('error', Yii::t('core', 'errorInsertRow'), $ex->getText(), $ex->getSql());
			}
			
			$this->sendJSON($response);
		
		}
		elseif(isset($_POST['attributes']))
		{
			$attributes = CJSON::decode($_POST['attributes'], true);
			$fromRow = Row::model()->findByAttributes($attributes);
			
			foreach($this->_table->columns as $column)
			{
				if($column->EXTRA != "auto_increment")
				{
					$row->{$column->COLUMN_NAME} = $fromRow->{$column->COLUMN_NAME};
				}
			}
		}
		
		$data = array(
			'row' => $row,
			'functions' => Row::$functions,
		);
		
		$data['formBody'] = $this->renderPartial('formBody', $data, true);
		
		$this->render('insert', $data);

	}
	
	public function actionUpdate()
	{

		$response = new AjaxResponse();

		// Take other solution::
		$newValue = Yii::app()->getRequest()->getParam('value');
		$column = Yii::app()->getRequest()->getParam('column');
		$isNull = Yii::app()->getRequest()->getParam('isNull');
		$attributes = CJSON::decode(Yii::app()->getRequest()->getParam('attributes'), true);
		
		$rows = Row::model()->findAllByAttributes($attributes);
		$row = $rows[0];
		
		// FILE (blob)
		if(isset($_FILES['value']))
		{
			$row->setHex($column);
			$newValue = '0x' . bin2hex(file_get_contents($_FILES['value']['tmp_name']));
			// Refresh if type s file 
			$response->refresh = true;
		}

		if($isNull)
		{
			$newValue = null;
		}
		
		try {

			$row->setAttribute($column, $newValue);
			$sql = $row->save();
			
			$showFullColumnContent = Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $this->schema . '.' . $this->table);
			
			$visibleValue = CHtml::encode($row->getAttribute($column));
			
			if($isNull)
			{
				$visibleValue = '<span class="null">NULL</span>';
			}
			elseif(!$showFullColumnContent)
			{
				$visibleValue = StringUtil::cutText($visibleValue, 100);
			}

			$response->addData(null, array(
				'value' => ($isNull ? 'NULL' : $row->getAttribute($column)),
				'column' => $column,
				'identifier' => $row->getIdentifier(),
				'isNull' => $isNull,
				'visibleValue' => $visibleValue
			));
			
			$cmd = new CDbCommand($this->db, 'SHOW WARNINGS');
			$warnings = $cmd->queryAll(true);
			
			if(count($warnings) > 0)
			{
				$response->refresh = true;
				foreach($warnings AS $warning)
				{
					$response->addNotification('warning', $warning['Message'], null);
				}
			}
			
			$response->addNotification('success', Yii::t('core', 'successUpdateRow'), null, $sql);


		}
		catch (DbException $ex)
		{
			$response->addNotification('error', Yii::t('core', 'errorUpdateRow'), $ex->getText(), $ex->getSql());
			$response->addData(null, array('error'=>true));
		}

		$this->sendJSON($response);

	}

	public function actionDelete()
	{

		$response = new AjaxResponse();

		$data = CJSON::decode($_POST['data'], true);
		
		$sql = "";
		
		try
		{
			foreach($data AS $attributes)
			{
				$row = Row::model()->findByAttributes($attributes);
				$sql .= $row->delete() . "\n\n";
				
			}
		}
		catch (DbException $ex)
		{
			$response->addNotification('error', Yii::t('core', 'errorDeleteRow'), $ex->getText(), $sql, array('isSticky'=>true));
		}

		$response->refresh = true;
		$response->addNotification('success', Yii::t('core', 'successDeleteRows', array(count($data), '{rowCount}' => count($data))), null, $sql);

		$this->sendJSON($response);
	}

	public function actionInput()
	{
		
		$attributes = CJSON::decode(Yii::app()->getRequest()->getParam('attributes'), true);
		$column = Yii::app()->getRequest()->getParam('column');
		$oldValue = Yii::app()->getRequest()->getParam('oldValue');
		$rowIndex = Yii::app()->getRequest()->getParam('rowIndex');

		$row = Row::model()->findByAttributes($attributes);
		$column = $this->db->getSchema($this->schema)->getTable($this->table)->getColumn($column);

		$this->render('input', array(
			'column' => $column,
			'row' => $row,
			'attributes' => $attributes,
			'oldValue' => str_replace("\n", "", $oldValue),
			'rowIndex' => $rowIndex,
		));

	}

	public function actionEdit() 
	{
		$attributes = CJSON::decode(Yii::app()->getRequest()->getParam('attributes'), true);
		
		$row = Row::model()->findByAttributes($attributes);

		if($newRow = Yii::app()->getRequest()->getParam('Row')) 
		{
			$response = new AjaxResponse();
			
			foreach($newRow AS $name=>$value)
			{

				// SET
				if(is_array($value))
				{
					$value = implode("," , $value);
				} 
				
				// FILE
				elseif(isset($_FILES['Row']['name'][$name]) && strlen($_FILES['Row']['name'][$name]) > 0)
				{
					$value = file_get_contents($_FILES['Row']['tmp_name'][$name]);
				}
				else if (strlen($value) == 0)
				{
					continue;
				}
				
				$options = Yii::app()->getRequest()->getParam($name);
				
				if($options['function'])
				{
					$row->setFunction($name, $options['function']);
				}
		
				if(isset($options['null']))
				{
					$value = null;
				}
				
				$row->setAttribute($name, $value);
				
			}
			
			
			try {
				
				$sql = $row->save();
				$response->refresh = true;
				$response->addNotification('success', Yii::t('core', 'successUpdateRow'), null, $sql);
				
			}
			catch(DbException $ex) 
			{
				$response->refresh = true;
				$response->addNotification('error', Yii::t('core', 'updatingRowFailed'), $ex->getText(), $ex->getSql());
			}
			
			$this->sendJSON($response);
			
		}
		
		CHtml::generateRandomIdPrefix();
		
		$data = array(
			'row' => $row,
			'functions' => Row::$functions,
			'attributes' => $attributes,
		);
		
		$data['formBody'] = $this->renderPartial('formBody', $data, true);
		
		$this->render('edit', $data);
		
		
	}
	
	public function actionDownload()
	{
		$key = CJSON::decode(Yii::app()->getRequest()->getParam('key'), true);
		$column = Yii::app()->getRequest()->getParam('column');

		$content = Row::model()->findByAttributes($key)->getAttribute($column);
		
		$filename = $column;
		
		// Try finding out mime type of string (fileinfo extensions is installed)
		if(class_exists('finfo', false))
		{
			$finfo = new finfo(FILEINFO_MIME);
			$mimeType = $finfo->buffer($content);
			
			header('Content-Type: ' . $mimeType);
			
			// Try finding correct extension
			preg_match('/\/([a-zA-Z_\-0-9]+)/i', $mimeType, $extension);
			
			if(isset($extension[1]))
			{
				$ext = $extension[1];
				if($ext == "x-gzip")
				{
					$ext = "tar.gz";
				}
				elseif($ext == "x-tar")
				{
					$ext = "tar";
				}
				
				$filename .= "." . $ext;
			}
			
		}
		
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		echo $content;
		
	}
	
	/**
	 * Shows the export page for this table.
	 */
	public function actionExport()
	{
		if(isset($_POST['data']))
		{
			$rows = CJSON::decode($_POST['data'], true);
		}
		else
		{
			$rows = CJSON::decode($_POST['Export']['rows'], true);
		}
		
		$exportPage = new ExportPage('rows', $this->schema, $this->table);
		$exportPage->setRows($rows);
		$exportPage->run();
		
		$this->render('../global/export', array(
			'model' => $exportPage,
		));
	}
	
	/**
	 * Dirty hack to make view tab navigation work ...
	 * 
	 * @return	Table
	 */
	public function loadView()
	{
		return $this->_table;
	}

}