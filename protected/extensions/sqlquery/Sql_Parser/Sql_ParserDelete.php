<?php

/**
 *
 * Sql_ParserDelete
 * @package Sql
 * @subpackage Sql_Parser
 * @author Thomas Schäfer
 * @since 30.11.2008 07:49:30
 * @desc parses a sql delete into object
 */

/**
 *
 * Sql_ParserDelete
 * @package Sql
 * @subpackage Sql_Parser
 * @author Thomas Schäfer
 * @since 30.11.2008 07:49:30
 * @desc parses a sql delete into object
 */

class Sql_ParserDelete implements Sql_InterfaceParser {

	public static function doParse(){

		Sql_Parser::getTok();
		
		if (Sql_Object::token() != 'from') {
			return Sql_Parser::raiseError('Expected "from"');
		}
		
		$tree = array('Command' => 'delete');
		
		Sql_Parser::getTok();
		
		if (Sql_Object::token() != 'ident') {
			return Sql_Parser::raiseError('Expected a table name');
		}
		
		$tree['TableNames'][] = Sql_Object::lexer()->tokText;
		
		Sql_Parser::getTok();
		
		if (Sql_Object::token() != 'where') {
			return Sql_Parser::raiseError('Expected "where"');
		}
		
		$clause = Sql_Parser::parseSearchClause();
		
		if (Sql_Parser::isError($clause)) {
			return $clause;
		}
		$tree['Where'] = $clause;
		
		return $tree;
	}
	
    public static function parse() {
    	return self::doParse();
    }

}

