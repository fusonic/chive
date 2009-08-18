<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
 * 
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */


class ForeignKey extends ActiveRecord
{

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
	 * @see		ActiveRecord::getUpdateSql()
	 */
	protected function getUpdateSql()
	{
		return array(
			$this->getDeleteSql(),
			$this->getInsertSql(),
		);
	}

	/**
	 * @see		ActiveRecord::getInsertSql()
	 */
	protected function getInsertSql()
	{
		return 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
			. "\t" . 'ADD FOREIGN KEY (' . self::$db->quoteColumnName($this->COLUMN_NAME) . ')' . "\n"
			. "\t" . 'REFERENCES '	. self::$db->quoteTableName($this->REFERENCED_TABLE_SCHEMA) . '.' . self::$db->quoteTableName($this->REFERENCED_TABLE_NAME) . ' '
			.  '(' . self::$db->quoteColumnName($this->REFERENCED_COLUMN_NAME) . ')'
			. ($this->onDelete ? "\n\t" . 'ON DELETE ' . $this->onDelete : '')
			. ($this->onUpdate ? "\n\t" . 'ON UPDATE ' . $this->onUpdate : '')
			. ';';
	}

	/**
	 * @see		ActiveRecord::getDeleteSql()
	 */
	protected function getDeleteSql()
	{
		return 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
			. "\t" . 'DROP FOREIGN KEY ' . self::$db->quoteColumnName($this->CONSTRAINT_NAME) . ';';
	}

}