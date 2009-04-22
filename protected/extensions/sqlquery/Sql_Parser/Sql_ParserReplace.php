<?php

/**
 *
 * Sql_ParserReplace
 * @package Sql
 * @subpackage Sql_Parser
 * @author Thomas Schäfer
 * @since 30.11.2008 07:49:30
 * @desc parses a sql replace statement into object
 */

/**
 *
 * Sql_ParserReplace
 * @package Sql
 * @subpackage Sql_Parser
 * @author Thomas Schäfer
 * @since 30.11.2008 07:49:30
 * @desc parses a sql replace statement into object
 */
class Sql_ParserReplace extends Sql_ParserInsert {
	
	public static function parse(){
		return self::doParse("replace");
	}

}

