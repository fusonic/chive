<?php

class Table extends CActiveRecord
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
		return 'TABLES';
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
			array('TABLE_TYPE','length','max'=>64),
			array('ENGINE','length','max'=>64),
			array('ROW_FORMAT','length','max'=>10),
			array('TABLE_COLLATION','length','max'=>64),
			array('CREATE_OPTIONS','length','max'=>255),
			array('TABLE_COMMENT','length','max'=>80),
			array('VERSION, TABLE_ROWS, AVG_ROW_LENGTH, DATA_LENGTH, MAX_DATA_LENGTH, INDEX_LENGTH, DATA_FREE, AUTO_INCREMENT, CHECKSUM', 'numerical'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'database' => array(self::BELONGS_TO, 'Database', 'TABLE_SCHEMA'),
			'columns' => array(self::HAS_MANY, 'Column', 'COLUMN_NAME', 'order'=>'??.ORDINAL_POSITION ASC'),
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
		return 'TABLE_SCHEMA';
	}

	public function getName() {
		return $this->TABLE_NAME;
	}

	public function getRowCount() {
		return $this->TABLE_ROWS;
	}

	public function isEmpty() {
		return (bool)!$this->getRowCount();
	}
}