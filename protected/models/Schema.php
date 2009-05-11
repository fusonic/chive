<?php

class Schema extends CActiveRecord
{

	public static $db;

	public $originalSchemaName;

	public $tableCount;
	public $DEFAULT_CHARACTER_SET_NAME = Collation::DEFAULT_CHARACTER_SET;
	public $DEFAULT_COLLATION_NAME = COLLATION::DEFAULT_COLLATION;

	public $tables;

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

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
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'SCHEMATA';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('CATALOG_NAME', 'length', 'max'=>512),
			array('SCHEMA_NAME', 'required'),
			array('SCHEMA_NAME', 'length', 'min'=>1, 'max'=>64),
			array('DEFAULT_CHARACTER_SET_NAME', 'length', 'max'=>64),
			array('DEFAULT_COLLATION_NAME', 'required'),
			array('DEFAULT_COLLATION_NAME', 'length', 'max'=>64),
			array('SQL_PATH','length','max'=>512),
		);
	}

	/**
	 * @return array attributes that can be massively assigned
	 */
	public function safeAttributes()
	{
		return array(
			'SCHEMA_NAME',
			'DEFAULT_COLLATION_NAME',
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'table' => array(self::HAS_MANY, 'Table', 'TABLE_SCHEMA'),
			'collation' => array(self::BELONGS_TO, 'Collation', 'DEFAULT_COLLATION_NAME'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
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
	 * @return string primary key column
	 */
	public function primaryKey()
	{
		return 'SCHEMA_NAME';
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