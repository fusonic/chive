<?php

class ForeignKey extends CActiveRecord
{

	public static $db;

	public $onDelete, $onUpdate;

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
		return 'KEY_COLUMN_USAGE';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array();
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
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

	public function safeAttributes()
	{
		return array(
			'references',
			'onUpdate',
			'onDelete',
			'TABLE_SCHEMA',
			'TABLE_NAME',
			'COLUMN_NAME',
		);
	}

	public function getReferences()
	{
		if($this->REFERENCED_COLUMN_NAME)
		{
			return $this->REFERENCED_TABLE_SCHEMA . '.' . $this->REFERENCED_TABLE_NAME . '.' . $this->REFERENCED_COLUMN_NAME;
		}
		else
		{
			return null;
		}
	}

	public function setReferences($value)
	{
		if($value)
		{
			list($this->REFERENCED_TABLE_SCHEMA, $this->REFERENCED_TABLE_NAME, $this->REFERENCED_COLUMN_NAME) = explode('.', $value);
		}
		else
		{
			$this->REFERENCED_TABLE_SCHEMA = $this->REFERENCED_TABLE_NAME = $this->REFERENCED_COLUMN_NAME = null;
		}
	}

	public function primaryKey()
	{
		return array(
			'TABLE_SCHEMA',
			'TABLE_NAME',
			'CONSTRAINT_NAME',
		);
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

		$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
			. "\t" . 'DROP FOREIGN KEY ' . self::$db->quoteColumnName($this->CONSTRAINT_NAME) . ';';
		$cmd = self::$db->createCommand($sql);
		try
		{
			$cmd->prepare();
			$cmd->execute();
			$this->afterDelete();
			$this->setIsNewRecord(true);
			return $sql;
		}
		catch(CDbException $ex)
		{
			$errorInfo = $cmd->getPdoStatement()->errorInfo();
			$this->addError(null, Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
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

		$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
			. "\t" . 'ADD FOREIGN KEY (' . self::$db->quoteColumnName($this->COLUMN_NAME) . ')' . "\n"
			. "\t" . 'REFERENCES '	. self::$db->quoteTableName($this->REFERENCED_TABLE_SCHEMA) . '.' . self::$db->quoteTableName($this->REFERENCED_TABLE_NAME) . ' '
				.  '(' . self::$db->quoteColumnName($this->REFERENCED_COLUMN_NAME) . ')'
			. ($this->onUpdate ? "\n\t" . 'ON UPDATE ' . $this->onUpdate : '')
			. ($this->onDelete ? "\n\t" . 'ON DELETE ' . $this->onDelete : '')
			. ';';
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
			$this->addError('COLUMN_NAME', Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
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

		if($sql1 = $this->delete())
		{
			if($sql2 = $this->insert())
			{
				return $sql1 . "\n" . $sql2;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

}