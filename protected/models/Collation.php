<?php

class Collation extends CActiveRecord
{
	public $collationGroup;

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
		return 'COLLATIONS';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('COLLATION_NAME','length','max'=>64),
			array('CHARACTER_SET_NAME','length','max'=>64),
			array('IS_DEFAULT','length','max'=>3),
			array('IS_COMPILED','length','max'=>3),
			array('ID, SORTLEN', 'numerical'),
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