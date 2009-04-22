<?php

/**
 *
 * Sql_CompilerUnion
 * @package Sql
 * @subpackage Sql_Parser
 * @author Thomas Schäfer
 * @since 12.12.2008
 * @desc compile a sql Union object into string
 */
/**
 *
 * Sql_CompilerUnion
 * @package Sql
 * @subpackage Sql_Parser
 * @author Thomas Schäfer
 * @since 12.12.2008
 * @desc compile a sql Union object into string
 */

class Sql_CompilerUnion implements Sql_InterfaceCompiler 
{

	public static function doCompile() 
	{
		$tree = Sql_Object::get("tree");
		$array = array();
		foreach(Sql_Object::get("tree.Union") as $index => $statement) {
			$compose = new Sql_Compiler();
			$array[] = $compose->compile($statement);
		}
		return implode(Sql_Compiler::LINEBREAK . strtoupper($tree["Command"]). Sql_Compiler::LINEBREAK, $array);
		
	}
	
	public static function compile($tree)
	{
		Sql_Object::set("tree", $tree);
		return self::doCompile();
	}

}