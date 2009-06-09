<?php

class Index extends CActiveRecord
{
	public static $db;

	public $originalIndexName;
	public $NON_UNIQUE = 1;
	public $throwExceptions = false;

	/**
	 * @see		CActiveRecord::model()
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see		CActiveRecord::instantiate()
	 */
	public function instantiate($attributes)
	{
		$res = parent::instantiate($attributes);
		if(isset($attributes['INDEX_NAME']))
		{
			$res->originalIndexName = $attributes['INDEX_NAME'];
		}
		return $res;
	}

	/**
	 * @see		CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'STATISTICS';
	}

	/**
	 * @see		CActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return array(
			'TABLE_SCHEMA',
			'TABLE_NAME',
			'INDEX_NAME',
			'COLUMN_NAME',
		);
	}

	/**
	 * @see		CActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
			'table' => array(self::BELONGS_TO, 'Table', 'TABLE_SCHEMA, TABLE_NAME'),
			'columns' => array(self::HAS_MANY, 'IndexColumn', 'TABLE_SCHEMA, TABLE_NAME, INDEX_NAME'),
		);
	}

	/**
	 * @see		CActiveRecord::safeAttributes()
	 */
	public function safeAttributes()
	{
		return array(
			'INDEX_NAME',
			'type',
		);
	}

	/**
	 * Returns the index type.
	 *
	 * @return	string				Index type (PRIMARY/FULLTEXT/UNIQUE/INDEX)
	 */
	public function getType()
	{
		if($this->INDEX_NAME == 'PRIMARY')
		{
			return 'PRIMARY';
		}
		elseif($this->INDEX_TYPE == 'FULLTEXT')
		{
			return 'FULLTEXT';
		}
		elseif($this->NON_UNIQUE == 0)
		{
			return 'UNIQUE';
		}
		else {
			return 'INDEX';
		}
	}

	/**
	 * Sets the index type.
	 *
	 * @param	string				Index type (PRIMARY/FULLTEXT/UNIQUE/INDEX)
	 */
	public function setType($type)
	{
		$this->INDEX_TYPE = 'BTREE';
		$this->NON_UNIQUE = 1;
		switch(strtoupper($type))
		{
			case 'PRIMARY':
				$this->INDEX_NAME = 'PRIMARY';
				break;
			case 'FULLTEXT':
				$this->INDEX_TYPE = 'FULLTEXT';
				break;
			case 'UNIQUE':
				$this->NON_UNIQUE = 0;
				break;
		}
	}

	/**
	 * Returns all available index types as array.
	 *
	 * @return	array				Index types
	 */
	public static function getIndexTypes()
	{
		return array(
			'PRIMARY' => Yii::t('database', 'primaryKey'),
			'INDEX' => Yii::t('database', 'index'),
			'UNIQUE' => Yii::t('database', 'uniqueKey'),
			'FULLTEXT' => Yii::t('database', 'fulltextIndex'),
		);
	}

	/**
	 * @see		CActiveRecord::insert()
	 */
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

		// Prepare columns
		$columns = array();
		foreach($this->columns AS $column)
		{
			$columns[] = self::$db->quoteColumnName($column->COLUMN_NAME)
				. (!is_null($column->SUB_PART) ? '(' . (int)$column->SUB_PART . ')' : '');
		}
		$columns = implode(', ', $columns);

		// Create command
		$type = $this->getType();
		if($type == 'PRIMARY')
		{
			$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
				. "\t" . 'ADD PRIMARY KEY (' . $columns . ');';
		}
		else
		{
			$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
				. "\t" . 'ADD ' . $type . ' ' . self::$db->quoteColumnName($this->INDEX_NAME) . ' (' . $columns . ');';
		}
		$cmd = self::$db->createCommand($sql);

		// Execute
		try
		{
			$cmd->prepare();
			$cmd->execute();
			$this->afterSave();
			$this->setIsNewRecord(false);
			$this->refresh();
			return $sql;
		}
		catch(CDbException $ex)
		{
			if($this->throwExceptions)
			{
				throw new DbException($cmd);
			}
			else
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				$this->addError('COLUMN_NAME', Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
				$this->afterSave();
				return false;
			}
		}
	}

	/**
	 * @see		CActiveRecord::delete()
	 */
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

		$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
			. "\t" . 'DROP INDEX ' . self::$db->quoteColumnName($this->originalIndexName) . ';';
		$cmd = self::$db->createCommand($sql);
		try
		{
			$cmd->prepare();
			$cmd->execute();
			$this->afterDelete();
			$this->refresh();
			return $sql;
		}
		catch(CDbException $ex)
		{
			if($this->throwExceptions)
			{
				throw new DbException($cmd);
			}
			else
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				$this->addError('COLUMN_NAME', Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
				$this->afterSave();
				return false;
			}
		}
	}

	/**
	 * @see		CActiveRecord::update()
	 */
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

		// Prepare columns
		$columns = array();
		foreach($this->columns AS $column)
		{
			$columns[] = self::$db->quoteColumnName($column->COLUMN_NAME)
				. (!is_null($column->SUB_PART) ? '(' . (int)$column->SUB_PART . ')' : '');
		}
		$columns = implode(', ', $columns);

		// Create command
		$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
			. "\t" . 'DROP INDEX ' . self::$db->quoteColumnName($this->originalIndexName) . ',' . "\n";
		$type = $this->getType();
		if($type == 'PRIMARY')
		{
			$sql .= "\t" . 'ADD PRIMARY KEY (' . $columns . ');';
		}
		else
		{
			$sql .= "\t" . 'ADD ' . $type . ' ' . self::$db->quoteColumnName($this->INDEX_NAME) . ' (' . $columns . ');';
		}
		$cmd = self::$db->createCommand($sql);

		// Execute
		try
		{
			$cmd->prepare();
			$cmd->execute();
			$this->afterSave();
			$this->refresh();
			return $sql;
		}
		catch(CDbException $ex)
		{
			if($this->throwExceptions)
			{
				throw new DbException($cmd);
			}
			else
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				$this->addError('COLUMN_NAME', Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
				$this->afterSave();
				return false;
			}
		}
	}
}