<?php

class Database extends CActiveRecord
{

	public $tableCount;

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
		return 'SCHEMATA';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('CATALOG_NAME','length','max'=>512),
			array('SCHEMA_NAME','length','max'=>64),
			array('DEFAULT_CHARACTER_SET_NAME','length','max'=>64),
			array('DEFAULT_COLLATION_NAME','length','max'=>64),
			array('SQL_PATH','length','max'=>512),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'table' => array(self::HAS_MANY, 'Table', 'TABLE_SCHEMA', 'joinType'=>'INNER JOIN'),
			'collation' => array(self::BELONGS_TO, 'Collation', 'DEFAULT_COLLATION_NAME'),
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

	public function getName() {
		return $this->SCHEMA_NAME;
	}

	/*
	 * @return string primary key column
	 */
	public function primaryKey() {
		return 'SCHEMA_NAME';
	}
}