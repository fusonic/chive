<?php

/**
 *
 * Sql_CompilerReplace
 * @package Sql_Compiler
 * @author Thomas Schäfer
 * @since 03.12.2008 07:49:30
 * @desc compiles sql replace statements into string
 */
/**
 *
 * Sql_CompilerReplace
 * @package Sql_Compiler
 * @author Thomas Schäfer
 * @since 03.12.2008 07:49:30
 * @desc compiles sql replace statements into string
 */
class Sql_CompilerReplace extends Sql_CompilerInsert
{

	/**
	 * compile insert
	 * 	
	 * @desc facade build an replace statement string
	 * @access private
	 * @param string $type
	 * @return string
	 */
	public static function doCompile() 
	{
		return parent::doCompile("replace");
	}
	
	public function compile( $tree )
	{
		Sql_Object::set("tree", $tree);
		return self::doCompile();
	}

}

