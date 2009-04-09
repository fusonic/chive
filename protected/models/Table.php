<?php

class Table extends CActiveRecord
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
			'schema' => array(self::BELONGS_TO, 'Schema', 'TABLE_SCHEMA'),
			'columns' => array(self::HAS_MANY, 'Column', 'TABLE_SCHEMA, TABLE_NAME', 'order'=>'??.ORDINAL_POSITION ASC', 'alias'=>'TableColumn'),
			'indices' => array(self::HAS_MANY, 'Index', 'TABLE_SCHEMA, TABLE_NAME', 'alias'=>'TableIndex'),
			#'constraints' => array(self::HAS_MANY, 'Constraint', 'TABLE_SCHEMA, TABLE_NAME', 'alias'=>'TableConstraint'),
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
		);
	}

	public function getName() {
		return $this->TABLE_NAME;
	}

	public function getRowCount() {
		return (int)$this->TABLE_ROWS;
	}

	public function getAverageRowSize() {
		if($rowCount = $this->getRowCount() > 0)
			return $this->DATA_LENGTH / $rowCount;
		else
			return '-';
	}

	/*
	 * Truncate the table (delete all values)
	 */
	public function truncate() {

		// @todo(rponudic): Work with parameters!
		$db = Yii::app()->getDb();
		$cmd = $db->createCommand('TRUNCATE TABLE ' . $db->quoteTableName($this->TABLE_SCHEMA) . '.' . $db->quoteTableName($this->TABLE_NAME));
		try
		{
			$cmd->prepare();
			$cmd->execute();
			return true;
		}
		catch(CDbException $ex)
		{
			return false;
		}

	}

	/*
	 * Drop table (delete structure and containing data)
	 */
	public function drop() {

		// @todo(rponudic): Work with parameters!
		$db = Yii::app()->getDb();
		$cmd = $db->createCommand('DROP TABLE ' . $db->quoteTableName($this->TABLE_SCHEMA) . '.' . $db->quoteTableName($this->TABLE_NAME));

		try
		{
			$cmd->prepare();
			$cmd->execute();
			return true;
		}
		catch(CDbException $ex)
		{
			return false;
		}

	}

}