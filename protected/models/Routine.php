<?php

class Routine extends CActiveRecord
{
	public static $db;

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'ROUTINES';
	}

	/**
	 * @see CActiveRecord::rules()
	 */
	public function rules()
	{
		return array(
		);
	}

	/**
	 * @see CActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
		//	'schema' => array(self::BELONGS_TO, 'Schema', 'ROUTINE_SCHEMA'),
		);
	}

	/**
	 * @see CActiveRecord::attributeLabels()
	 */
	public function attributeLabels()
	{
		return array(
		);
	}

	/**
	 * @see CActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return array(
			'ROUTINE_SCHEMA',
			'ROUTINE_NAME',
		);
	}

	/**
	 * Drop routine.
	 *
	 * @return	string
	 */
	public function delete()
	{
		$sql = 'DROP ' . strtoupper($this->ROUTINE_TYPE) . ' ' . self::$db->quoteTableName($this->ROUTINE_NAME) . ';';
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
	 * Returns the CREATE FUNCTION|PROCEDURE statement for this routine.
	 *
	 * @return	string
	 */
	public function getCreateRoutine()
	{
		$cmd = self::$db->createCommand('SHOW CREATE ' . strtoupper($this->ROUTINE_TYPE) . ' ' . self::$db->quoteTableName($this->ROUTINE_NAME));
		$res = $cmd->queryRow(false);
		return $res[2];
	}

}