<?php

class Table extends CActiveRecord
{
	public static $db;

	public $originalAttributes;
	public $optionChecksum = '0', $originalOptionChecksum = '0';
	public $optionDelayKeyWrite = '0', $originalOptionDelayKeyWrite = '0';
	public $optionPackKeys = 'DEFAULT', $originalOptionPackKeys = 'DEFAULT';
	public $TABLE_COLLATION = 'utf8_general_ci';
	public $comment;

	private $showCreateTable;

	/**
	 * @see		CActiveRecord::model()
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see		CActiveRecord::instantiate()
	 */
	public function instantiate($attributes)
	{
		$res = parent::instantiate($attributes);
		$res->originalAttributes = $attributes;

		// Check options
		$options = strtolower($attributes['CREATE_OPTIONS']);
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
		if($attributes['ENGINE'] == 'InnoDB')
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
		$res->originalAttributes['comment'] = $res->comment;

		return $res;
	}

	/**
	 * @see		CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'TABLES';
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
	 * @see		CActiveRecord:.safeAttributes()
	 */
	public function safeAttributes()
	{
		return array(
			'TABLE_NAME',
			'TABLE_COLLATION',
			'ENGINE',
			'comment',
			'optionPackKeys',
			'optionDelayKeyWrite',
			'optionChecksum',
		);
	}

	/**
	 * @see		CActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
			'schema' => array(self::BELONGS_TO, 'Schema', 'TABLE_SCHEMA'),
			'columns' => array(self::HAS_MANY, 'Column', 'TABLE_SCHEMA, TABLE_NAME'),
			'indices' => array(self::HAS_MANY, 'Index', 'TABLE_SCHEMA, TABLE_NAME', 'alias' => 'TableIndex'),
			'foreignKeys' => array(self::HAS_MANY, 'ForeignKey', 'TABLE_SCHEMA, TABLE_NAME', 'alias' => 'TableConstraint'),
		);
	}

	/**
	 * @see		CModel::attributeLabels()
	 */
	public function attributeLabels()
	{
		return array(
			'optionPackKeys' => Yii::t('database', 'packKeys'),
			'optionDelayKeyWrite' => Yii::t('database', 'delayKeyWrite'),
			'optionChecksum' => Yii::t('core', 'checksum'),
			'TABLE_COLLATION' => Yii::t('database', 'collation'),
			'TABLE_COMMENT' => Yii::t('core', 'comment'),
			'TABLE_NAME' => Yii::t('core', 'name'),
			'ENGINE' => Yii::t('database', 'storageEngine'),
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
	 * Returns the result of SHOW CREATE TABLE.
	 *
	 * @return	string				Create table statement
	 */
	public function getShowCreateTable()
	{
		if(!$this->showCreateTable)
		{
			$cmd = self::$db->createCommand('SHOW CREATE TABLE ' . self::$db->quoteTableName($this->TABLE_NAME));
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
	 * @see		CActiveRecord::delete()
	 */
	public function delete() {

		$sql = 'DROP TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . ';';
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
		if($this->TABLE_NAME !== $this->originalAttributes['TABLE_NAME'] && !$this->getIsNewRecord())
		{
			//@todo(mburtscher): Privileges are not copied automatically!!!
			$sql .= "\n\t" . 'RENAME ' . self::$db->quoteTableName($this->TABLE_NAME);
			$comma = ',';
		}
		if($this->TABLE_COLLATION !== $this->originalAttributes['TABLE_COLLATION'])
		{
			$sql .= $comma . "\n\t" . 'CHARACTER SET ' . Collation::getCharacterSet($this->TABLE_COLLATION) . ' COLLATE ' . $this->TABLE_COLLATION;
			$comma = ',';
		}
		if($this->comment !== $this->originalAttributes['comment'])
		{
			$sql .= $comma . "\n\t" . 'COMMENT ' . self::$db->quoteValue($this->comment);
			$comma = ',';
		}
		if($this->ENGINE !== $this->originalAttributes['ENGINE'])
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
	 * @see		CActiveRecord::update()
	 */
	public function update()
	{
		if($this->getIsNewRecord())
		{
			throw new CDbException(Yii::t('yii','The active record cannot be updated because it is new.'));
		}
		if(!$this->beforeSave())
		{
			return false;
		}

		$sql = 'ALTER TABLE ' . self::$db->quoteTableName($this->originalAttributes['TABLE_NAME']) . $this->getSaveDefinition() . ';';
		$cmd = new CDbCommand(self::$db, $sql);
		try
		{
			$cmd->prepare();
			$cmd->execute();
			$this->afterSave();
			$this->refresh();
			return $sql;
		}
		catch(CDbException $ex)
		{
			$errorInfo = $cmd->getPdoStatement()->errorInfo();
			$this->addError(null, Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			$this->afterSave();
			return false;
		}
	}

	/**
	 * @see		CActiveRecord::insert()
	 */
	public function insert(array $columns)
	{
		if(!$this->getIsNewRecord())
		{
			throw new CDbException(Yii::t('yii','The active record cannot be inserted to database because it is not new.'));
		}
		if(!$this->beforeSave())
		{
			return false;
		}

		$columnDefinitions = array();
		foreach($columns AS $column)
		{
			$columnDefinitions[] = $column->getColumnDefinition();
		}

		$sql = 'CREATE TABLE ' . self::$db->quoteTableName($this->TABLE_NAME) . '( ' . "\n\t"
			. implode(",\n\t", $columnDefinitions) . "\n"
			. ')'
			. str_replace("\t", '', $this->getSaveDefinition()) . ';';
		$cmd = new CDbCommand(self::$db, $sql);
		try
		{
			$cmd->prepare();
			$cmd->execute();
			$this->afterSave();
			$this->refresh();
			return $sql;
		}
		catch(CDbException $ex)
		{
			$errorInfo = $cmd->getPdoStatement()->errorInfo();
			$this->addError(null, Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			$this->afterSave();
			return false;
		}
	}

	/**
	 * Returns all index types which are supported by this table.
	 *
	 * @return	array				Array of all supported index types
	 */
	public function getSupportedIndexTypes()
	{
		$types = array(
			'PRIMARY' => Yii::t('database', 'primaryKey'),
			'INDEX' => Yii::t('database', 'index'),
			'UNIQUE' => Yii::t('database', 'uniqueKey'),
		);
		if($this->ENGINE != 'InnoDB')
		{
			$types['FULLTEXT'] = Yii::t('database', 'fulltextIndex');
		}
		return $types;
	}
}