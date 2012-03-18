<?php

class Row extends CActiveRecord
{

	private $originalAttributes;
	
	private $_functions;
	private $_hexvalues = array();

	public static $db;
	public static $schema;
	public static $table;
	
	/*
	 * @var Available database functions
	 */
	public static $functions = array(
			'',
			'ASCII',
			'CHAR',
			'MD5',
			'SHA1',
			'ENCRYPT',
			'RAND',
			'LAST_INSERT_ID',
			'UNIX_TIMESTAMP',
			'COUNT',
			'AVG',
			'SUM',
			'SOUNDEX',
			'LCASE',
			'UCASE',
			'NOW',
			'PASSWORD',
			'OLD_PASSWORD',
			'COMPRESS',
			'UNCOMPRESS',
			'CURDATE',
			'CURTIME',
			'UTC_DATE',
			'UTC_TIME',
			'UTC_TIMESTAMP',
			'FROM_DAYS',
			'FROM_UNIXTIME',
			'PERIOD_ADD',
			'PERIOD_DIFF',
			'TO_DAYS',
			'USER',
			'WEEKDAY',
			'CONCAT',
			'HEX',
			'UNHEX',
	);

	/**
	 * @see CActiveRecord::instantiate()
	 */
	public function instantiate($attributes)
	{
		$res = parent::instantiate($attributes);
		$res->originalAttributes = $attributes;

		return $res;
	}
	
	public function populateRecord($attributes,$callAfterFind=true)
	{
		$table = $this->getMetaData()->tableSchema;
		
		foreach($table->columns AS $column)
		{
			if(DataType::getInputType($column->dbType) != "file")
			{
				if(isset($attributes[$column->name]))
				{
					SqlUtil::FixValue($attributes[$column->name]);
				}				
			}
		}
		
		return parent::populateRecord($attributes, $callAfterFind);
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
		return self::$table;
	}

	/*
	 * @return string primary key columns
	 */
	public function primaryKey()
	{
		return self::$db->getSchema(self::$schema)->getTable(self::$table)->primaryKey;
	}

	/**
	 * @return array key value pairs of primary key (if no primary key, all column values will be returned)
	 */
	public function getIdentifier() 
	{
		$table=$this->getMetaData()->tableSchema;
		if(is_string($table->primaryKey))
		{
			return array(
				$table->primaryKey => $this->{$table->primaryKey}
			);
		}
		else if(is_array($table->primaryKey))
		{
			$values=array();
			foreach($table->primaryKey as $name)
				$values[$name]=$this->$name;
			return $values;
		}
		else
		{
			$values = array();
			foreach($table->columns AS $column)
			{
				if(DataType::getInputType($column->dbType) != "file")
				{
					$values[$column->name] = $this->getAttribute($column->name);
				}					
			}
			return $values;
		}
	}
	
	/**
	 * @return array key value pairs of primary key (if no primary key, all column values will be returned)
	 */
	public function getOriginalIdentifier() 
	{
		$table=$this->getMetaData()->tableSchema;
		if(is_string($table->primaryKey))
		{
			return array(
				$table->primaryKey => $this->originalAttributes[$table->primaryKey]
			);
		}
		else if(is_array($table->primaryKey))
		{
			$values=array();
			foreach($table->primaryKey as $name)
				$values[$name]=$this->originalAttributes[$name];
			return $values;
		}
		else
		{
			$values = array();
			foreach($table->columns AS $column)
			{
				if(DataType::getInputType($column->dbType) != "file")
				{
					$values[$column->name] = $this->originalAttributes[$column->name];
				}			
			}
			return $values;
		}
	}

	public function getAttributeAsArray($_attribute)
	{
		$value = explode(",", $this->$_attribute);
		return $value; 
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return self::$db->getSchema()->getTable(self::$table)->getColumnNames();
	}

	public function attributeNames()
	{
		return self::$db->getSchema()->getTable(self::$table)->getColumnNames();
	}

	/**
	 * @see		ActiveRecord::rules()
	 */
	public function rules()
	{
		$return = array();
		foreach(self::$db->getSchema()->getTable(self::$table)->getColumnNames() as $column)
		{
			$return[] = array($column, 'type', 'type' => 'string');
		}
		return $return;
	}

	public function getDbConnection() {
		return self::$db;
	}

	public function insert($attributes=null) 
	{	
		$table = Table::model()->findByPk(array(
			'TABLE_NAME' => self::$table,
			'TABLE_SCHEMA' => self::$schema,
		));
		$attributesCount = count($this->getAttributes());
		$sql = 'INSERT INTO ' . self::$db->quoteTableName(self::$table) . ' (';

		$i = 0;
		foreach($table->columns AS $column)
		{
			$sql .= self::$db->quoteColumnName($column->COLUMN_NAME);
			$i++;

			if($i < $attributesCount)
				$sql .= ', ';
		}

		$sql .= ') VALUES ' . "\n\t" . '(';

		$i = 0;
		foreach($table->columns AS $column)
		{
			$function = $this->getFunction($column->COLUMN_NAME);
			$value = $this->getAttribute($column->COLUMN_NAME);
			
			if($function !== null)
			{
				$sql .= self::$functions[$function] . '(' . ($value === null ? 'NULL' : self::$db->quoteValue($value))  . ')';
			}
			elseif($value === null)
			{
				$sql .= 'NULL';
			}
			elseif($this->isHex($column->COLUMN_NAME))
			{
				$sql .= $value;
			}
			elseif(is_array($value))
			{
				$sql .= self::$db->quoteValue(implode(",", $value));
			}
			elseif($column->DATA_TYPE == "bit")
			{
				$sql .= (int)$value;				
			}
			elseif($column->DATA_TYPE == "int")
			{
				$sql .= (int)$value;
			}
			// DEFAULT
			else
			{
				$sql .= self::$db->quoteValue($value);
			}

			$i++;
			
			if($i < $attributesCount)
				$sql .= ', ';

			
		}
		
		$sql .= ');';
		
		$cmd = new CDbCommand(self::$db, $sql);
		
		try
		{
			$this->beforeSave();
			$cmd->prepare();
			$cmd->execute();
			$this->afterSave();
			return $sql;
		}
		catch(CDbException $ex)
		{
			throw new DbException($cmd);
		}
		
	}
		
	public function getAttribute($name)
	{
		$value = parent::getAttribute($name);
		
		if(is_array($value))
		{
			$value = implode(",", $value);
		}
		
		return $value;
	}
	
	public function update($attributes = null)
	{
		if($this->getIsNewRecord())
		{
			throw new CDbException(Yii::t('core','The active record cannot be updated because it is new.'));
		}
		if(!$this->beforeSave())
		{
			return false;
		}

		$sql = '';
		
		$table = Table::model()->findByPk(array(
			'TABLE_NAME' => self::$table,
			'TABLE_SCHEMA' => self::$schema,
		));
			
		// Check if there has been changed any attribute
		$changedAttributes = array();
		foreach($this->originalAttributes AS $column=>$value)
		{
			if($this->getAttribute($column) !== $value || $this->getFunction($column))
			{
				// SET datatype
				$changedAttributes[$column] = $this->getAttribute($column);
			}
		}
		
		$changedAttributesCount = count($changedAttributes);
		
		
		if($changedAttributesCount > 0)
		{

			$sql = 'UPDATE ' . self::$db->quoteTableName(self::$table) . ' SET ' . "\n";
				
			foreach($changedAttributes AS $column=>$value)
			{
				$columnInfo = $this->getColumnInfo($table, $column);
				
				$function = $this->getFunction($column);
				
				$sql .= "\t" . self::$db->quoteColumnName($column) . ' = ';
				
				if($function !== null)
				{
					$sql .= self::$functions[$function] . '(' . ($value === null ? 'NULL' : self::$db->quoteValue($value))  . ')';
				}
				elseif($columnInfo->IS_NULLABLE === "YES" && is_null($value))
				{
					$sql .= 'NULL';
				}
				elseif($this->isHex($column))
				{
					$sql .= $value;
				}
				elseif(is_array($value))
				{
					$sql .= self::$db->quoteValue(implode(",", $value));
				}
				elseif($columnInfo->DATA_TYPE == "int")
				{
					$sql .= (int)$value;				
				}
				elseif($columnInfo->DATA_TYPE == "bit")
				{
					$sql .= (int)$value;	
				}
				else
				{
					$sql .= (is_null($value) ? 'NULL' : self::$db->quoteValue($value));
				}

				$changedAttributesCount--;

				if($changedAttributesCount > 0)
					$sql .= ',' . "\n";

			}
				
			$sql .= "\n" . 'WHERE ' . "\n";
				
			$identifier = $this->getOriginalIdentifier();
					
			// Create find criteria
			$count = count($identifier);
			foreach($identifier AS $column=>$value) {

				if(is_null($value))
				{
					$sql .= "\t" . self::$db->quoteColumnName($column) . ' IS NULL ';
				}
				else
				{
					$sql .= "\t" . self::$db->quoteColumnName($column) . ' = ' . self::$db->quoteValue($this->originalAttributes[$column]) . ' ';
				}

				$count--;

				if($count > 0)
				{
					$sql .= 'AND ' . "\n";
				}
			}
				
			$sql .= "\n" . 'LIMIT 1;';
				
		}
		
		$cmd = new CDbCommand(self::$db, $sql);
		
		try
		{
			$cmd->prepare();
			$cmd->execute();
			$this->afterSave();
			return $sql;
		}
		catch(CDbException $ex)
		{
			throw new DbException($cmd);
		}

	}

	public function delete()
	{

		if($this->getIsNewRecord())
		{
			throw new CDbException(Yii::t('core','The active record cannot be deleted because it is new.'));
		}
		if(!$this->beforeDelete())
		{
			return false;
		}

		$identifier = $this->getIdentifier();
			
		$sql = 'DELETE FROM ' . self::$db->quoteTableName(self::$table) . ' WHERE ';

		$count = count($identifier);
		foreach($identifier AS $column => $value)
		{
			$sql .= "\n\t" . self::$db->quoteColumnName($column) . (is_null($value) ? ' IS NULL' :  ' = ' . self::$db->quoteValue($value));
			
			$count--;

			if($count > 0)
			{
				$sql .= ' AND';
			}

		}

		$sql .= "\n" . 'LIMIT 1;';

		$cmd = self::$db->createCommand($sql);

		try
		{
			$cmd->execute();
			$this->afterDelete();
			return $sql;
		}
		catch(CDbException $ex)
		{
			$this->afterDelete();
			throw new DbException($cmd);
			return false;
		}

	}


	private function getColumnInfo($table, $columnName)
	{
		foreach($table->columns as $columnInfo)
		{
			if($columnInfo->COLUMN_NAME == $columnName)
			{
				return $columnInfo;
			}
		}
		
		return null;
	}
	
	public function setFunction($_attribute, $_function)
	{
		if(isset(self::$functions[$_function]))
		{
			$this->_functions[$_attribute] = $_function;
		}
	}
	
	public function getFunction($_attribute)
	{
		if(isset($this->_functions[$_attribute]))
		{
			return $this->_functions[$_attribute];
		}
		else
		{
			return null;
		}
	}
	
	public function setHex($_attribute)
	{
		$this->_hexvalues[$_attribute] = true;
	}
	
	
	public function isHex($_attribute)
	{
		if(isset($this->_hexvalues[$_attribute]) && $this->_hexvalues[$_attribute] === true)
		{
			return true;
		}
		
		return false;
	}
}