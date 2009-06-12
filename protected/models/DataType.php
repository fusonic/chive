<?php

class DataType
{

	const GROUP = 0;
	const SUPPORTS_COLLATION = 1;
	const SUPPORTS_INDEX = 2;
	const SUPPORTS_UNIQUE = 3;
	const SUPPORTS_FULLTEXT = 4;
	const SUPPORTS_SIZE = 5;
	const SUPPORTS_SCALE = 6;
	const SUPPORTS_VALUES = 7;
	const SUPPORTS_UNSIGNED = 8;
	const SUPPORTS_UNSIGNED_ZEROFILL = 9;
	const SUPPORTS_ON_UPDATE_CURRENT_TIMESTAMP = 10;
	const SUPPORTS_AUTO_INCREMENT = 11;
	const INPUT_TYPE = 12;

	public static $types = array(

		//	Type					group		coll.	index	unique	fulltxt	size	scale	values	unsgnd	unsgndz	updtts	autoinc	input

		'bit'			=> array(	'numeric',	false,	true,	true,	false,	true,	false,	false,	true,	true,	false,	false,	'number'),
		'tinyint'		=> array(	'numeric',	false,	true,	true,	false,	true,	false,	false,	true,	true,	false,	true,	'number'),
		'bool'			=> array(	'numeric',	false,	true,	true,	false,	false,	false,	false,	true,	true,	false,	false,	'checkbox'),
		'smallint'		=> array(	'numeric',	false,	true,	true,	false,	true,	false,	false,	true,	true,	false,	true,	'number'),
		'mediumint'		=> array(	'numeric',	false,	true,	true,	false,	true,	false,	false,	true,	true,	false,	true,	'number'),
		'int'			=> array(	'numeric',	false,	true,	true,	false,	true,	false,	false,	true,	true,	false,	true,	'number'),
		'bigint'		=> array(	'numeric',	false,	true,	true,	false,	true,	false,	false,	true,	true,	false,	true, 	'number'),
		'float'			=> array(	'numeric',	false,	true,	true,	false,	true,	true,	false,	true,	true,	false,	true,	'number'),
		'double'		=> array(	'numeric',	false,	true,	true,	false,	true,	true,	false,	true,	true,	false,	true,	'number'),
		'decimal'		=> array(	'numeric',	false,	true,	true,	false,	true,	true,	false,	true,	true,	false,	true,	'number'),

		'char'			=> array(	'string',	true,	true,	true,	true,	true,	false,	false,	false,	false,	false,	false,	'single'),
		'varchar'		=> array(	'string',	true,	true,	true,	true,	true,	false,	false,	false,	false,	false,	false,	'single'),
		'tinytext'		=> array(	'string',	true,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false,	'text'),
		'text'			=> array(	'string',	true,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false,	'text'),
		'mediumtext'	=> array(	'string',	true,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false,	'text'),
		'longtext'		=> array(	'string',	true,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false,	'text'),
		'tinyblob'		=> array(	'string',	false,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false,	'file'),
		'blob'			=> array(	'string',	false,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false,	'file'),
		'mediumblob'	=> array(	'string',	false,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false,	'file'),
		'longblob'		=> array(	'string',	false,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false,	'file'),
		'binary'		=> array(	'string',	false,	true,	true,	false,	true,	false,	false,	false,	false,	false,	false,	'single'),
		'varbinary'		=> array(	'string',	false,	true,	true,	false,	true,	false,	false,	false,	false,	false,	false,	'single'),
		'enum'			=> array(	'string',	true,	true,	true,	false,	false,	false,	true,	false,	false,	false,	false,	'select'),
		'set'			=> array(	'string',	true,	true,	true,	false,	false,	false,	true,	false,	false,	false,	false,	'select-multiple'),

		'date'			=> array(	'date',		false,	true,	true,	false,	false,	false,	false,	false,	false,	false,	false,	'date'),
		'datetime'		=> array(	'date',		false,	true,	true,	false,	false,	false,	false,	false,	false,	false,	false,	'datetime'),
		'timestamp'		=> array(	'date',		false,	true,	true,	false,	false,	false,	false,	false,	false,	false,	false,	'single'),
		'time'			=> array(	'date',		false,	true,	true,	false,	false,	false,	false,	false,	false,	false,	false,	'single'),
		'year'			=> array(	'date',		false,	true,	true,	true,	false,	false,	false,	false,	false,	false,	false,	'number'),

	);

	public static function check($dataType, $property)
	{
		return self::$types[self::getBaseType($dataType)][$property];
	}

	public static function getBaseType($dataType)
	{
		preg_match('/^\w+/', $dataType, $res);
		return strtolower($res[0]);
	}
	
	public static function getInputType($dataType) {
		return self::$types[self::getBaseType($dataType)][self::INPUT_TYPE];
	}

}

?>