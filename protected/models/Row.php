<?php

class Row extends CActiveRecord
{

	public $schema;
	public $table;

	public static $db;

	public function __construct($attributes=array(),$scenario='') {

		$request = Yii::app()->getRequest();

		$this->schema = $request->getParam('schema');
		$this->table = $request->getParam('table') ? $request->getParam('table') : $request->getParam('view');

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

	/*
	 * @return string primary key columns
	 */
	public function primaryKey()
	{
		predie(self::$db->getSchema($this->schema)->getTable($this->table)->primaryKey);
		return self::$db->getSchema($this->schema)->getTable($this->table)->primaryKey;
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

	public function save()
	{

	}

	public function delete()
	{

		if($this->getIsNewRecord())
		{
			throw new CDbException(Yii::t('yii','The active record cannot be deleted because it is new.'));
		}
		if(!$this->beforeDelete())
		{
			return false;
		}


		$pk = (array)self::$db->getSchema($this->schema)->getTable($this->table)->primaryKey;
		$pkCount = count($pk);
		
		$sql = 'DELETE FROM ' . self::$db->quoteTableName($this->table) . ' WHERE ';

		$i = 0;
		foreach($pk AS $column)
		{
			$sql .= "\n\t" . self::$db->quoteColumnName($column) . ' = ' . self::$db->quoteValue($this->$column);
			$i++;

			if($i < $pkCount)
				$sql .= ' AND';

		}

		$cmd = self::$db->createCommand($sql);

		try
		{
			$cmd->prepare();
			$cmd->execute();
			$this->afterDelete();
			return $sql;
		}
		catch(CDbException $ex)
		{
			$this->afterDelete();
			throw new DbException($cmd);
			return false;
		}

	}

}