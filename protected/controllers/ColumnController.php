<?php

class ColumnController extends CController
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

	public function actionCreate()
	{
		Column::$db = $this->_db;

		$column = new Column();
		if(isset($_POST['Column']))
		{
			$column->attributes = $_POST['Column'];
			$column->TABLE_NAME = $this->table;
			if($sql = $column->save())
			{
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('message', 'successAddColumn', array('{col}' => $column->COLUMN_NAME)),
					null,
					$sql);
				$response->reload = true;
				$response->send();
			}
		}

		$collations = Collation::model()->findAll(array(
			'order' => 'COLLATION_NAME',
			'select'=>'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'
		));

		$this->render('form', array(
			'column' => $column,
			'collations' => $collations,
		));
	}

	public function actionUpdate()
	{
		Column::$db = $this->_db;

		$isSubmitted = false;
		$sql = false;
		$column = Column::model()->findByPk(array('TABLE_SCHEMA' => $this->schema, 'TABLE_NAME' => $this->table, 'COLUMN_NAME' => $_GET['col']));
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

		$this->render('form', array(
			'column' => $column,
			'collations' => $collations,
			'helperId' => 'helper_' . mt_rand(1000, 9999),
			'isSubmitted' => $isSubmitted,
			'sql' => $sql,
		));
	}

	public function actionMove()
	{
		Column::$db = $this->_db;

		$pk = array(
			'TABLE_SCHEMA' => $this->schema,
			'TABLE_NAME' => $this->table,
			'COLUMN_NAME' => $_POST['column'],
		);
		$column = Column::model()->findByPk($pk);

		$response = new AjaxResponse();
		try
		{
			$command = $column->move($_POST['command']);
			$response->addNotification('success',
				Yii::t('message', 'successMoveColumn', array('{col}' => $_POST['column'])),
				null,
				$command);
		}
		catch(DbException $ex)
		{
			$response->addNotification('error',
				Yii::t('message', 'errorMoveColumn', array('{col}' => $_POST['column'])),
				$ex->getText(),
				$ex->getSql());
			$response->reload = true;
		}
		$response->send();
	}

	public function actionDrop()
	{
		Column::$db = $this->_db;

		$columns = (array)$_POST['column'];
		$response = new AjaxResponse();
		foreach($columns AS $column)
		{
			$pk = array(
				'TABLE_SCHEMA' => $this->schema,
				'TABLE_NAME' => $this->table,
				'COLUMN_NAME' => $column,
			);
			$column = Column::model()->findByPk($pk);
			try
			{
				$sql = $column->delete();
				$response->addNotification('success',
					Yii::t('message', 'successDropColumn', array('{col}' => $column->COLUMN_NAME)),
					null,
					$sql);
			}
			catch(DbException $ex)
			{
				$response->addNotification('error',
					Yii::t('message', 'errorDropColumn', array('{col}' => $column->COLUMN_NAME)),
					null,
					$ex->getText());
				$response->reload = true;
			}
		}
		$response->send();
	}

}