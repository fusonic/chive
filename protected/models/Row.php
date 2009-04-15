<?php

class Row extends CActiveRecord
{

	public $schema;
	public $table;

	public static $db;

	public function __construct($attributes=array(),$scenario='') {

		$request = Yii::app()->getRequest();

		$this->schema = $request->getParam('schema');
		$this->table = $request->getParam('table');

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
		return $this->table;
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
		return self::$db->getSchema()->getTable($this->table)->getColumnNames();
	}

	public function attributeNames()
	{
		return self::$db->getSchema()->getTable($this->table)->getColumnNames();
	}

	public function safeAttributes() {
		return self::$db->getSchema()->getTable($this->table)->getColumnNames();
	}

	public function getDbConnection() {
		return self::$db;
	}

}