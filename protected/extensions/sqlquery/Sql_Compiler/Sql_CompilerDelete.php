<?php

/**
 *
 * Sql_CompilerDelete
 * @package Sql_Compiler
 * @author Thomas Schäfer
 * @since 03.12.2008 07:49:30
 * @desc compiles sql delete statements into string
 */
/**
 *
 * Sql_CompilerDelete
 * @package Sql_Compiler
 * @author Thomas Schäfer
 * @since 03.12.2008 07:49:30
 * @desc compiles sql delete statements into string
 */
class Sql_CompilerDelete implements Sql_InterfaceCompiler
{

	/**
	 * compile delete
	 * 	
	 * @desc build an delete statement string
	 * @access private
	 * @param string $type
	 * @return string
	 */
	public static function doCompile() 
	{
		$sql 	= Sql_Compiler::SQL_DELETE 
				. Sql_Compiler::SPACE 
				. Sql_Compiler::SQL_FROM
				. Sql_Compiler::SPACE 
				. implode(Sql_Compiler::COMMA . Sql_Compiler::SPACE, Sql_Object :: get('tree.TableNames') );

		// save the where clause
		if (Sql_Object :: has('tree.Where')) {
			
			$search_string = Sql_Compiler::compileSearchClause (Sql_Object :: get('tree.Where'));
			
			if (Sql_Compiler::isError($search_string)) {
				return $search_string;
			}
			$sql 	.= Sql_Compiler::SPACE
					. Sql_Compiler::SQL_WHERE
					. Sql_Compiler::SPACE
					. $search_string;
		}
		return $sql;
		
	}
	
	public static function compile( $tree )
	{
		Sql_Object::set("tree", $tree);
		return self::doCompile();
	}

}

