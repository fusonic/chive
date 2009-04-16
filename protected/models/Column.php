<?php

class Column extends CActiveRecord
{

	public $COLLATION_NAME = Collation::DEFAULT_COLLATION;

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
			'size',
			'scale',
			'collation',
			'autoIncrement',
			'COLUMN_COMMENT',
			'COLLATION_NAME',
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
			'size' => Yii::t('core', 'size'),
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

	public function getSize()
	{
		if(preg_match('/^\w+\((\d+)(,\d+)?\)$/', $this->COLUMN_TYPE, $res))
		{
			return $res[1];
		}
	}

	public function setSize($value)
	{
		$this->COLUMN_TYPE = $this->DATA_TYPE . ($value ? '(' . $value . ')' : '');
	}

	public function getScale()
	{
		if(preg_match('/^\w+\((\d+)(,\d+)?\)$/', $this->COLUMN_TYPE, $res))
		{
			if(isset($res[2]))
			{
				return substr($res[2], 1);
			}
			else
			{
				return 0;
			}
		}
	}

	public function setScale($value)
	{
		$this->COLUMN_TYPE = $this->DATA_TYPE . ($this->size ? '(' . $this->size . (!is_null($value) ? ',' . $value : '') . ')' : '');
	}

	public function getDataType()
	{
		return $this->DATA_TYPE;
	}

	public function setDataType($value)
	{
		$size = $this->getSize();
		$scale = $this->getScale();
		$this->DATA_TYPE = $value;
		$this->COLUMN_TYPE = $value . ($size ? '(' . $size . ($scale ? ',' . $scale : '') . ')' : '');
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

	public function getColumnDefinition()
	{
		if(DataType::supportsCollation($this->DATA_TYPE))
		{
			$collate = ' CHARACTER SET ' . Collation::getCharacterSet($this->COLLATION_NAME) . ' COLLATE ' . $this->COLLATION_NAME;
		}
		else
		{
			$collate = '';
		}

		return self::$db->quoteColumnName($this->COLUMN_NAME)
			. ' ' . $this->COLUMN_TYPE . $collate
			. ' ' . ($this->IS_NULLABLE == 'YES' ? 'NULL' : 'NOT NULL')
			. ' ' . ($this->COLUMN_DEFAULT ? 'DEFAULT :defaultValue' : ($this->getIsNullable() ? 'DEFAULT NULL' : ''))
			. ' ' . ($this->EXTRA == 'auto_increment' ? 'AUTO_INCREMENT' : '')
			. ' ' . (strlen($this->COLUMN_COMMENT) ? 'COMMENT :comment' : '');
	}

	protected function bindColumnDefinitionValues($sql)
	{
		if($this->COLUMN_DEFAULT)
		{
			$sql->bindParam('defaultValue', $this->COLUMN_DEFAULT, PDO::PARAM_STR);
		}
		if(strlen($this->COLUMN_COMMENT))
		{
			$sql->bindParam('comment', $this->COLUMN_COMMENT, PDO::PARAM_STR);
		}
	}

	public function move($command)
	{
		$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME)
			. ' MODIFY ' . $this->getColumnDefinition()
			. ' ' . (substr($command, 0, 6) == 'AFTER ' ? 'AFTER ' . self::$db->quoteColumnName(substr($command, 6)) : 'FIRST');
		$cmd = new CDbCommand(self::$db, $sql);
		$this->bindColumnDefinitionValues($cmd);
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

	public function update()
	{
		if($this->getIsNewRecord())
		{
			throw new CDbException(Yii::t('yii','The active record cannot be updated because it is new.'));
		}
		if(!$this->beforeSave())
		{
			return false;
		}

		$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME)
			. ' MODIFY ' . $this->getColumnDefinition();
		$cmd = new CDbCommand(self::$db, $sql);
		$this->bindColumnDefinitionValues($cmd);
		try
		{
			$cmd->prepare();
			$cmd->execute();
			$this->afterSave();
			return $sql;
		}
		catch(CDbException $ex)
		{
			$errorInfo = $cmd->getPdoStatement()->errorInfo();
			$this->addError('COLUMN_NAME', Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			$this->afterSave();
			return false;
		}
	}

	public function insert()
	{
		if(!$this->getIsNewRecord())
		{
			throw new CDbException(Yii::t('yii','The active record cannot be inserted to database because it is not new.'));
		}
		if(!$this->beforeSave())
		{
			return false;
		}

		$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME)
			. ' ADD ' . $this->getColumnDefinition();
		$cmd = new CDbCommand(self::$db, $sql);
		$this->bindColumnDefinitionValues($cmd);
		try
		{
			$cmd->prepare();
			$cmd->execute();
			$this->afterSave();
			return $sql;
		}
		catch(CDbException $ex)
		{
			$errorInfo = $cmd->getPdoStatement()->errorInfo();
			$this->addError('COLUMN_NAME', Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			$this->afterSave();
			return false;
		}
	}

	public function delete()
	{
		if($this->getIsNewRecord())
		{
			throw new CDbException(Yii::t('yii','The active record cannot be deleted because it is new.'));
		}
		if(!$this->beforeDelete())
		{
			return false;
		}

		$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME)
			. ' DROP ' . self::$db->quoteColumnName($this->COLUMN_NAME);
		$cmd = self::$db->createCommand($sql);
		try
		{
			$cmd->prepare();
			$cmd->execute();
			$this->afterDelete();
			return $sql;
		}
		catch(CDbException $ex)
		{
			$this->afterDelete();
			throw new DbException($cmd);
		}
	}

	protected function afterSave()
	{
		$this->refresh();
		parent::afterSave();
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