<?php

class Index extends ActiveRecord
{

	public $NON_UNIQUE = 1;

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
		return 'STATISTICS';
	}

	/**
	 * @see		CActiveRecord::primaryKey()
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
	 * @see		CActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
			'table' => array(self::BELONGS_TO, 'Table', 'TABLE_SCHEMA, TABLE_NAME'),
			'columns' => array(self::HAS_MANY, 'IndexColumn', 'TABLE_SCHEMA, TABLE_NAME, INDEX_NAME'),
		);
	}

	/**
	 * @see		CActiveRecord::safeAttributes()
	 */
	public function safeAttributes()
	{
		return array(
			'INDEX_NAME',
			'type',
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
			'PRIMARY' => Yii::t('database', 'primaryKey'),
			'INDEX' => Yii::t('database', 'index'),
			'UNIQUE' => Yii::t('database', 'uniqueKey'),
			'FULLTEXT' => Yii::t('database', 'fulltextIndex'),
		);
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
		// Prepare columns
		$columns = array();
		foreach($this->columns AS $column)
		{
			$columns[] = self::$db->quoteColumnName($column->COLUMN_NAME)
				. (!is_null($column->SUB_PART) ? '(' . (int)$column->SUB_PART . ')' : '');
		}
		$columns = implode(', ', $columns);

		// Create command
		$type = $this->getType();
		if($type == 'PRIMARY')
		{
			$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
				. "\t" . 'ADD PRIMARY KEY (' . $columns . ');';
		}
		else
		{
			$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
				. "\t" . 'ADD ' . $type . ' ' . self::$db->quoteColumnName($this->INDEX_NAME) . ' (' . $columns . ');';
		}

		return $sql;
	}

	/**
	 * @see		ActiveRecord::getDeleteSql()
	 */
	protected function getDeleteSql()
	{
		return 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
			. "\t" . 'DROP INDEX ' . self::$db->quoteColumnName($this->originalAttributes['INDEX_NAME']) . ';';
	}

}