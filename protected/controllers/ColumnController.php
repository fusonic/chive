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
			if($column->save())
			{
				Yii::app()->end('reload');
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
		$column = Column::model()->findByPk(array('TABLE_SCHEMA' => $this->schema, 'TABLE_NAME' => $this->table, 'COLUMN_NAME' => $_GET['col']));
		if(isset($_POST['Column']))
		{
			$column->attributes = $_POST['Column'];
			if($column->save())
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

		$column->move($_POST['command']);
	}

	public function actionDrop()
	{
		Column::$db = $this->_db;

		$columns = (array)$_POST['column'];
		foreach($columns AS $column)
		{
			$pk = array(
				'TABLE_SCHEMA' => $this->schema,
				'TABLE_NAME' => $this->table,
				'COLUMN_NAME' => $column,
			);
			try
			{
				$column = Column::model()->findByPk($pk);
				$column->delete();
			}
			catch(Exception $ex) { }
		}
	}

}