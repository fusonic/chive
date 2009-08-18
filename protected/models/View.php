<?php

class View extends CActiveRecord
{
	public static $db;

	/**
	 * @see		CActiveRecord::model()
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see		CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'VIEWS';
	}

	/**
	 * @see		CActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return array(
			'TABLE_SCHEMA',
			'TABLE_NAME',
		);
	}
	
	/**
	 * @see		CActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
			'columns' => array(self::HAS_MANY, 'Column', 'TABLE_SCHEMA, TABLE_NAME'),
		);
	}

	/**
	 * @see		CActiveRecord::attributeLabels()
	 */
	public function attributeLabels()
	{
		return array(
			'IS_UPDATABLE' => Yii::t('database', 'updatable'),
		);
	}

	/**
	 * @see		CActiveRecord::delete()
	 */
	public function delete()
	{
		$sql = 'DROP VIEW ' . self::$db->quoteTableName($this->TABLE_NAME) . ';';
		$cmd = self::$db->createCommand($sql);

		// Execute
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

	/**
	 * Returns the CREATE VIEW statement for this view.
	 *
	 * @return	string
	 */
	public function getCreateView()
	{
		$cmd = self::$db->createCommand('SHOW CREATE VIEW ' . self::$db->quoteTableName($this->TABLE_SCHEMA) . '.' . self::$db->quoteTableName($this->TABLE_NAME));
		$res = $cmd->queryRow(false);
		return $res[1];
	}

	/**
	 * Returns the ALTER VIEW statement for this view.
	 *
	 * @return	string
	 */
	public function getAlterView()
	{
		return 'ALTER' . substr($this->getCreateView(), 6);
	}
	
	public function getIsUpdatable()
	{
		if($this->getAttribute('IS_UPDATABLE') === "YES")
		{
			return true;	
		}	
		else
		{
			return false;
		}
	}
}