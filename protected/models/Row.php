<?php

class Row extends CActiveRecord
{

	public $schema;
	public $table;
	
	private $originalAttributes;
	
	public static $db;

	public function __construct($attributes=array(),$scenario='') {

		$request = Yii::app()->getRequest();

		$this->schema = $request->getParam('schema');
		$this->table = $request->getParam('table') ? $request->getParam('table') : $request->getParam('view');

		parent::__construct($attributes, $scenario);
	}

	/**
	 * @see CActiveRecord::instantiate()
	 */
	public function instantiate($attributes)
	{
		$res = parent::instantiate($attributes);
		$res->originalAttributes = $attributes;
		
		return $res;
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
		return $this->table;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/*
	 * @return string primary key columns
	 */
	public function primaryKey()
	{
		return self::$db->getSchema($this->schema)->getTable($this->table)->primaryKey;
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return self::$db->getSchema()->getTable($this->table)->getColumnNames();
	}

	public function attributeNames()
	{
		return self::$db->getSchema()->getTable($this->table)->getColumnNames();
	}

	public function safeAttributes() {
		return self::$db->getSchema()->getTable($this->table)->getColumnNames();
	}

	public function getDbConnection() {
		return self::$db;
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

		$sql = '';
		
		// Check if there has been changed any attribute
		$changedAttributes = array();
		foreach($this->originalAttributes AS $column=>$value)
		{
			if($newValue = $this->getAttribute($column) !== $value)
			{
				// SET datatype
				if(is_array($newValue))
				{
					$this->setAttribute($column, implode(",", $newValue));
				}
				
				$changedAttributes[$column] = $this->getAttribute($column);
			}
		}
		
		$changedAttributesCount = count($changedAttributes);
		
		if($changedAttributesCount > 0)
		{

			$sql = 'UPDATE ' . self::$db->quoteTableName($this->table) . ' SET ' . "\n";
			
			foreach($changedAttributes AS $column=>$value)
			{
				$sql .= "\t" . self::$db->quoteColumnName($column) . ' = ' . (is_null($value) ? 'NULL' : self::$db->quoteValue($value));
				
				$changedAttributesCount--;
				
				if($changedAttributesCount > 0)
					$sql .= ',' . "\n";
				
			}
			
			$sql .= "\n" . ' WHERE ' . "\n";
			
			
			$key = $this->getPrimaryKey();
			
			// If there is no PK, update with the original attributes in WHERE criteria
			if($key === null) 
			{
				$key = $this->originalAttributes;
			}
			elseif(!is_array($key))
			{
				$value = $key;
				$key = array();
				$key[$this->primaryKey()] = $value;
			}
			
			// Create find criteria
			$i = count($key);
			foreach($key AS $column=>$value) {
				
				if(is_null($value))
				{
					$sql .= "\t" . self::$db->quoteColumnName($column) . ' IS NULL ';
				}
				else
				{
					$sql .= "\t" . self::$db->quoteColumnName($column) . ' = ' . self::$db->quoteValue($this->originalAttributes[$column]) . ' ';
				}
				
				$i--;
	
				if($i > 0)
					$sql .= 'AND ' . "\n";
			}
			
			$sql .= "\n" . 'LIMIT 1';
			
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
			throw new CDbException(Yii::t('yii','The active record cannot be deleted because it is new.'));
		}
		if(!$this->beforeDelete())
		{
			return false;
		}


		if($pk = self::$db->getSchema($this->schema)->getTable($this->table)->primaryKey !== null)
			$pk = (array)$pk;
		else
			$pk = $this->safeAttributes();
			
		$pkCount = count($pk);
		
		$sql = 'DELETE FROM ' . self::$db->quoteTableName($this->table) . ' WHERE ';

		$i = 0;
		foreach($pk AS $column)
		{
			$sql .= "\n\t" . self::$db->quoteColumnName($column) . (is_null($this->getAttribute($column)) ? ' IS NULL' :  ' = ' . self::$db->quoteValue($this->getAttribute($column)));
			$i++;

			if($i < $pkCount)
				$sql .= ' AND';

		}
		
		$sql .= "\n" . 'LIMIT 1';

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

}