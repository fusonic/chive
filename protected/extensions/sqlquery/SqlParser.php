<?php

Yii::import("application.extensions.sqlquery.libraries.*");

global $cfg;
global $PMA_SQPdata_column_attrib, $PMA_SQPdata_reserved_word, $PMA_SQPdata_column_type, $PMA_SQPdata_function_name;
global $PMA_SQPdata_column_attrib_cnt, $PMA_SQPdata_reserved_word_cnt, $PMA_SQPdata_column_type_cnt, $PMA_SQPdata_function_name_cnt;
global $mysql_charsets, $mysql_collations_flat, $mysql_charsets_count, $mysql_collations_count;
global $PMA_SQPdata_forbidden_word, $PMA_SQPdata_forbidden_word_cnt;

require_once('common.lib.php');
require_once('sqlparser.data.php');
require_once('sqlparser.lib.php');

class SQPException extends Exception
{
}

class SqlParser
{
	public static function parsePMA($_sql)
	{
		return self::trim(PMA_SQP_parse($_sql));
	}
	
	public static function parse($_sql)
	{
		if(!is_array($_sql))
		{
			$_sql = self::parsePMA($_sql);
		}
		
		$analyzedSql = PMA_SQP_analyze($_sql);
		return self::trim(@$analyzedSql[0]);
	}
	
	private static function trim($_array)
	{
		if(!is_array($_array))
		{
			return rtrim($_array);
		}
		
   		return array_map(array("SqlParser", "trim"), $_array);
	}
	
}
