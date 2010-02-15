<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2010 Fusonic GmbH
 *
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */


class Trigger extends CActiveRecord
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
	 * @see		CActiveRecord::delete()
	 */
	public function delete()
	{
		$sql = 'DROP TRIGGER ' . self::$db->quoteTableName($this->TRIGGER_NAME) . ';';
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
	 * Returns the CREATE TRIGGER statement for this trigger.
	 *
	 * @return	string
	 */
	public function getCreateTrigger()
	{
		return 'CREATE TRIGGER ' . self::$db->quoteTableName($this->TRIGGER_SCHEMA) . '.' . self::$db->quoteTableName($this->TRIGGER_NAME) . "\n"
			. $this->ACTION_TIMING . ' ' . $this->EVENT_MANIPULATION . "\n"
			. 'ON ' . self::$db->quoteTableName($this->EVENT_OBJECT_TABLE) . ' FOR EACH ROW' . "\n"
			. $this->ACTION_STATEMENT;
	}

}