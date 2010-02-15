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


class Index extends ActiveRecord
{

	public $NON_UNIQUE = 1;

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
		return 'STATISTICS';
	}

	/**
	 * @see		ActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return array(
			'TABLE_SCHEMA',
			'TABLE_NAME',
			'INDEX_NAME',
			'COLUMN_NAME',
		);
	}

	/**
	 * @see		ActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
			'table' => array(self::BELONGS_TO, 'Table', 'TABLE_SCHEMA, TABLE_NAME'),
			'columns' => array(self::HAS_MANY, 'IndexColumn', 'TABLE_SCHEMA, TABLE_NAME, INDEX_NAME'),
		);
	}

	/**
	 * @see		ActiveRecord::rules()
	 */
	public function rules()
	{
		return array(
			array('INDEX_NAME', 'type', 'type' => 'string'),
			array('type', 'type', 'type' => 'string'),
		);
	}

	/**
	 * Returns the index type.
	 *
	 * @return	string				Index type (PRIMARY/FULLTEXT/UNIQUE/INDEX)
	 */
	public function getType()
	{
		if($this->INDEX_NAME == 'PRIMARY')
		{
			return 'PRIMARY';
		}
		elseif($this->INDEX_TYPE == 'FULLTEXT')
		{
			return 'FULLTEXT';
		}
		elseif($this->NON_UNIQUE == 0)
		{
			return 'UNIQUE';
		}
		else {
			return 'INDEX';
		}
	}

	/**
	 * Sets the index type.
	 *
	 * @param	string				Index type (PRIMARY/FULLTEXT/UNIQUE/INDEX)
	 */
	public function setType($type)
	{
		$this->INDEX_TYPE = 'BTREE';
		$this->NON_UNIQUE = 1;
		switch(strtoupper($type))
		{
			case 'PRIMARY':
				$this->INDEX_NAME = 'PRIMARY';
				break;
			case 'FULLTEXT':
				$this->INDEX_TYPE = 'FULLTEXT';
				break;
			case 'UNIQUE':
				$this->NON_UNIQUE = 0;
				break;
		}
	}

	/**
	 * Returns all available index types as array.
	 *
	 * @return	array				Index types
	 */
	public static function getIndexTypes()
	{
		return array(
			'PRIMARY' => Yii::t('core', 'primaryKey'),
			'INDEX' => Yii::t('core', 'index'),
			'UNIQUE' => Yii::t('core', 'uniqueKey'),
			'FULLTEXT' => Yii::t('core', 'fulltextIndex'),
		);
	}

	/**
	 * @see		ActiveRecord::getUpdateSql()
	 */
	protected function getUpdateSql()
	{
		return 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
			. $this->getDeleteSql(true) . ',' . "\n"
			. $this->getInsertSql(true) . ';';
	}

	/**
	 * @see		ActiveRecord::getInsertSql()
	 */
	protected function getInsertSql($skipAlter = false)
	{
		// Prepare columns
		$columns = array();
		foreach($this->columns AS $column)
		{
			$columns[] = self::$db->quoteColumnName($column->COLUMN_NAME)
				. (!is_null($column->SUB_PART) ? '(' . (int)$column->SUB_PART . ')' : '');
		}
		$columns = implode(', ', $columns);

		// Create command
		if($skipAlter)
		{
			$sql = '';
		}
		else
		{
			$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"; 
		}
		$type = $this->getType();
		if($type == 'PRIMARY')
		{
			$sql .= "\t" . 'ADD PRIMARY KEY (' . $columns . ')';
		}
		else
		{
			$sql .= "\t" . 'ADD ' . $type . ' ' . self::$db->quoteColumnName($this->INDEX_NAME) . ' (' . $columns . ')';
		}
		if(!$skipAlter)
		{
			$sql .= ';';
		}

		return $sql;
	}

	/**
	 * @see		ActiveRecord::getDeleteSql()
	 */
	protected function getDeleteSql($skipAlter = false)
	{
		if($skipAlter)
		{
			$sql = '';
		}
		else
		{
			$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n";
		}
		$sql .= "\t" . 'DROP INDEX ' . self::$db->quoteColumnName($this->originalAttributes['INDEX_NAME']);
		if(!$skipAlter)
		{
			$sql .= ';';
		}
		return $sql;
	}

}