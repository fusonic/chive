<?php


/**
 *
 * Sql_CompilerSelect
 * @package Sql_Compiler
 * @author Thomas Sch�fer
 * @since 30.11.2008 07:49:30
 * @desc compiles sql select statements into string
 */
/**
 *
 * Sql_CompilerSelect
 * @package Sql_Compiler
 * @author Thomas Sch�fer
 * @since 30.11.2008 07:49:30
 * @desc compiles sql select statements into string
 */
class Sql_CompilerSelect implements Sql_InterfaceCompiler 
{

	/**
	 * process select compilation
	 * @access private 
	 * @param mixed $arg
	 * @return string
	 */
	public static function doCompile() 
	{

		// save the command and set quantifiers
		$sql = Sql_Compiler :: SQL_SELECT . Sql_Compiler :: SPACE;
		if (Sql_Object :: has('tree.Quantifier')) {
			$sql .= Sql_Object :: get('tree.Quantifier') . Sql_Compiler :: SPACE;
		}

		if (!Sql_Object :: has("tree.Bridge") and count(Sql_Object :: get("tree.ColumnNames")) < 1) {
			$sql .= self :: ASTERISK;
		} else {
			// save the column names and set functions
			$sql = Sql_Compiler::compileColumns($sql);
		}

		// save the tables
		$sql .= Sql_Compiler::$breakline . Sql_Compiler :: SPACE . Sql_Compiler :: SQL_FROM . Sql_Compiler :: SPACE;

		for ($i = 0; $i < count(Sql_Object :: get('tree.TableNames')); $i++) {
			
			if(Sql_Object :: has('tree.DatabaseNames')) {
			    $sql .= Sql_Object :: get('tree.DatabaseNames.' . $i) .".";
			    $sql .= Sql_Object :: get('tree.TableNames.' . $i);
			} else {
			    $sql .= Sql_Object :: get('tree.TableNames.' . $i);
			}
			if (Sql_Object :: get('tree.TableAliases.' . $i) != '') {
				$sql .= Sql_Compiler :: ALIAS . Sql_Object :: get('tree.TableAliases.' . $i);
			}
			if (Sql_Object :: get('tree.Joins.' . $i) != '') {
				$search_string = Sql_Compiler :: compileSearchClause(Sql_Object :: get('tree.Joins.' . $i));
				if (Sql_Compiler :: isError($search_string)) {
					return $search_string;
				}
				$sql .= Sql_Compiler :: ON . $search_string;
			}
			if (Sql_Object :: has('tree.Join.' . $i)) {
				$sql .= Sql_Compiler::$breakline;
				$sql .= Sql_Compiler :: SPACE . Sql_Object :: get('tree.Join.' . $i) . Sql_Compiler :: SPACE;
			}
		}
		// save the where clause
		if (Sql_Object :: has('tree.Where')) {
			$search_string = Sql_Compiler :: compileSearchClause(Sql_Object :: get('tree.Where'));
			if (Sql_Compiler::isError($search_string)) {
				return $search_string;
			}
			$sql .= Sql_Compiler::$breakline;
			$sql .= Sql_Compiler :: SPACE . Sql_Compiler :: SQL_WHERE . Sql_Compiler :: SPACE . $search_string;
		}

		// save the group by clause
		if (Sql_Object :: has('tree.GroupBy')) {
			$sql .= Sql_Compiler::$breakline;
			$sql .= Sql_Compiler :: SPACE . Sql_Compiler :: SQL_GROUPBY . Sql_Compiler :: SPACE . implode(Sql_Compiler :: COMMA . Sql_Compiler :: SPACE, Sql_Object :: get('tree.GroupBy'));
		}

		// save the having clause
		if (Sql_Object :: has('tree.Having')) {
			$search_string = Sql_Compiler :: compileSearchClause(Sql_Object :: get('tree.Having'));
			if (Sql_Compiler::isError($search_string)) {
				return $search_string;
			}
			$sql .= Sql_Compiler::$breakline;
			$sql .= Sql_Compiler :: SPACE . Sql_Compiler :: SQL_HAVING . Sql_Compiler :: SPACE . $search_string;
		}

		// save the order by clause
		if (Sql_Object :: has('tree.SortOrder')) {
			$sort_order = array ();
			foreach (Sql_Object :: get('tree.SortOrder') as $key => $value) {
				$sort_order[] = $key . Sql_Compiler :: SPACE . $value;
			}
			$sql .= Sql_Compiler::$breakline;
			$sql .= Sql_Compiler :: SPACE . Sql_Compiler :: SQL_ORDERBY . Sql_Compiler :: SPACE . implode(Sql_Compiler :: COMMA . Sql_Compiler :: SPACE, $sort_order);
		}

		// save the limit clause

		if (Sql_Object :: has('tree.Limit')) {
			$sql .= Sql_Compiler::$breakline;
			$sql .= Sql_Compiler :: SPACE . Sql_Compiler :: SQL_LIMIT . Sql_Compiler :: SPACE . Sql_Object :: get('tree.Limit.Start') . Sql_Compiler :: COMMA . Sql_Object :: get('tree.Limit.Length');
		}

		return $sql;
	}
	
	public static function compile( $tree )
	{
		Sql_Object::set("tree", $tree);
		return self::doCompile();
	}

}