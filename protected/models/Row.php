<?php

class Row extends CActiveRecord
{

	public $schemaName;
	public $tableName;

	public static $db;

	public function __construct($attributes=array(),$scenario='') {

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
		return self::$db->getSchema()->getTable($this->tableName)->getColumnNames();
	}

	public function attributeNames()
	{
		return self::$db->getSchema()->getTable($this->tableName)->getColumnNames();
	}

	public function safeAttributes() {
		return self::$db->getSchema()->getTable($this->tableName)->getColumnNames();
	}

	public function getDbConnection() {
		return self::$db;
	}

}