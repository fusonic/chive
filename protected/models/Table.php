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


class Table extends ActiveRecord
{
	
	public $optionChecksum = '0', $originalOptionChecksum = '0';
	public $optionDelayKeyWrite = '0', $originalOptionDelayKeyWrite = '0';
	public $optionPackKeys = 'DEFAULT', $originalOptionPackKeys = 'DEFAULT';
	public $TABLE_COLLATION = 'utf8_general_ci';
	public $comment;

	private $showCreateTable;

	/**
	 * @see		ActiveRecord::model()
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see		ActiveRecord::instantiate()
	 */
	public function instantiate($attributes)
	{
		$res = parent::instantiate($attributes);

		// Check options
		if(isset($attributes['CREATE_OPTIONS']))
		{
			$options = strtolower($attributes['CREATE_OPTIONS']);
		}
		else
		{
			$options = null;
		}
		if(strpos($options, 'checksum=1') !== false)
		{
			$res->optionChecksum = $res->originalOptionChecksum = '1';
		}
		if(strpos($options, 'delay_key_write=1') !== false)
		{
			$res->optionDelayKeyWrite = $res->originalOptionDelayKeyWrite = '1';
		}
		if(strpos($options, 'pack_keys=1') !== false)
		{
			$res->optionPackKeys = $res->originalOptionPackKeys = '1';
		}
		elseif(strpos($options, 'pack_keys=0') !== false)
		{
			$res->optionPackKeys = $res->originalOptionPackKeys = '0';
		}

		// Comment
		if(isset($attributes['TABLE_COMMENT']))
		{
			if(isset($attributes['ENGINE']) && $attributes['ENGINE'] == 'InnoDB')
			{
				$search = 'InnoDB free: \d+ ..?$';
				if(preg_match('/^' . $search . '/', $attributes['TABLE_COMMENT']))
				{
					$res->comment = '';
				}
				elseif(preg_match('/; ' . $search . '/', $attributes['TABLE_COMMENT'], $result))
				{
					$res->comment = str_replace($result[0], '', $attributes['TABLE_COMMENT']);
				}
				else
				{
					$res->comment = $attributes['TABLE_COMMENT'];
				}
			}
			else
			{
				$res->comment = $attributes['TABLE_COMMENT'];
			}
		}
		$res->originalAttributes['comment'] = $res->comment;

		return $res;
	}

	/**
	 * @see		ActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'TABLES';
	}

	/**
	 * @see		ActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return array(
			'TABLE_SCHEMA',
			'TABLE_NAME',
		);
	}

	/**
	 * @see		ActiveRecord::rules()
	 */
	public function rules()
	{
		return array(
			array('TABLE_NAME', 'type', 'type' => 'string'),
			array('TABLE_COLLATION', 'type', 'type' => 'string'),
			array('ENGINE', 'type', 'type' => 'string'),
			array('comment', 'type', 'type' => 'string'),
			array('optionPackKeys', 'type', 'type' => 'string'),
			array('optionDelayKeyWrite', 'type', 'type' => 'string'),
			array('optionChecksum', 'type', 'type' => 'string'),
		);
	}

	/**
	 * @see		ActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
			'schema' => array(self::BELONGS_TO, 'Schema', 'TABLE_SCHEMA'),
			'columns' => array(self::HAS_MANY, 'Column', 'TABLE_SCHEMA, TABLE_NAME'),
			'indices' => array(self::HAS_MANY, 'Index', 'TABLE_SCHEMA, TABLE_NAME', 'alias' => 'TableIndex'),
			'foreignKeys' => array(self::HAS_MANY, 'ForeignKey', 'TABLE_SCHEMA, TABLE_NAME', 'alias' => 'TableConstraint'),
			'triggers' => array(self::HAS_MANY, 'Trigger', 'EVENT_OBJECT_SCHEMA, EVENT_OBJECT_TABLE'),
		);
	}

	/**
	 * @see		ActiveRecord::attributeLabels()
	 */
	public function attributeLabels()
	{
		return array(
			'optionPackKeys' => Yii::t('core', 'packKeys'),
			'optionDelayKeyWrite' => Yii::t('core', 'delayKeyWrite'),
			'optionChecksum' => Yii::t('core', 'checksum'),
			'TABLE_COLLATION' => Yii::t('core', 'collation'),
			'TABLE_COMMENT' => Yii::t('core', 'comment'),
			'TABLE_NAME' => Yii::t('core', 'name'),
			'ENGINE' => Yii::t('core', 'storageEngine'),
		);
	}

	/**
	 * Returns the row count.
	 *
	 * @return	int					Number of rows in the table
	 */
	public function getRowCount() {
		return (int)$this->TABLE_ROWS;
	}

	/**
	 * Returns the average row size.
	 *
	 * @return	mixed				Average row size, '-' if no rows found.
	 */
	public function getAverageRowSize() {
		if($this->getRowCount() > 0)
		{
			return $this->DATA_LENGTH / $this->getRowCount();
		}
		else
		{
			return '-';
		}
	}

	/**
	 * Returns wether the table has a primary key.
	 *
	 * @return	bool				True, if table has a primary key. False, if not.
	 */
	public function getHasPrimaryKey()
	{
		foreach($this->indices AS $index)
		{
			if($index->INDEX_NAME == 'PRIMARY')
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Returns wether this table has an auto increment column.
	 * 
	 * @return	bool				True, if table has an auto increment column. False if not.
	 */
	public function getHasAutoIncrementColumn()
	{
		foreach($this->columns as $column)
		{
			if($column->EXTRA == "auto_increment")
			{
				return true;
			}
		}
		
		return false;
	}

	/**
	 * Returns the result of SHOW CREATE TABLE.
	 *
	 * @return	string				Create table statement
	 */
	public function getShowCreateTable()
	{
		if(!$this->showCreateTable)
		{
			$cmd = self::$db->createCommand('SHOW CREATE TABLE ' . self::$db->quoteTableName($this->TABLE_SCHEMA) . '.' . self::$db->quoteTableName($this->TABLE_NAME));
			$res = $cmd->queryAll();
			$this->showCreateTable = $res[0]['Create Table'];
		}
		return $this->showCreateTable;
	}

	/**
	 * Truncate the table (delete all values)
	 *
	 * @return	string
	 */
	public function truncate()
	{
		// Create command
		$sql = 'TRUNCATE TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . ';';
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
	 * Returns the query string for all options which need to be saved.
	 *
	 * @return	string
	 */
	private function getSaveDefinition()
	{
		$sql = '';
		$comma = '';
		if($this->TABLE_NAME !== @$this->originalAttributes['TABLE_NAME'] && !$this->getIsNewRecord())
		{
			//@todo(mburtscher): Privileges are not copied automatically!!!
			$sql .= "\n\t" . 'RENAME ' . self::$db->quoteTableName($this->TABLE_NAME);
			$comma = ',';
		}
		if($this->TABLE_COLLATION !== @$this->originalAttributes['TABLE_COLLATION'])
		{
			$sql .= $comma . "\n\t" . 'CHARACTER SET ' . Collation::getCharacterSet($this->TABLE_COLLATION) . ' COLLATE ' . $this->TABLE_COLLATION;
			$comma = ',';
		}
		if($this->comment !== @$this->originalAttributes['comment'])
		{
			$sql .= $comma . "\n\t" . 'COMMENT ' . self::$db->quoteValue($this->comment);
			$comma = ',';
		}
		if($this->ENGINE !== @$this->originalAttributes['ENGINE'])
		{
			$sql .= $comma . "\n\t" . 'ENGINE ' . $this->ENGINE;
			$comma = ',';
		}
		if($this->optionChecksum !== $this->originalOptionChecksum)
		{
			$sql .= $comma . "\n\t" . 'CHECKSUM ' . $this->optionChecksum;
			$comma = ',';
		}
		if($this->optionPackKeys !== $this->originalOptionPackKeys)
		{
			$sql .= $comma . "\n\t" . 'PACK_KEYS ' . $this->optionPackKeys;
			$comma = ',';
		}
		if($this->optionDelayKeyWrite !== $this->originalOptionDelayKeyWrite)
		{
			$sql .= $comma . "\n\t" . 'DELAY_KEY_WRITE ' . $this->optionDelayKeyWrite;
		}
		return $sql;
	}

	/**
	 * @see		ActiveRecord::getDeleteSql()
	 */
	protected function getDeleteSql()
	{
		return 'DROP TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . ';';
	}

	/**
	 * @see		ActiveRecord::getUpdateSql()
	 */
	protected function getUpdateSql()
	{
		return 'ALTER TABLE ' . self::$db->quoteTableName($this->originalAttributes['TABLE_NAME']) . $this->getSaveDefinition() . ';';
	}

	/**
	 * @see		ActiveRecord::getInsertSql()
	 */
	protected function getInsertSql()
	{
		$columnDefinitions = array();
		foreach($this->columns AS $column)
		{
			$columnDefinitions[] = $column->getColumnDefinition();
		}

		return 'CREATE TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . '( ' . "\n\t"
			. implode(",\n\t", $columnDefinitions) . "\n"
			. ')'
			. str_replace("\t", '', $this->getSaveDefinition()) . ';';
	}

	/**
	 * Returns all index types which are supported by this table.
	 *
	 * @return	array				Array of all supported index types
	 */
	public function getSupportedIndexTypes()
	{
		$types = array(
			'PRIMARY' => Yii::t('core', 'primaryKey'),
			'INDEX' => Yii::t('core', 'index'),
			'UNIQUE' => Yii::t('core', 'uniqueKey'),
		);
		if($this->ENGINE != 'InnoDB')
		{
			$types['FULLTEXT'] = Yii::t('core', 'fulltextIndex');
		}
		return $types;
	}
	
	/**
	 * Returns true, because all tables are updatable.
	 * 
	 * @return	bool
	 */
	public function getIsUpdatable()
	{
		return true;
	}
	
}