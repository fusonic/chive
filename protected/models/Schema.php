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


class Schema extends ActiveRecord
{

	public $tableCount;
	public $DEFAULT_CHARACTER_SET_NAME = Collation::DEFAULT_CHARACTER_SET;
	public $DEFAULT_COLLATION_NAME = Collation::DEFAULT_COLLATION;

	/**
	 * @see		ActiveRecord::model()
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see		ActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'SCHEMATA';
	}

	/**
	 * @see		ActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return 'SCHEMA_NAME';
	}

	/**
	 * @see		ActiveRecord::rules()
	 */
	public function rules()
	{
		return array(
			array('SCHEMA_NAME', 'type', 'type' => 'string'),
			array('DEFAULT_COLLATION_NAME', 'type', 'type' => 'string'),
		);
	}

	/**
	 * @see		ActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
			'tables' => array(self::HAS_MANY, 'Table', 'TABLE_SCHEMA', 'condition' => 'tables.TABLE_TYPE IS NULL OR tables.TABLE_TYPE NOT IN (\'VIEW\')'),
			'views' => array(self::HAS_MANY, 'View', 'TABLE_SCHEMA'),
			'collation' => array(self::BELONGS_TO, 'Collation', 'DEFAULT_COLLATION_NAME'),
			'routines' => array(self::HAS_MANY, 'Routine', 'ROUTINE_SCHEMA'),
		);
	}

	/**
	 * @see		ActiveRecord::attributeLabels()
	 */
	public function attributeLabels()
	{
		return array(
			'SCHEMA_NAME' => Yii::t('core', 'name'),
			'DEFAULT_COLLATION_NAME' => Yii::t('core', 'collation'),
			'tableCount' => Yii::t('core', 'tables'),
		);
	}

	/**
	 * @see		ActiveRecord::getUpdateSql()
	 */
	protected function getUpdateSql()
	{
		return 'ALTER DATABASE ' . self::$db->quoteTableName($this->SCHEMA_NAME) . "\n"
			. "\t" . 'DEFAULT COLLATE = ' . self::$db->quoteValue($this->DEFAULT_COLLATION_NAME) . ';';
	}

	/**
	 * @see		ActiveRecord::getInsertSql()
	 */
	protected function getInsertSql()
	{
		return 'CREATE DATABASE ' . self::$db->quoteTableName($this->SCHEMA_NAME) . "\n"
			. "\t" . 'DEFAULT COLLATE = ' . self::$db->quoteValue($this->DEFAULT_COLLATION_NAME) . ';';
	}

	/**
	 * @see		ActiveRecord::getDeleteSql()
	 */
	protected function getDeleteSql()
	{
		return 'DROP DATABASE ' . self::$db->quoteTableName($this->SCHEMA_NAME) . ';';
	}

}