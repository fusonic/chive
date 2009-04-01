<?php

class Column extends CActiveRecord
{
	public function __construct($attributes=array(), $scenario='') {

		if($attributes===null)
		 {
		      $tableName=$this->tableName();
		      if(($table=$this->getDbConnection()->getSchema()->getTable($tableName))===null)
		         throw new CDbException(Yii::t('yii','The table "{table}" for active record class "{class}" cannot be found in the database.',
		            array('{class}'=>get_class($model),'{table}'=>$tableName)));

		      $table->primaryKey=$this->primaryKey();
		      $table->columns[$table->primaryKey]->isPrimaryKey=true;

		   }

		   parent::__construct($attributes,$scenario);

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
		return 'COLUMNS';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('TABLE_CATALOG','length','max'=>512),
			array('TABLE_SCHEMA','length','max'=>64),
			array('TABLE_NAME','length','max'=>64),
			array('COLUMN_NAME','length','max'=>64),
			array('IS_NULLABLE','length','max'=>3),
			array('DATA_TYPE','length','max'=>64),
			array('CHARACTER_SET_NAME','length','max'=>64),
			array('COLLATION_NAME','length','max'=>64),
			array('COLUMN_KEY','length','max'=>3),
			array('EXTRA','length','max'=>20),
			array('PRIVILEGES','length','max'=>80),
			array('COLUMN_COMMENT','length','max'=>255),
			array('COLUMN_TYPE', 'required'),
			array('ORDINAL_POSITION, CHARACTER_MAXIMUM_LENGTH, CHARACTER_OCTET_LENGTH, NUMERIC_PRECISION, NUMERIC_SCALE', 'numerical'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'table' => array(self::BELONGS_TO, 'Table', 'TABLE_NAME'),
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

	public function primaryKey() {
		return 'COLUMN_NAME';
	}
}