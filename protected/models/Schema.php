<?php

class Schema extends CActiveRecord
{
	public static $db;

	public $tableCount;
	public $originalSchemaName;

	public $DEFAULT_CHARACTER_SET_NAME = Collation::DEFAULT_CHARACTER_SET;
	public $DEFAULT_COLLATION_NAME = Collation::DEFAULT_COLLATION;

	/**
	 * @see		CActiveRecord::model()
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see		CActiveRecord::instantiate()
	 */
	public function instantiate($attributes)
	{
		$res = parent::instantiate($attributes);
		if(isset($attributes['SCHEMA_NAME']))
		{
			$res->originalSchemaName = $attributes['SCHEMA_NAME'];
		}
		return $res;
	}

	/**
	 * @see		CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'SCHEMATA';
	}

	/**
	 * @see		CActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return 'SCHEMA_NAME';
	}

	/**
	 * @see		CActiveRecord::safeAttributes()
	 */
	public function safeAttributes()
	{
		return array(
			'SCHEMA_NAME',
			'DEFAULT_COLLATION_NAME',
		);
	}

	/**
	 * @see		CActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
			'tables' => array(self::HAS_MANY, 'Table', 'TABLE_SCHEMA', 'condition' => '??.TABLE_TYPE IS NULL OR ??.TABLE_TYPE NOT IN (\'VIEW\')'),
			'views' => array(self::HAS_MANY, 'View', 'TABLE_SCHEMA'),
			'collation' => array(self::BELONGS_TO, 'Collation', 'DEFAULT_COLLATION_NAME'),
			'routines' => array(self::HAS_MANY, 'Routine', 'ROUTINE_SCHEMA'),
		);
	}

	/**
	 * @see		CActiveRecord::attributeLabels()
	 */
	public function attributeLabels()
	{
		return array(
			'SCHEMA_NAME' => Yii::t('core', 'name'),
			'DEFAULT_COLLATION_NAME' => Yii::t('database', 'collation'),
			'tableCount' => Yii::t('database', 'tables'),
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

		$sql = 'CREATE DATABASE ' . self::$db->quoteTableName($this->SCHEMA_NAME) . "\n"
			. "\t" . 'DEFAULT COLLATE = ' . self::$db->quoteValue($this->DEFAULT_COLLATION_NAME) . ';';
		$cmd = self::$db->createCommand($sql);
		try
		{
			$cmd->prepare();
			$cmd->execute();
			$this->afterSave();
			$this->setIsNewRecord(false);
			return $sql;
		}
		catch(CDbException $ex)
		{
			$errorInfo = $cmd->getPdoStatement()->errorInfo();
			$this->addError('SCHEMA_NAME', Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			$this->afterSave();
			return false;
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

		$sql = 'ALTER DATABASE ' . self::$db->quoteTableName($this->SCHEMA_NAME) . "\n"
			. "\t" . 'DEFAULT COLLATE = ' . self::$db->quoteValue($this->DEFAULT_COLLATION_NAME) . ';';
		$cmd = self::$db->createCommand($sql);
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
			$this->addError('SCHEMA_NAME', Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			$this->afterSave();
			return false;
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

		$sql = 'DROP DATABASE ' . self::$db->quoteTableName($this->SCHEMA_NAME) . ';';
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
}