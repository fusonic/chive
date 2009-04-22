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

	public static $types = array(

		//	Type					group		coll.	index	unique	fulltxt	size	scale	values	unsgnd	unsgndz	updtts	autoinc

		'bit'			=> array(	'numeric',	false,	true,	true,	false,	true,	false,	false,	true,	true,	false,	false),
		'tinyint'		=> array(	'numeric',	false,	true,	true,	false,	true,	false,	false,	true,	true,	false,	true),
		'bool'			=> array(	'numeric',	false,	true,	true,	false,	false,	false,	false,	true,	true,	false,	false),
		'smallint'		=> array(	'numeric',	false,	true,	true,	false,	true,	false,	false,	true,	true,	false,	true),
		'mediumint'		=> array(	'numeric',	false,	true,	true,	false,	true,	false,	false,	true,	true,	false,	true),
		'int'			=> array(	'numeric',	false,	true,	true,	false,	true,	false,	false,	true,	true,	false,	true),
		'bigint'		=> array(	'numeric',	false,	true,	true,	false,	true,	false,	false,	true,	true,	false,	true),
		'float'			=> array(	'numeric',	false,	true,	true,	false,	true,	true,	false,	true,	true,	false,	true),
		'double'		=> array(	'numeric',	false,	true,	true,	false,	true,	true,	false,	true,	true,	false,	true),
		'decimal'		=> array(	'numeric',	false,	true,	true,	false,	true,	true,	false,	true,	true,	false,	true),

		'char'			=> array(	'string',	true,	true,	true,	false,	true,	false,	false,	false,	false,	false,	false),
		'varchar'		=> array(	'string',	true,	true,	true,	false,	true,	false,	false,	false,	false,	false,	false),
		'tinytext'		=> array(	'string',	true,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false),
		'text'			=> array(	'string',	true,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false),
		'mediumtext'	=> array(	'string',	true,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false),
		'longtext'		=> array(	'string',	true,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false),
		'tinyblob'		=> array(	'string',	false,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false),
		'blob'			=> array(	'string',	false,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false),
		'mediumblob'	=> array(	'string',	false,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false),
		'longblob'		=> array(	'string',	false,	false,	false,	true,	false,	false,	false,	false,	false,	false,	false),
		'binary'		=> array(	'string',	false,	true,	true,	false,	true,	false,	false,	false,	false,	false,	false),
		'varbinary'		=> array(	'string',	false,	true,	true,	false,	true,	false,	false,	false,	false,	false,	false),
		'enum'			=> array(	'string',	true,	true,	true,	false,	false,	false,	true,	false,	false,	false,	false),
		'set'			=> array(	'string',	true,	true,	true,	false,	false,	false,	true,	false,	false,	false,	false),

		'date'			=> array(	'date',		false,	true,	true,	false,	false,	false,	false,	false,	false,	false,	false),
		'datetime'		=> array(	'date',		false,	true,	true,	false,	false,	false,	false,	false,	false,	false,	false),
		'timestamp'		=> array(	'date',		false,	true,	true,	false,	false,	false,	false,	false,	false,	false,	false),
		'time'			=> array(	'date',		false,	true,	true,	false,	false,	false,	false,	false,	false,	false,	false),
		'year'			=> array(	'date',		false,	true,	true,	true,	false,	false,	false,	false,	false,	false,	false),

	);

	public static function check($dataType, $property)
	{
		return self::$types[self::getBaseType($dataType)][$property];
	}

	public static function getBaseType($dataType)
	{
		preg_match('/^\w+/', $dataType, $res);
		return $res[0];
	}

}

?>