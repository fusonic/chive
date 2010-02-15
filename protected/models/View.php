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
			'IS_UPDATABLE' => Yii::t('core', 'updatable'),
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