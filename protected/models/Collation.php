<?php

class Collation extends CActiveRecord
{
	public $collationGroup;

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
			'database' => array(self::HAS_MANY, 'Database', 'DEFAULT_COLLATION_NAME'),
			'characterSet' => array(self::BELONGS_TO, 'CharacterSet', 'CHARACTER_SET_NAME'),
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

	/*
	 * @return string primary key column
	 */
	public function primaryKey()
	{
		return 'COLLATION_NAME';
	}

	public static function getDefinition($collation)
	{
		$data = explode('_', $collation);
		$text = Yii::t('collation', $data[0]) . ', ' . Yii::t('collation', $data[1]);
		if(count($data) == 3)
		{
			$text .= ' (' . Yii::t('collation', $data[2]) . ')';
		}
		return $text;
	}

}