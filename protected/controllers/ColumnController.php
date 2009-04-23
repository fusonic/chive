<?php

class ColumnController extends Controller
{
	private $_db;

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
			$this->layout = false;

		$this->schema = $request->getParam('schema');
		$this->table = $request->getParam('table');
		$this->column = $request->getParam('column');

		// @todo (rponudic) work with parameters!
		$this->_db = new CDbConnection('mysql:host='.Yii::app()->user->host.';dbname=' . $this->schema, Yii::app()->user->name, Yii::app()->user->password);
		$this->_db->charset='utf8';
		$this->_db->active = true;

		Column::$db = Table::$db = $this->_db;

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
		$column = new Column();
		$column->TABLE_NAME = $this->table;
		$table = Table::model()->findByPk(array('TABLE_SCHEMA' => $this->schema, 'TABLE_NAME' => $this->table));
		if(isset($_POST['Column']))
		{
			$column->attributes = $_POST['Column'];
			if($sql = $column->save())
			{
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('message', 'successAddColumn', array('{col}' => $column->COLUMN_NAME)),
					null,
					$sql);
				$response->reload = true;

				/*
				 * Add index
				 */
				$addIndices = array();
				if(isset($_POST['createIndexPrimary']))
				{
					$addIndices['PRIMARY'] = 'PRIMARY';
				}
				if(isset($_POST['createIndex']))
				{
					$addIndices['INDEX'] = $column->COLUMN_NAME;
				}
				if(isset($_POST['createIndexUnique']))
				{
					$addIndices['UNIQUE'] = $column->COLUMN_NAME . (array_search($column->COLUMN_NAME, $addIndices) !== false ? '_unique' : '');
				}
				if(isset($_POST['createIndexFulltext']))
				{
					$addIndices['FULLTEXT'] = $column->COLUMN_NAME . (array_search($column->COLUMN_NAME, $addIndices) !== false ? '_fulltext' : '');
				}
				foreach($addIndices AS $type => $index)
				{
					try
					{
						$sql = $table->createIndex($index, $type, array($column->COLUMN_NAME));
						$response->addNotification('success',
							Yii::t('message', 'successCreateIndex', array('{index}' => $index)),
							null,
							$sql);
						$response->reload = true;
					}
					catch(DbException $ex)
					{
						$response->addNotification('error',
							Yii::t('message', 'errorCreateIndex', array('{index}' => $index)),
							$ex->getText(),
							$ex->getSql());
					}
				}

				$response->send();
			}
		}

		$collations = Collation::model()->findAll(array(
			'order' => 'COLLATION_NAME',
			'select'=>'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'
		));

		$this->render('form', array(
			'column' => $column,
			'table' => $table,
			'collations' => $collations,
		));
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
				Yii::t('message', 'successMoveColumn', array('{col}' => $column->COLUMN_NAME)),
				null,
				$command);
		}
		catch(DbException $ex)
		{
			$response->addNotification('error',
				Yii::t('message', 'errorMoveColumn', array('{col}' => $column->COLUMN_NAME)),
				$ex->getText(),
				$ex->getSql());
			$response->reload = true;
		}
		$response->send();
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
			try
			{
				$sql = $column->delete();
				$droppedColumns[] = $column->COLUMN_NAME;
				$droppedSqls[] = $sql;
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

		$count = count($droppedColumns);
		if($count > 0)
		{
			$response->addNotification('success',
				Yii::t('message', 'successDropColumn', array($count, '{col}' => $droppedColumns[0], '{colCount}' => $count)),
				($count > 1 ? implode(', ', $droppedColumns) : null),
				implode("\n", $droppedSqls));
		}

		$response->send();
	}

}