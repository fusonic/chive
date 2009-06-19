<?php

class ForeignKey extends CActiveRecord
{
	public static $db;

	public $onDelete, $onUpdate, $table;

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

		$res->table = Table::model()->findByAttributes(array(
			'TABLE_SCHEMA' => $attributes['TABLE_SCHEMA'],
			'TABLE_NAME' => $attributes['TABLE_NAME'],
		));

		$match = '/^\s+constraint `' . $attributes['CONSTRAINT_NAME'] . '` .+?$/im';
		if(preg_match($match, $res->table->getShowCreateTable(), $result))
		{
			if(preg_match('/on delete (CASCADE|NO ACTION|SET NULL|RESTRICT)/i', $result[0], $result2))
			{
				$res->onDelete = $result2[1];
			}
			if(preg_match('/on update (CASCADE|NO ACTION|SET NULL|RESTRICT)/i', $result[0], $result2))
			{
				$res->onUpdate = $result2[1];
			}
		}

		return $res;
	}

	/**
	 * @see		CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'KEY_COLUMN_USAGE';
	}

	/**
	 * @see		CActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return array(
			'TABLE_SCHEMA',
			'TABLE_NAME',
			'CONSTRAINT_NAME',
		);
	}

	/**
	 * @see		CActiveRecord::safeAttributes()
	 */
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

	/**
	 * Gets the references string.
	 *
	 * @return	string
	 */
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

	/**
	 * Sets the references attributes from a string.
	 *
	 * @param	string				References string (e.g. schema.table.column)
	 */
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

		$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
		. "\t" . 'ADD FOREIGN KEY (' . self::$db->quoteColumnName($this->COLUMN_NAME) . ')' . "\n"
		. "\t" . 'REFERENCES '	. self::$db->quoteTableName($this->REFERENCED_TABLE_SCHEMA) . '.' . self::$db->quoteTableName($this->REFERENCED_TABLE_NAME) . ' '
		.  '(' . self::$db->quoteColumnName($this->REFERENCED_COLUMN_NAME) . ')'
		. ($this->onDelete ? "\n\t" . 'ON DELETE ' . $this->onDelete : '')
		. ($this->onUpdate ? "\n\t" . 'ON UPDATE ' . $this->onUpdate : '')
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