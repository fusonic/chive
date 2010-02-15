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


class Routine extends CActiveRecord
{
	public static $db;

	/**
	 * @see		CActiveRecord::model()
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
		return 'ROUTINES';
	}

	/**
	 * @see		CActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return array(
			'ROUTINE_SCHEMA',
			'ROUTINE_NAME',
		);
	}

	/**
	 * @see		CActiveRecord::delete()
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
		$cmd = self::$db->createCommand('SHOW CREATE ' . strtoupper($this->ROUTINE_TYPE) . ' ' . self::$db->quoteTableName($this->ROUTINE_SCHEMA) . '.' . self::$db->quoteTableName($this->ROUTINE_NAME));
		$res = $cmd->queryRow(false);
		return $res[2];
	}
	
}