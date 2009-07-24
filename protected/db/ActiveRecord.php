<?php

/**
 * Does the whole insert/update/delete procedure by using sql
 * statements given by the derived class.
 * Saves original attributes automatically.
 */
abstract class ActiveRecord extends CActiveRecord
{

	public static $db;

	public $throwExceptions = false;
	public $originalAttributes = array();

	/**
	 * @see		CActiveRecord::instantiate()
	 */
	public function instantiate($attributes)
	{
		$res = parent::instantiate($attributes);

		// Set original attributes
		$res->originalAttributes = $attributes;

		return $res;
	}

	/**
	 * Returns the sql statement(s) needed to update the record.
	 *
	 * @return	mixed					sql satement(s)
	 */
	protected abstract function getUpdateSql();

	/**
	 * Returns the sql statement(s) needed to insert the record.
	 *
	 * @return	mixed					sql satement(s)
	 */
	protected abstract function getInsertSql();

	/**
	 * Returns the sql statement(s) needed to delete the record.
	 *
	 * @return	mixed					sql satement(s)
	 */
	protected abstract function getDeleteSql();

	/**
	 * Executes the given sql statement(s).
	 *
	 * @param	mixed					sql statement(s)
	 * @return	mixed					sql statement(s) (imploded) or false
	 */
	private function executeSql($sql)
	{
		try
		{
			$sql = (array)$sql;
			foreach($sql AS $sql1)
			{
				$cmd = new CDbCommand(self::$db, $sql1);
				$cmd->prepare();
				$cmd->execute();
				$this->afterSave();
				$this->refresh();
			}
			return implode("\n", $sql);
		}
		catch(CDbException $ex)
		{
			$this->afterSave();
			if($this->throwExceptions)
			{
				throw new DbException($cmd);
			}
			else
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				$this->addError(null, Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
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

		return $this->executeSql($this->getUpdateSql());
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

		return $this->executeSql($this->getInsertSql());
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

		return $this->executeSql($this->getDeleteSql());
	}

}