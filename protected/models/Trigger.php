<?php

class Trigger extends CActiveRecord
{
	public static $db;

	/**
	 * @see		CModel::model()
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see		CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'TRIGGERS';
	}

	/**
	 * @see		CActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return array(
			'TRIGGER_SCHEMA',
			'TRIGGER_NAME',
		);
	}

	/**
	 * Returns the CREATE TRIGGER statement for this trigger.
	 *
	 * @return	string
	 */
	public function getCreateTrigger()
	{
		return 'CREATE TRIGGER ' . self::$db->quoteTableName($this->TRIGGER_NAME) . "\n"
			. $this->ACTION_TIMING . ' ' . $this->EVENT_MANIPULATION . "\n"
			. 'ON ' . self::$db->quoteTableName($this->EVENT_OBJECT_TABLE) . ' FOR EACH ROW' . "\n"
			. $this->ACTION_STATEMENT;
	}

}