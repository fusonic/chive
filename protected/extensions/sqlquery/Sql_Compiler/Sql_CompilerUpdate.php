<?php

/**
 *
 * Sql_CompilerUpdate
 * @package Sql_Compiler
 * @author Thomas Schäfer
 * @since 03.12.2008 07:49:30
 * @desc compiles sql update statements into string
 */
/**
 *
 * Sql_CompilerUpdate
 * @package Sql_Compiler
 * @author Thomas Schäfer
 * @since 03.12.2008 07:49:30
 * @desc compiles sql update statements into string
 */
class Sql_CompilerUpdate implements Sql_InterfaceCompiler
{

	/**
	 * compile insert
	 * 	
	 * @desc build an insert statement string
	 * @access private
	 * @param string $type
	 * @return string
	 */
	public static function doCompile() 
	{
		$sql 	= Sql_Compiler::SQL_UPDATE 
				. Sql_Compiler::SPACE;
		
		if(Sql_Object :: has('tree.Statement')) {
			$sql .= implode(Sql_Compiler::SPACE, Sql_Object :: get('tree.Statement')) . Sql_Compiler::SPACE;
		}
		$sql	.= implode(Sql_Compiler::COMMA . Sql_Compiler::SPACE, Sql_Object :: get('tree.TableNames'));

		// save the set clause
		for ($i = 0; $i < count (Sql_Object :: get('tree.ColumnNames')); $i++) {
			$set_columns[] 	= Sql_Object :: get('tree.ColumnNames.'.$i)
							. ' = '
							. Sql_Compiler::getWhereValue(Sql_Object :: get('tree.Values.'.$i));
		}
		// set
		$sql 	.= Sql_Compiler::SPACE 
				. Sql_Compiler::SQL_SET
				. Sql_Compiler::SPACE
				. implode (Sql_Compiler::COMMA . Sql_Compiler::SPACE, $set_columns);

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
		} else {
			return Sql_Compiler::raiseError("UPDATE without condition.");
		}

		return $sql;
	}
	
	public static function compile( $tree )
	{
		Sql_Object::set("tree", $tree);
		return self::doCompile();
	}

}

