<?php

class Row extends CActiveRecord
{

	public $schemaName;
	public $tableName;

	private $_db;

	public function __construct($attributes=array(),$scenario='') {

		$this->_db = new CDbConnection('mysql:host='.Yii::app()->user->host.';dbname=' . $_GET['schema'], Yii::app()->user->name, Yii::app()->user->password);
		$this->_db->charset='utf8';
		$this->_db->active = true;

		$this->schemaName = $_GET['schema'];
		$this->tableName = $_GET['table'];

	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return $this->tableName;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return $this->_db->getSchema()->getTable($this->tableName)->getColumnNames();
	}

	public function attributeNames()
	{
		return $this->_db->getSchema()->getTable($this->tableName)->getColumnNames();
	}

	public function safeAttributes() {
		return $this->_db->getSchema()->getTable($this->tableName)->getColumnNames();
	}

	public function getDbConnection() {
		return $this->_db;
	}

}