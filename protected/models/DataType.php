<?php

class DataType
{

	public static $supportsCollation = array(
		'char',
		'varchar',
		'smalltext',
		'text',
		'mediumtext',
		'longtext',
		'enum',
		'set',
	);
	public static $supportsFulltext = array(
		'char',
		'varchar',
		'smalltext',
		'text',
		'mediumtext',
		'longtext',
	);
	public static $supportsSize = array(
		'char',
		'varchar',
		'binary',
		'varbinary',
		'blob',
		'text',
		'bit',
		'tinyint',
		'smallint',
		'mediumint',
		'int',
		'bigint',
		'float',
		'double',
		'decimal',
		'year',
	);
	public static $supportsScale = array(
		'float',
		'double',
		'decimal',
	);

	public static $supportsValues = array(
		'enum',
		'set',
	);

	public static function supportsCollation($dataType)
	{
		return in_array(strtolower($dataType), self::$supportsCollation);
	}

	public static function supportsFulltext($dataType)
	{
		return in_array(strtolower($dataType), self::$supportsFulltext);
	}

	public static function supportsSize($dataType)
	{
		return in_array(strtolower($dataType), self::$supportsSize);
	}

	public static function supportsScale($dataType)
	{
		return in_array(strtolower($dataType), self::$supportsScale);
	}

	public static function supportsValues($dataType)
	{
		return in_array(strtolower($dataType), self::$supportsValues);
	}

}

?>