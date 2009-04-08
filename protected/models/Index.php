<?php

class Index extends CActiveRecord
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
		return 'STATISTICS';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('TABLE_CATALOG','length','max'=>512),
			array('TABLE_SCHEMA','required','length','max'=>64),
			array('TABLE_NAME','required','length','max'=>64),
			array('INDEX_SCHEMA','required','length','max'=>64),
			array('INDEX_NAME','required','length','max'=>64),
			array('COLLATION','length','max'=>1),
			array('PACKED','length','max'=>10),
			array('NULLABLE','required','length','max'=>3),
			array('INDEX_TYPE','required','length','max'=>16),
			array('COMMENT','length','max'=>16),
			array('NON_UNIQUE, SEQ_IN_INDEX, CARDINALITY, SUB_PART', 'numerical'),
		);

	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'table' => array(self::BELONGS_TO, 'Table', 'TABLE_SCHEMA, TABLE_NAME'),
			'column' => array(self::HAS_ONE, 'Column', 'TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME', 'alias'=>'IndexColumn'),
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
			'TABLE_SCHEMA',
			'TABLE_NAME',
			'COLUMN_NAME',
			'INDEX_NAME',
		);
	}

	/*
	 * @return string type
	 */
	public function getType() {

		if($this->INDEX_NAME == 'PRIMARY')
		{
			return 'PRIMARY';
		}
		elseif($this->INDEX_TYPE == 'FULLTEXT')
		{
			return 'FULLTEXT';
		}
		elseif($this->NON_UNIQUE == 0)
		{
			return 'UNIQUE';
		}
		else {
			return 'INDEX';
		}

	}

}