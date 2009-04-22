<?php

/**
 *
 * Sql_ParserUpdate
 * @package Sql
 * @subpackage Sql_Parser
 * @author Thomas Schäfer
 * @since 30.11.2008 07:49:30
 * @desc parses a sql update into object
 */

/**
 *
 * Sql_ParserUpdate
 * @package Sql
 * @subpackage Sql_Parser
 * @author Thomas Schäfer
 * @since 30.11.2008 07:49:30
 * @desc parses a sql update into object
 */

class Sql_ParserUpdate implements Sql_InterfaceParser {

	public static function doParse(){
	
		Sql_Parser::getTok();
	
		if (Sql_Object::token() == 'ident') {
			$tree = array('Command' => 'update');
			
			if(Sql_Object::token() == "ident" and Sql_Object::lexer()->tokText=="IGNORE") {
				$tree["Statement"][] = Sql_Object::lexer()->tokText;
				Sql_Parser::getTok();
			}
			$tree['TableNames'][] = Sql_Object::lexer()->tokText;
		} else {
			return self::raiseError('Expected table name');
		}

		Sql_Parser::getTok();

		if (Sql_Object::token() != 'set') {
			return self::raiseError('Expected "set"');
		}

		while (true) 
		{
			
			Sql_Parser::getTok();
			
			if (Sql_Object::token() != 'ident') {
				return Sql_Parser::raiseError('Expected a column name');
			}
			$tree['ColumnNames'][] = Sql_Object::lexer()->tokText;
			
			Sql_Parser::getTok();
			
			if (Sql_Object::token() != '=') {
				return Sql_Parser::raiseError('Expected =');
			}
			
			Sql_Parser::getTok();
			
			if (!Sql_Parser::isVal(Sql_Object::token())) {
				return Sql_Parser::raiseError('Expected a value');
			}
			$tree['Values'][] = array('Value'=>Sql_Object::lexer()->tokText,
                                      'Type'=>Sql_Object::token());
			
			Sql_Parser::getTok();
			
			if (Sql_Object::token() == 'where') {
				$clause = Sql_Parser::parseSearchClause();
				if (Sql_Parser::isError($clause)) {
					return $clause;
				}
				$tree['Where'] = $clause;
				break;
			} 
			elseif (Sql_Object::token() != ',') 
			{
				return Sql_Parser::raiseError('Expected "where" or ","');
			}
		}
		return $tree;
		
	}
	
    public static function parse() {
    	return self::doParse();
    }

}

