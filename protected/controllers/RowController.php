<?php

class RowController extends CController
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

		$attributes = json_decode($_POST['data'], true);
		$attributesCount = count($attributes);

		$response = new AjaxResponse();

		$response->addData(null, array(
			'value' => $_POST['value'],
			'attribute' => $_POST['attribute'],
		));

		try
		{

			$commandBuilder = $this->_db->getCommandBuilder();

			$sql = 'UPDATE ' . $db->quoteTableName($this->table) . ' SET ' . "\n";
			$sql .= "\t" . $db->quoteColumnName($_POST['attribute']) . ' = ' . $db->quoteValue($_POST['value']) . ' ' . "\n";
			$sql .= ' WHERE ' . "\n";

			$i = 0;
			foreach($attributes AS $name=>$value) {

				$sql .= "\t" . $db->quoteColumnName($name) . ' = ' . $db->quoteValue($value) . ' ';
				$i++;

				if($i < $attributesCount)
					$sql .= 'AND ' . "\n";

			}

			$cmd = $commandBuilder->createSqlCommand($sql);

			$cmd->prepare();
			$cmd->execute();

			$response->addNotification('success', Yii::t('message', 'successUpdateRow'), null, $sql);

		}
		catch (CDbException $ex)
		{
			$ex = new DbException($cmd);
			$response->addNotification('error', Yii::t('message', 'errorUpdateRow'), $ex->getText(), $sql, array('isSticky'=>true));
		}

		$response->send();

	}

	public function actionGetColumnValue()
	{

		// @todo (rponudic) remove this section if not needed anymore
		$pk = json_decode($_POST['data'], true);
		$row = Row::model()->findByPk($pk);

		return $row->getAttribute($_POST['attribute']);

	}

	public function actionDelete()
	{

		$data = json_decode($_POST['data'], true);

		foreach($data AS $attributes) {

			$row = new Row;
			$row->attributes = $attributes;
			$row->delete();

		}

		Yii::app()->end('redirect: xyz.com');
	}

}