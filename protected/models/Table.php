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

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see CActiveRecord::instantiate()
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
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'TABLES';
	}

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
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('TABLE_CATALOG','length','max'=>512),
			array('TABLE_SCHEMA','length','max'=>64),
			array('TABLE_NAME','length','max'=>64),
			array('TABLE_TYPE','length','max'=>64),
			array('ENGINE','length','max'=>64),
			array('ROW_FORMAT','length','max'=>10),
			array('TABLE_COLLATION','length','max'=>64),
			array('CREATE_OPTIONS','length','max'=>255),
			array('TABLE_COMMENT','length','max'=>80),
			array('VERSION, TABLE_ROWS, AVG_ROW_LENGTH, DATA_LENGTH, MAX_DATA_LENGTH, INDEX_LENGTH, DATA_FREE, AUTO_INCREMENT, CHECKSUM', 'numerical'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'schema' => array(self::BELONGS_TO, 'Schema', 'TABLE_SCHEMA'),
			'columns' => array(self::HAS_MANY, 'Column', 'TABLE_SCHEMA, TABLE_NAME'),
			'indices' => array(self::HAS_MANY, 'Index', 'TABLE_SCHEMA, TABLE_NAME', 'alias'=>'TableIndex'),
			#'constraints' => array(self::HAS_MANY, 'Constraint', 'TABLE_SCHEMA, TABLE_NAME', 'alias'=>'TableConstraint'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
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

	public function primaryKey()
	{
		return array(
			'TABLE_SCHEMA',
			'TABLE_NAME',
		);
	}

	public function getRowCount() {
		return (int)$this->TABLE_ROWS;
	}

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
	 * Drop table (delete structure and containing data)
	 *
	 * @return	string
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
			//@todo(mburtscher): Privileges are not copied!!!
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
	 * Update table
	 *
	 * @return	mixed
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
	 * Insert new table
	 *
	 * @param	array			$columns
	 * @return	bool
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