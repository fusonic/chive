<?php

class InputField extends CWidget
{

	public $column;
	public $row;

	private $types = array(

		// Short text fields
		'CHAR' => 'shorttext',
		'VARCHAR' => 'shorttext',
		'BIT' => 'shorttext',
		'BOOL' => 'shorttext',

		// Numeric types
		'TINYINT' => 'int',
		'SMALLINT' => 'int',
		'MEDIUMINT' => 'int',
		'BIGINT' => 'int',
		'TIMESTAMP' => 'int',
		'YEAR' => 'int',
		'INT' => 'int',

		// Text
		'TINYTEXT' => 'text',
		'MEDIUMTEXT' => 'text',
		'TEXT' => 'text',
		'LONGTEXT' => 'text',

		// Date
		'DATE' => 'date',
		'DATETIME' => 'datetime',
		'TIME' => 'time',

		// Floats
		'FLOAT' => 'float',
		'DOUBLE' => 'float',
		'DECIMAL' => 'float',

		// Blog fields
		'TINYBLOB' => 'blob',
		'BLOB' => 'blob',
		'MEDIUMBLOB' => 'blob',
		'LONGBLOB' => 'blob',

		// Enums
		'ENUM' => 'enum',
		'SET' => 'enum',

		// @todo (rponudic) What to do with these?
		'BINARY',
		'VARBINARY',
	);



	public function run()
	{
		$data = array();

		$this->render('inputField', array(
			'column'=>$this->column,
			'row'=>$this->row,
		));

	}

	public function getType()
	{
		preg_match('/[a-z]+/i', $this->column->dbType, $res);
		$type = $res[0];

		return $this->types[strtoupper($type)];
	}

	public function getEnumValues()
	{
		$type = preg_match('/\(\'(.+)\'\)/i', $this->column->dbType, $res);
		$values = explode('\',\'', $res[1]);

		return $values;
	}


}