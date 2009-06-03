<?php

class RowController extends Controller
{
	private $_db;

	public $schema;
	public $table;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = 'schema';

	public function __construct($id, $module=null) {

		if(Yii::app()->request->isAjaxRequest)
			$this->layout = false;

		$request = Yii::app()->getRequest();
		$this->schema = $request->getParam('schema');
		$this->table = $request->getParam('table');


		// @todo (rponudic) work with parameters!
		$this->_db = new CDbConnection('mysql:host='.Yii::app()->user->host.';dbname=' . $this->schema, Yii::app()->user->name, Yii::app()->user->password);
		$this->_db->charset='utf8';
		$this->_db->active = true;

		// Assign database connection to row model
		Row::$db = $this->_db;

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

	public function actionUpdate()
	{

		$db = $this->_db;

		$pk = CPropertyValue::ensureArray($db->getSchema()->getTable($this->table)->primaryKey);
		$column = Yii::app()->getRequest()->getParam('column');
		$newValue = Yii::app()->getRequest()->getParam('value');
		$null = Yii::app()->getRequest()->getParam('isNull');

		$attributes = json_decode($_POST['attributes'], true);
		$attributesCount = count($pk);

		if($null)
		{
			$newValue = 'NULL';
		}
		
		$response = new AjaxResponse();
		
		Row::$db = $db;
		$row = Row::model()->findByPk($attributes);

		$response->addData(null, array(
			'value' => htmlspecialchars($newValue),
			'column' => $column,
			'isPrimary' => in_array($column, $pk)
		));

		try
		{

			$commandBuilder = $this->_db->getCommandBuilder();

			$sql = 'UPDATE ' . $db->quoteTableName($this->table) . ' SET ' . "\n";
			$sql .= "\t" . $db->quoteColumnName($column) . ' = ' . ($null ? $newValue : $db->quoteValue($newValue)) . ' ' . "\n";
			$sql .= ' WHERE ' . "\n";

			$i = 0;
			foreach($attributes AS $name=>$value) {

				if(!in_array($name, $pk))
					continue;

				$sql .= "\t" . $db->quoteColumnName($name) . ' = ' . $db->quoteValue($value) . ' ';
				$i++;

				if($i < $attributesCount)
					$sql .= 'AND ' . "\n";

			}
			
			$sql .= "\n" . 'LIMIT 1';

			$cmd = $commandBuilder->createSqlCommand($sql);

			$cmd->prepare();
			$cmd->execute();
			
			// Get value out of DB
			if(in_array($column, array_keys($attributes)))
			{
				$attributes[$column] = $newValue;
			}
			
			$row = Row::model()->findByAttributes($attributes);
			
			if($row == null)
			{
				$response->refresh = true;
			}
			
			
			$response->addNotification('success', Yii::t('message', 'successUpdateRow'), null, $sql);

		}
		catch (CDbException $ex)
		{
			$ex = new DbException($cmd);
			$response->addNotification('error', Yii::t('message', 'errorUpdateRow'), $ex->getText(), $sql, array('isSticky'=>true));
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

			foreach($data AS $attributes) {

				$row = new Row;
				$row->attributes = $attributes;

				$pkAttributes = $row->getPrimaryKey();
				$row->attributes = null;

				$row->attributes = $pkAttributes;

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
		$column = $this->_db->getSchema()->getTable($this->table)->getColumn($column);
		
		$this->render('input', array(
			'column' => $column,
			'row' => $row,
			'attributes' => $kvAttributes,
			'oldValue' => str_replace("\n", "", $oldValue),				// @todo (rponudic) double-check if this is the solution!?
			'rowIndex' => $rowIndex,
		));

	}
	
	public function actionSave()
	{
		$attributes = json_decode($_POST['attributes'], true);
	}

}