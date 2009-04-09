<?php

class Column extends CActiveRecord
{

	public static $db;

	public function __construct($attributes=array(), $scenario='') {

		if($attributes===null)
		 {
		      $tableName=$this->tableName();
		      if(($table=$this->getDbConnection()->getSchema()->getTable($tableName))===null)
		         throw new CDbException(Yii::t('yii','The table "{table}" for active record class "{class}" cannot be found in the database.',
		            array('{class}'=>get_class($model),'{table}'=>$tableName)));

		      $table->primaryKey=$this->primaryKey();
		      foreach($table->columns AS $key=>$column) {
		      	$table->columns[$key]->isPrimaryKey = true;
		      }

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

	public function safeAttributes()
	{
		return array(
			'COLUMN_NAME',
			'COLUMN_DEFAULT',
			'isNullable',
			'dataType',
			'precision',
			'collation',
			'autoIncrement',
			'COLUMN_COMMENT',
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'table' => array(self::BELONGS_TO, 'Table', 'TABLE_NAME'),
			'indices' => array(self::HAS_MANY, 'Index', 'TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME'),
			'collation' => array(self::BELONGS_TO, 'Collation', 'COLLATION_NAME'),
			#'constraint' => array(self::MANY_MANY, 'Constraint', 'COLUMN_NAME'),
		);
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'COLUMN_NAME' => Yii::t('core', 'name'),
			'COLLATION_NAME' => Yii::t('database', 'collation'),
			'COLUMN_COMMENT' => Yii::t('core', 'comment'),
		);
	}

	public function primaryKey() {
		return array(
			'TABLE_SCHEMA',
			'TABLE_NAME',
			'COLUMN_NAME',
		);
	}

	public function getAutoIncrement()
	{
		return $this->EXTRA == 'auto_increment';
	}

	public function setAutoIncrement($value)
	{
		$this->EXTRA = ($value ? 'auto_increment' : null);
	}

	public function getIsNullable()
	{
		return $this->IS_NULLABLE == 'YES';
	}

	public function setIsNullable($value)
	{
		$this->IS_NULLABLE = ($value ? 'YES' : 'NO');
	}

	public function getCollation()
	{
		return $this->COLLATION_NAME;
	}

	public function setCollation($value)
	{
		$this->COLLATION_NAME = $value;
		$data = explode('_', $value);
		$this->CHARACTER_SET_NAME = $data[0];
	}

	public function getPrecision()
	{
		if(preg_match('/^\w+\((\d+)\)$/', $this->COLUMN_TYPE, $res))
		{
			return $res[1];
		}
	}

	public function setPrecision($value)
	{
		$this->COLUMN_TYPE = $this->DATA_TYPE . '(' . $value . ')';
	}

	public function getDataType()
	{
		return $this->DATA_TYPE;
	}

	public function setDataType($value)
	{
		$precision = $this->precision;
		$this->COLUMN_TYPE = $value . ($this->precision ? '(' . $this->precision . ')' : '');
	}

	public function getIsPartOfPrimaryKey($indices = null)
	{
		$res = false;
		if(is_null($indices))
		{
			$indices = $this->indices;
		}
		foreach($indices AS $index)
		{
			if($index->INDEX_NAME == 'PRIMARY' && $index->COLUMN_NAME == $this->COLUMN_NAME)
			{
				$res = true;
				break;
			}
		}
		return $res;
	}

	public function move($command)
	{

		$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME)
			. ' MODIFY ' . self::$db->quoteColumnName($this->COLUMN_NAME)
			. ' ' . $this->COLUMN_TYPE
			. ' ' . ($this->IS_NULLABLE == 'YES' ? 'NULL' : 'NOT NULL')
			. ' ' . ($this->COLUMN_DEFAULT || $this->IS_NULLABLE ? 'DEFAULT :defaultValue' : '')
			. ' ' . ($this->EXTRA == 'auto_increment' ? 'AUTO_INCREMENT' : '')
			. ' ' . (strlen($this->COLUMN_COMMENT) ? 'COMMENT :comment' : '')
			. ' ' . (substr($command, 0, 6) == 'AFTER ' ? 'AFTER ' . self::$db->quoteColumnName(substr($command, 6)) : 'FIRST');

		$sql = new CDbCommand(self::$db, $sql);
		if($this->COLUMN_DEFAULT || $this->IS_NULLABLE)
		{
			$sql->bindParam('defaultValue', $this->COLUMN_DEFAULT, PDO::PARAM_STR);
		}
		if(strlen($this->COLUMN_COMMENT))
		{
			$sql->bindParam('comment', $this->COLUMN_COMMENT, PDO::PARAM_STR);
		}
		return $sql->execute();

	}

	public static function getDataTypes()
	{

		$types = array();

		// Numeric
		$types[Yii::t('dataTypes', 'numeric')] =  array(
			'bit' => 'bit',
			'tinyint' => 'tinyint',
			'bool' => 'bool',
			'smallint' => 'smallint',
			'mediumint' => 'mediumint',
			'int' => 'int',
			'bigint' => 'bigint',
			'float' => 'float',
			'double' => 'double',
			'decimal' => 'decimal',
		);

		// Strings
		$types[Yii::t('dataTypes', 'strings')] = array(
			'char' => 'char',
			'varchar' => 'varchar',
			'tinytext' => 'tinytext',
			'text' => 'text',
			'mediumtext' => 'mediumtext',
			'longtext' => 'longtext',
			'tinyblob' => 'tinyblob',
			'blob' => 'blob',
			'mediumblob' => 'mediumblob',
			'longblob' => 'longblob',
			'binary' => 'binary',
			'varbinary' => 'varbinary',
		);

		// Date and time
		$types[Yii::t('dataTypes', 'dateAndTime')] = array(
			'date' => 'date',
			'datetime' => 'datetime',
			'timestamp' => 'timestamp',
			'time' => 'time',
			'year' => 'year',
		);

		return $types;

	}

}