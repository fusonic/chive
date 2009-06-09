<?php

class IndexColumn extends CActiveRecord
{

	/**
	 * @see		CActiveRecord::model()
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see		CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'STATISTICS';
	}

	/**
	 * @see		CActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return array(
			'TABLE_SCHEMA',
			'TABLE_NAME',
			'INDEX_NAME',
			'COLUMN_NAME',
		);
	}

	/**
	 * @see		CActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
			'index' => array(self::BELONGS_TO, 'Index', 'TABLE_SCHEMA, TABLE_NAME, INDEX_NAME'),
		);
	}
}