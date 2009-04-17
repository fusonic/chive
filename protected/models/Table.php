<?php

class Table extends CActiveRecord
{

	public static $db;

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

		// @todo(rponudic): Work with parameters! Use correct DB connection.
		$db = Yii::app()->getDb();
		$cmd = $db->createCommand('TRUNCATE TABLE ' . $db->quoteTableName($this->TABLE_SCHEMA) . '.' . $db->quoteTableName($this->TABLE_NAME));
		try
		{
			$cmd->prepare();
			$cmd->execute();
			return $cmd->getPdoStatement();
		}
		catch(CDbException $ex)
		{
			$errorInfo = $cmd->getPdoStatement()->errorInfo();
			throw new DbException($sql, $errorInfo[1], $errorInfo[2]);
		}

	}

	/*
	 * Drop table (delete structure and containing data)
	 */
	public function drop() {

		// @todo(rponudic): Work with parameters! Use correct DB connection.
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

	/**
	 * Drops an index from this table.
	 *
	 * @param	string			name of the index
	 * @param	string			type of the index (index/unique/fulltext/primary)
	 * @return	string			sql statement
	 * @throws	DbException		If sql statement fails.
	 */
	public function dropIndex($index, $type)
	{
		// Create command
		$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME)
			. ' DROP INDEX ' . self::$db->quoteColumnName($index);
		$cmd = self::$db->createCommand($sql);

		// Execute
		try
		{
			$cmd->prepare();
			$cmd->execute();
			return $sql;
		}
		catch(CDbException $ex)
		{
			throw new DbException($cmd);
		}
	}

	/**
	 * Creates an index in this table.
	 *
	 * @param	string			name of the index
	 * @param	string			type of the index (index/unique/fulltext/primary)
	 * @param	array			array of columns
	 * @return	string			sql statement
	 * @throws	DbException		If sql statement fails.
	 */
	public function createIndex($index, $type, array $columns)
	{
		// Prepare columns
		foreach($columns AS $key => $value)
		{
			$columns[$key] = self::$db->quoteColumnName($value);
		}
		$columns = implode(',', $columns);

		// Create command
		if(strtolower($type) == 'primary')
		{
			$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME)
				. ' ADD PRIMARY KEY (' . $columns . ')';
		}
		else
		{
			$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME)
				. ' ADD ' . $type . ' ' . self::$db->quoteColumnName($index) . ' (' . $columns . ')';
		}
		$cmd = self::$db->createCommand($sql);

		// Execute
		try
		{
			$cmd->prepare();
			$cmd->execute();
			return $sql;
		}
		catch(CDbException $ex)
		{
			throw new DbException($cmd);
		}
	}

}