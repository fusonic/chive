<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
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


class Column extends ActiveRecord
{

	public $COLLATION_NAME = Collation::DEFAULT_COLLATION;
	public $scale, $size;
	public $_values = array();
	public $attribute = '';
	public $createPrimaryKey, $createUniqueKey;
	public $DATA_TYPE = 'varchar';

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

		/*
		 * We have to set some properties by hand
		 */
		if(isset($attributes['COLUMN_TYPE']))
		{
			// Size / scale
			if(DataType::check($attributes['COLUMN_TYPE'], DataType::SUPPORTS_SIZE))
			{
				if(preg_match('/^\w+\((\d+)(,\d+)?\)/', $attributes['COLUMN_TYPE'], $result))
				{
					$res->size = (int)$result[1];
					if(isset($result[2]) && DataType::check($attributes['COLUMN_TYPE'], DataType::SUPPORTS_SCALE))
					{
						$res->scale = (int)substr($result[2], 1);
					}
				}
			}

			// Values
			elseif(DataType::check($attributes['COLUMN_TYPE'], DataType::SUPPORTS_VALUES))
			{
				if(preg_match('/^\w+\(\'([^\)]+)\'\)/', $attributes['COLUMN_TYPE'], $result))
				{
					$res->setValues(implode("\n", (array)explode("','", $result[1])));
				}
			}

			// Unsigned
			if(preg_match('/ unsigned$/', $attributes['COLUMN_TYPE']))
			{
				$res->attribute = 'unsigned';
			}

			// Unsigned zerofill
			elseif(preg_match('/ unsigned zerofill$/', $attributes['COLUMN_TYPE']))
			{
				$res->attribute = 'unsigned zerofill';
			}

			// On update current_timestamp
			elseif($attributes['COLUMN_TYPE'] == 'timestamp')
			{
				$table = Table::model()->findByPk(array(
					'TABLE_SCHEMA' => $attributes['TABLE_SCHEMA'],
					'TABLE_NAME' => $attributes['TABLE_NAME'],
				));
				$match = '/^\s+`' . $attributes['COLUMN_NAME'] . '` timestamp.+?on update CURRENT_TIMESTAMP.+?$/im';
				if(preg_match($match, $table->getShowCreateTable(), $result))
				{
					$res->attribute = 'on update current_timestamp';
				}
			}
		}

		return $res;
	}

	/**
	 * @see		CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'COLUMNS';
	}

	public function safeAttributes()
	{
		return array(
			'COLUMN_NAME',
			'COLUMN_DEFAULT',
			'isNullable',
			'dataType',
			'size',
			'scale',
			'values',
			'collation',
			'autoIncrement',
			'attribute',
			'COLUMN_COMMENT',
			'COLLATION_NAME',
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'table' => array(self::BELONGS_TO, 'Table', 'TABLE_SCHEMA, TABLE_NAME'),
			'indices' => array(self::HAS_MANY, 'Index', 'TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME'),
			'collation' => array(self::BELONGS_TO, 'Collation', 'COLLATION_NAME'),
			#'constraint' => array(self::MANY_MANY, 'Constraint', 'COLUMN_NAME'),
		);
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'COLUMN_NAME' => Yii::t('core', 'name'),
			'COLLATION_NAME' => Yii::t('core', 'collation'),
			'COLUMN_COMMENT' => Yii::t('core', 'comment'),
			'size' => Yii::t('core', 'size'),
		);
	}

	public function primaryKey() {
		return array(
			'TABLE_SCHEMA',
			'TABLE_NAME',
			'COLUMN_NAME',
		);
	}

	public function getAutoIncrement()
	{
		return $this->EXTRA == 'auto_increment';
	}

	public function setAutoIncrement($value)
	{
		$this->EXTRA = ($value ? 'auto_increment' : null);
	}

	public function getIsNullable()
	{
		return $this->IS_NULLABLE == 'YES';
	}

	public function setIsNullable($value)
	{
		$this->IS_NULLABLE = ($value ? 'YES' : 'NO');
	}

	public function getCollation()
	{
		return $this->COLLATION_NAME;
	}

	public function setCollation($value)
	{
		$this->COLLATION_NAME = $value;
		$data = explode('_', $value);
		$this->CHARACTER_SET_NAME = $data[0];
	}

	public function getDataType()
	{
		return DataType::getBaseType($this->DATA_TYPE);
	}

	public function setDataType($value)
	{
		$this->DATA_TYPE = $value;
		$this->COLUMN_TYPE = $value . ($this->size ? '(' . $this->size . ($this->scale ? ',' . $this->scale : '') . ')' : '');
	}

	public function getColumnType()
	{
		$return = $this->DATA_TYPE;
		if(DataType::check($this->DATA_TYPE, DataType::SUPPORTS_SIZE))
		{
			$return .= '(' . (int)$this->size;
			if(DataType::check($this->DATA_TYPE, DataType::SUPPORTS_SCALE))
			{
				$return .= ', ' . (int)$this->scale;
			}
			$return .= ')';
		}
		elseif(DataType::check($this->DATA_TYPE, DataType::SUPPORTS_VALUES) && count((array)$this->_values) > 0)
		{
			$return .= '(\'' . implode('\',\'', $this->_values) . '\')';
		}
		return $return;
	}

	public function getValues()
	{
		return implode("\n", $this->_values);
	}

	public function setValues($values)
	{
		if(is_array($values))
		{
			$this->_values = $values;
		}
		else
		{
			$this->_values = (array)explode("\n", $values);
		}
	}

	public function getIsPartOfPrimaryKey($indices = null)
	{
		$res = false;
		if(is_null($indices))
		{
			$indices = $this->indices;
		}
		foreach($indices AS $index)
		{
			if($index->INDEX_NAME == 'PRIMARY' && $index->COLUMN_NAME == $this->COLUMN_NAME)
			{
				$res = true;
				break;
			}
		}
		return $res;
	}

	public function getColumnDefinition()
	{
		if(DataType::check($this->DATA_TYPE, DataType::SUPPORTS_COLLATION))
		{
			$collate = ' CHARACTER SET ' . Collation::getCharacterSet($this->COLLATION_NAME) . ' COLLATE ' . $this->COLLATION_NAME;
		}
		else
		{
			$collate = '';
		}

		if($this->attribute)
		{
			if(($this->attribute == 'unsigned' && !DataType::check($this->DATA_TYPE, DataType::SUPPORTS_UNSIGNED))
				|| $this->attribute == 'unsigned_zerofill' && !DataType::check($this->DATA_TYPE, DataType::SUPPORTS_UNSIGNED_ZEROFILL)
				|| $this->attribute == 'on update current_timestamp' && !DataType::check($this->DATA_TYPE, DataType::SUPPORTS_ON_UPDATE_CURRENT_TIMESTAMP))
			{
				$attribute = '';
			}
			else
			{
				$attribute = ' ' . $this->attribute;
			}
		}
		else
		{
			$attribute = '';
		}

		if(strlen($this->COLUMN_DEFAULT) > 0 && $this->EXTRA != 'auto_increment')
		{
			$default = ' DEFAULT ' . self::$db->quoteValue($this->COLUMN_DEFAULT);
		}
		else if($this->getIsNullable() && $this->EXTRA != 'auto_increment')
		{
			$default = ' DEFAULT NULL';
		}
		else
		{
			$default = '';
		}

		return trim(
			self::$db->quoteColumnName($this->COLUMN_NAME)
			. ' ' . $this->getColumnType() . $attribute . $collate
			. ($this->getIsNullable() ? ' NULL' : ' NOT NULL')
			. $default
			. ($this->EXTRA == 'auto_increment' ? ' AUTO_INCREMENT' : '')
			. ($this->createPrimaryKey ? ' PRIMARY KEY' : '')
			. ($this->createUniqueKey ? ' UNIQUE KEY' : '')
			. (strlen($this->COLUMN_COMMENT) ? ' COMMENT ' . self::$db->quoteValue($this->COLUMN_COMMENT) : '')
		);
	}

	public function move($command)
	{
		$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
			. "\t" . 'MODIFY ' . $this->getColumnDefinition()
			. ' ' . (substr($command, 0, 6) == 'AFTER ' ? 'AFTER ' . self::$db->quoteColumnName(substr($command, 6)) : 'FIRST') . ';';
		$cmd = new CDbCommand(self::$db, $sql);
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
	 * @see		ActiveRecord::getUpdateSql()
	 */
	protected function getUpdateSql()
	{
		$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n";
		if($this->originalAttributes['COLUMN_NAME'] == $this->COLUMN_NAME)
		{
			$sql .= "\t" . 'MODIFY ' . $this->getColumnDefinition() . ';';
		}
		else
		{
			$sql .= "\t" . 'CHANGE ' . self::$db->quoteColumnName($this->originalAttributes['COLUMN_NAME']) . ' ' . $this->getColumnDefinition() . ';';
		}
		return $sql;
	}

	/**
	 * @see		ActiveRecord::getInsertSql()
	 */
	protected function getInsertSql()
	{
		return 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
			. "\t" . 'ADD ' . $this->getColumnDefinition() . ';';
	}

	/**
	 * @see		ActiveRecord::getDeleteSql()
	 */
	protected function getDeleteSql()
	{
		return 'ALTER TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . "\n"
			. "\t" . 'DROP ' . self::$db->quoteColumnName($this->COLUMN_NAME) . ';';
	}

	public static function getDataTypes()
	{

		$types = array();

		// Numeric
		$types[Yii::t('core', 'numeric')] =  array(
			'bit' => 'bit',
			'tinyint' => 'tinyint',
			'bool' => 'bool',
			'smallint' => 'smallint',
			'mediumint' => 'mediumint',
			'int' => 'int',
			'bigint' => 'bigint',
			'float' => 'float',
			'double' => 'double',
			'decimal' => 'decimal',
		);

		// Strings
		$types[Yii::t('core', 'strings')] = array(
			'char' => 'char',
			'varchar' => 'varchar',
			'tinytext' => 'tinytext',
			'text' => 'text',
			'mediumtext' => 'mediumtext',
			'longtext' => 'longtext',
			'tinyblob' => 'tinyblob',
			'blob' => 'blob',
			'mediumblob' => 'mediumblob',
			'longblob' => 'longblob',
			'binary' => 'binary',
			'varbinary' => 'varbinary',
			'enum' => 'enum',
			'set' => 'set',
		);

		// Date and time
		$types[Yii::t('core', 'dateAndTime')] = array(
			'date' => 'date',
			'datetime' => 'datetime',
			'timestamp' => 'timestamp',
			'time' => 'time',
			'year' => 'year',
		);

		return $types;

	}

}