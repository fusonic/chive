<?php

class RowController extends Controller
{
	public $schema;
	public $table;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = 'schema';

	/*
	 * @var Available database functions
	 */
	public static $functions = array(
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
	
	public function __construct($id, $module=null)
	{
		if(Yii::app()->request->isAjaxRequest)
			$this->layout = false;

		$request = Yii::app()->getRequest();
		$this->schema = $request->getParam('schema');
		$this->table = $request->getParam('table');

		parent::__construct($id, $module);
		$this->connectDb($this->schema);
	}

	/**
	 * Insert a new row
	 * 
	 */
	public function actionInsert()
	{

		$this->layout = '_table';
		$db = $this->db;

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
					if($attribute == 'multiple_set')
					{
						var_dump($value);
						die();
						
						
					}
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

		$data = array(
			'row' => $row,
			'functions' => self::$functions,
		);
		
		$data['formBody'] = $this->renderPartial('formBody', $data, true);
		
		$this->render('insert', $data);



	}
	
	public function actionUpdate()
	{

		$db = $this->db;

		$pk = CPropertyValue::ensureArray($db->getSchema()->getTable($this->table)->primaryKey);
		$column = Yii::app()->getRequest()->getParam('column');
		$newValue = json_decode(Yii::app()->getRequest()->getParam('value'), true);
		$null = Yii::app()->getRequest()->getParam('isNull');
		$attributes = json_decode(Yii::app()->getRequest()->getParam('attributes'), true);

		// SET datatype
		if(is_array($newValue))
		{
			$newValue = implode(',', $newValue);
		}

		$attributesCount = count($pk);

		if($null)
		{
			$newValue = null;
		}

		$response = new AjaxResponse();

		Row::$db = $db;

		if(count($attributes) == 1)
		{
			$findAttributes = $attributes[$column];
		}
		else
		{
			$findAttributes = $attributes;
		}

		$row = Row::model()->findByPk($findAttributes);

		try {

			$row->setAttribute($column, $newValue);
			$sql = $row->save();

			$response->addData(null, array(
				'value' => ($null ? 'NULL' : htmlspecialchars($row->getAttribute($column))),
				'column' => $column,
				'isPrimary' => in_array($column, $pk),
				'isNull' => $null,
				'visibleValue' => ($null ? '<span class="null">NULL</span>' : htmlspecialchars($row->getAttribute($column)))
			));

			// Refresh the page if the row could not be found in database anymore
			if(!$row->refresh() || $row->getAttribute($column) != $newValue) {
				$response->refresh = true;

				// @todo (rponudic) check if a notification is necessary in this case
				//$response->addNotification('warning', 'type does not match');
			}

			$response->addNotification('success', Yii::t('message', 'successUpdateRow'), null, $sql);

		}
		catch (DbException $ex)
		{
			$response->addNotification('error', Yii::t('message', 'errorUpdateRow'), $ex->getText(), $sql);
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

		// Single PK
		$kvAttributes = $attributes;

		if(count($attributes) == 1)
		{
			$attributes = array_pop($attributes);
		}

		$row = Row::model()->findByPk($attributes);
		$column = $this->db->getSchema()->getTable($this->table)->getColumn($column);

		$this->render('input', array(
			'column' => $column,
			'row' => $row,
			'attributes' => $kvAttributes,
			'oldValue' => str_replace("\n", "", $oldValue),				// @todo (rponudic) double-check if this is the solution!?
			'rowIndex' => $rowIndex,
		));

	}

	public function actionEdit() 
	{
		Row::$db = $this->db;
		
		$attributes = json_decode(Yii::app()->getRequest()->getParam('attributes'), true);
		$kvAttributes = $attributes;
		
		
		// Single PK
		if(count($attributes) == 1)
		{
			$attributes = array_pop($attributes);
		}
		
		$row = Row::model()->findByPk($attributes);

		if($newRow = Yii::app()->getRequest()->getParam('Row')) 
		{

			$response = new AjaxResponse();
			
			foreach($newRow AS $name=>$value)
			{
				
				if(isset($_FILES['Row']['name'][$name]))
				{
					$value = file_get_contents($_FILES['Row']['tmp_name'][$name]);
				}
				
				$options = Yii::app()->getRequest()->getParam($name);
				
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
				predie($ex);
				$response->addNOtification('error', Yii::t('message', 'updatingRowFailed'), $ex->getText());
			}
			
			$response->send();
			
		}
		
		CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3);
		
		$data = array(
			'row' => $row,
			'attributes' => $kvAttributes,
			'functions' => self::$functions,
		);
		
		$data['formBody'] = $this->renderPartial('formBody', $data, true);
		
		$this->render('edit', $data);
		
		
	}
	
	public function actionDownload()
	{

		$key = json_decode(Yii::app()->getRequest()->getParam('key'), true);
		$column = Yii::app()->getRequest()->getParam('column');
		
		if(count($key) == 1)
		{
			$key = array_pop($key);
		}
		
		header('Content-Disposition: attachment; filename="'.$column.'"');
		echo  Row::model()->findByPk($key)->getAttribute($column);
	}
	
	public function actionExport()
	{


	}

}