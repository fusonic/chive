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

		Column::$db = Table::$db = Index::$db = $this->_db;

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
					Yii::t('message', 'successAddColumn', array('{col}' => $column->COLUMN_NAME)),
					null,
					$sql);
				$response->reload = true;

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
							Yii::t('message', 'successCreateIndex', array('{index}' => $index->INDEX_NAME)),
							null,
							$sql);
						$response->reload = true;
					}
					catch(DbException $ex)
					{
						$response->addNotification('error',
							Yii::t('message', 'errorCreateIndex', array('{index}' => $index->INDEX_NAME)),
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

		CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3);
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

		CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3);
		$data = array(
			'column' => $column,
			'table' => $table,
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