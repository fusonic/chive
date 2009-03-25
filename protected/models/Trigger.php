<?php

class Trigger extends CActiveRecord
{
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
		return 'TRIGGERS';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('TRIGGER_CATALOG','length','max'=>512),
			array('TRIGGER_SCHEMA','length','max'=>64),
			array('TRIGGER_NAME','length','max'=>64),
			array('EVENT_MANIPULATION','length','max'=>6),
			array('EVENT_OBJECT_CATALOG','length','max'=>512),
			array('EVENT_OBJECT_SCHEMA','length','max'=>64),
			array('EVENT_OBJECT_TABLE','length','max'=>64),
			array('ACTION_ORIENTATION','length','max'=>9),
			array('ACTION_TIMING','length','max'=>6),
			array('ACTION_REFERENCE_OLD_TABLE','length','max'=>64),
			array('ACTION_REFERENCE_NEW_TABLE','length','max'=>64),
			array('ACTION_REFERENCE_OLD_ROW','length','max'=>3),
			array('ACTION_REFERENCE_NEW_ROW','length','max'=>3),
			array('ACTION_STATEMENT, SQL_MODE, DEFINER', 'required'),
			array('ACTION_ORDER', 'numerical'),
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
		return array(
		);
	}
}