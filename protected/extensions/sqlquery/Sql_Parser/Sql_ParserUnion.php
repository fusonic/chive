<?php

/**
 *
 * Sql_ParserUnion
 * @package Sql
 * @subpackage Sql_Parser
 * @author Thomas Schäfer
 * @since 30.11.2008 07:49:30
 * @desc parses a sql Union into object
 */

/**
 *
 * Sql_ParserUnion
 * @package Sql
 * @subpackage Sql_Parser
 * @author Thomas Schäfer
 * @since 30.11.2008 07:49:30
 * @desc parses a sql Union into object
 */
class Sql_ParserUnion implements Sql_InterfaceParser {

	public static function doParse($subSelect = false) 
	{
		$tree = array('Command' => 'union');
		
		$SQL = Sql_Object::lexer()->string;
		$selects = preg_split('/union/i', $SQL);
		
		foreach($selects as $index => $select) {
			$object = new Sql_Parser($select);
			$tree["Union"][] = $object->parse();	
		}
		return $tree;		
	}

	public static function parse(){
		return self::doParse();
	}
}

