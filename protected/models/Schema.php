<?php

class Schema extends ActiveRecord
{

	public $tableCount;
	public $DEFAULT_CHARACTER_SET_NAME = Collation::DEFAULT_CHARACTER_SET;
	public $DEFAULT_COLLATION_NAME = Collation::DEFAULT_COLLATION;

	/**
	 * @see		CActiveRecord::model()
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see		CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'SCHEMATA';
	}

	/**
	 * @see		CActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return 'SCHEMA_NAME';
	}

	/**
	 * @see		CActiveRecord::safeAttributes()
	 */
	public function safeAttributes()
	{
		return array(
			'SCHEMA_NAME',
			'DEFAULT_COLLATION_NAME',
		);
	}

	/**
	 * @see		CActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
			'tables' => array(self::HAS_MANY, 'Table', 'TABLE_SCHEMA', 'condition' => '??.TABLE_TYPE IS NULL OR ??.TABLE_TYPE NOT IN (\'VIEW\')'),
			'views' => array(self::HAS_MANY, 'View', 'TABLE_SCHEMA'),
			'collation' => array(self::BELONGS_TO, 'Collation', 'DEFAULT_COLLATION_NAME'),
			'routines' => array(self::HAS_MANY, 'Routine', 'ROUTINE_SCHEMA'),
		);
	}

	/**
	 * @see		CActiveRecord::attributeLabels()
	 */
	public function attributeLabels()
	{
		return array(
			'SCHEMA_NAME' => Yii::t('core', 'name'),
			'DEFAULT_COLLATION_NAME' => Yii::t('database', 'collation'),
			'tableCount' => Yii::t('database', 'tables'),
		);
	}

	/**
	 * @see		ActiveRecord::getUpdateSql()
	 */
	protected function getUpdateSql()
	{
		return 'ALTER DATABASE ' . self::$db->quoteTableName($this->SCHEMA_NAME) . "\n"
			. "\t" . 'DEFAULT COLLATE = ' . self::$db->quoteValue($this->DEFAULT_COLLATION_NAME) . ';';
	}

	/**
	 * @see		ActiveRecord::getInsertSql()
	 */
	protected function getInsertSql()
	{
		return 'CREATE DATABASE ' . self::$db->quoteTableName($this->SCHEMA_NAME) . "\n"
			. "\t" . 'DEFAULT COLLATE = ' . self::$db->quoteValue($this->DEFAULT_COLLATION_NAME) . ';';
	}

	/**
	 * @see		ActiveRecord::getDeleteSql()
	 */
	protected function getDeleteSql()
	{
		return 'DROP DATABASE ' . self::$db->quoteTableName($this->SCHEMA_NAME) . ';';
	}

}