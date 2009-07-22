<?php

class RowController extends Controller
{
	public $schema;
	public $table;

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
		
		$this->layout = '_table';

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
				elseif(isset($_FILES['Row']['name'][$attribute]))
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
				$response->addNotification('success', Yii::t('message', 'successInsertRow'), null, $sql);
				
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
				$response->refresh = true;
				$response->addNotification('error', Yii::t('message', 'errorInsertRow'), $ex->getText(), $ex->getSql());
			}
			
			$response->send();
		
		}
		elseif(isset($_POST['attributes']))
		{
			$attributes = json_decode($_POST['attributes'], true);
			$fromRow = Row::model()->findByAttributes($attributes);
			$row->attributes = $fromRow->attributes;
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
		$attributes = json_decode(Yii::app()->getRequest()->getParam('attributes'), true);
		
		$attributesCount = count($pk);
		$rows = Row::model()->findAllByAttributes($attributes);
		$row = $rows[0];
		
		// SET datatype
		if(is_array($newValue))
		{
			$newValue = implode(',', $newValue);
		}
		
		// FILE (blob)
		elseif(isset($_FILES['value']))
		{
			$row->setHex($column);
			$newValue = '0x' . bin2hex(file_get_contents($_FILES['value']['tmp_name']));
		}

		// NULL
		if($isNull)
		{
			$newValue = null;
		}
		
		
		
		try {

			$row->setAttribute($column, $newValue);
			$sql = $row->save();
			
			$response->addData(null, array(
				'value' => ($isNull ? 'NULL' : $row->getAttribute($column)),
				'column' => $column,
				'identifier' => $row->getIdentifier(),
				'isNull' => $isNull,
				'visibleValue' => ($isNull ? '<span class="null">NULL</span>' : htmlspecialchars($row->getAttribute($column)))
			));

			// @todo (rponudic) check which method should be used here
			// Refresh the page if the row could not be found in database anymore
			/* 
			$rows = Row::model()->findAllByAttributes($attributes);
			$row = $rows[0];
			
			if($rows === null || count($rows) > 1 || $row === null || $row->getAttribute($column) != $newValue) {
				$response->refresh = true;

				
				$response->addNotification('warning', 'type does not match');
			}
			
			*/
			
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
			
			$response->addNotification('success', Yii::t('message', 'successUpdateRow'), null, $sql);


		}
		catch (DbException $ex)
		{
			$response->addNotification('error', Yii::t('message', 'errorUpdateRow'), $ex->getText(), $ex->getSql());
			$response->addData(null, array('error'=>true));
		}

		$response->send();

	}

	public function actionDelete()
	{

		$response = new AjaxResponse();

		$data = json_decode($_POST['data'], true);
		
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
			$response->addNotification('error', Yii::t('message', 'errorDeleteRow'), $ex->getText(), $sql, array('isSticky'=>true));
		}

		$response->refresh = true;
		$response->addNotification('success', Yii::t('message', 'successDeleteRows', array(count($data), '{rowCount}' => count($data))), null, $sql);

		$response->send();
	}

	public function actionInput()
	{
		
		$attributes = json_decode(Yii::app()->getRequest()->getParam('attributes'), true);
		$column = Yii::app()->getRequest()->getParam('column');
		$oldValue = Yii::app()->getRequest()->getParam('oldValue');
		$rowIndex = Yii::app()->getRequest()->getParam('rowIndex');

		$row = Row::model()->findByAttributes($attributes);
		$column = $this->db->getSchema($this->schema)->getTable($this->table)->getColumn($column);

		$this->render('input', array(
			'column' => $column,
			'row' => $row,
			'attributes' => $attributes,
			'oldValue' => str_replace("\n", "", $oldValue),				// @todo (rponudic) double-check if this is the solution!?
			'rowIndex' => $rowIndex,
		));

	}

	public function actionEdit() 
	{
		
		$attributes = json_decode(Yii::app()->getRequest()->getParam('attributes'), true);
		
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
				elseif(isset($_FILES['Row']['name'][$name]))
				{
					$value = file_get_contents($_FILES['Row']['tmp_name'][$name]);
				}
				
				$options = Yii::app()->getRequest()->getParam($name);
				
				if($options['function'])
				{
					$row->setFunction($name, $options['function']);
				}
				
				if($options['null'])
				{
					$value = null;
				}
				
				$row->setAttribute($name, $value);
				
			}
			
			
			try {
				
				$sql = $row->save();
				$response->refresh = true;
				$response->addNotification('success', Yii::t('message', 'successUpdateRow'), null, $sql);
				
			}
			catch(DbException $ex) 
			{
				$response->addNotification('error', Yii::t('message', 'updatingRowFailed'), $ex->getText(), $ex->getSql());
			}
			
			$response->send();
			
		}
		
		CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3);
		
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

		$key = json_decode(Yii::app()->getRequest()->getParam('key'), true);
		$column = Yii::app()->getRequest()->getParam('column');
		
		header('Content-Disposition: attachment; filename="'.$column.'"');
		echo  Row::model()->findByAttributes($key)->getAttribute($column);
	}
	
	/**
	 * Shows the export page for this table.
	 */
	public function actionExport()
	{
		
		$rows = json_decode($_POST['data'], true);
		
		$exportPage = new ExportPage('rows', $this->schema, $this->table);
		$exportPage->setRows($rows);
		//$exportPage->setSelectedObjects(array('t:' . $this->table));
		$exportPage->run();
		
		$this->render('../global/export', array(
			'model' => $exportPage,
		));
	}

}