<?php

/**
 *
 * Sql_ParserInsert
 * @package Sql
 * @subpackage Sql_Parser
 * @author Thomas Sch�fer
 * @since 30.11.2008 07:49:30
 * @desc parses a sql insert statement into object
 */
/**
 *
 * Sql_ParserInsert
 * @package Sql
 * @subpackage Sql_Parser
 * @author Thomas Sch�fer
 * @since 30.11.2008 07:49:30
 * @desc parses a sql insert statement into object
 */
class Sql_ParserInsert implements Sql_InterfaceParser {

	private function doParse($type="insert") {

		$tree = array('Command' => $type);

		Sql_Parser::getTok();

		if(Sql_Object::token() == "ident" and Sql_Object::lexer()->tokText=="DELAYED") {
				$tree["Statement"][] = Sql_Object::lexer()->tokText;
				Sql_Parser::getTok();
		}

		if(Sql_Object::token() == "ident" and Sql_Object::lexer()->tokText=="IGNORE") {
				$tree["Statement"][] = Sql_Object::lexer()->tokText;
				Sql_Parser::getTok();
		}

		if (Sql_Object::token() == 'into')
		{
			Sql_Parser::getTok();
			if (Sql_Object::token() == 'ident')
			{
				$tree['TableNames'][] = Sql_Object::lexer()->tokText;
				Sql_Parser::getTok();
				if(Sql_Object::token()=="select") {
					$tree['SubClause'] = Sql_ParserSelect::doParse();
					return $tree;
				}
			}
			else
			{
				return Sql_Parser::raiseError('Expected table name');
			}

			if (Sql_Object::token() == '(')
			{
				$results = Sql_Parser::getParams();

				if (Sql_Parser::isError($results))
				{
					return $results;
				}
				else
				{
					if (sizeof($results))
					{
						$tree['ColumnNames'] = $results["values"];
					}
				}
				Sql_Parser::getTok();
			}

			if (strtolower(Sql_Object::token()) == 'values')
			{
				do {
					Sql_Parser::getTok();
					$results = Sql_Parser::getParams();
					if (Sql_Parser::isError($results))
					{
						return $results;
					}
					else
					{
						if (isset($tree['ColumnDefs']) && (count($tree['ColumnDefs']) != count($results)))
						{
							return Sql_Parser::raiseError('field/value mismatch');
						}
						if (count($results)) {
							$values = array();
							foreach ($results["values"] as $key=>$value) {
								$values[$key] = array('Value'=>$value, 'Type'=>$results["types"][$key]);
							}
							$tree['Values'][] = $values;
						}
						else
						{
							return Sql_Parser::raiseError('No fields to insert');
						}
					}
					Sql_Parser::getTok();
				} while (Sql_Object::token() == ',');
			}
			else
			{
				return Sql_Parser::raiseError('Expected "values"');
			}
		}
		else
		{
			return Sql_Parser::raiseError('Expected "into"');
		}
		return $tree;
	}

	public static function parse(){
		return self::doParse();
	}

}
