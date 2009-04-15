<?php

class Key extends CActiveRecord

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
		return 'KEY_COLUMN_USAGE';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('CONSTRAINT_CATALOG','length','max'=>512),
			array('CONSTRAINT_SCHEMA','required','length','max'=>64),
			array('CONSTRAINT_NAME','required','length','max'=>64),
			array('TABLE_CATALOG','length','max'=>512),
			array('TABLE_SCHEMA','required','length','max'=>64),
			array('TABLE_NAME','required','length','max'=>64),
			array('COLUMN_NAME','required','length','max'=>64),
			array('ORDINAL_POSITION, POSITION_IN_UNIQUE_CONSTRAINT', 'numerical'),
			array('REFERENCED_TABLE_SCHEMA','length','max'=>64),
			array('REFERENCED_TABLE_NAME','length','max'=>64),
			array('REFERENCED_COLUMN_NAME','length','max'=>64),
		);

	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'constraint' => array(self::BELONGS_TO, 'Constraint', 'CONSTRAINT_NAME'),
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

	public function primaryKey()
	{
		return array(
			'CONSTRAINT_SCHEMA',
			'CONSTRAINT_NAME',
			'TABLE_SCHEMA',
			'TABLE_NAME',
			'COLUMN_NAME',
		);
	}

}