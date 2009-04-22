<?php

/**
 *
 * Sql_CompilerInsert
 * @package Sql_Compiler
 * @author Thomas Sch&#65533;fer
 * @since 03.12.2008 07:49:30
 * @desc compiles sql insert statements into string
 */
/**
 *
 * Sql_CompilerInsert
 * @package Sql_Compiler
 * @author Thomas Sch&#65533;fer
 * @since 03.12.2008 07:49:30
 * @desc compiles sql insert statements into string
 */
class Sql_CompilerInsert implements Sql_InterfaceCompiler
{

	/**
	 * compile insert
	 *
	 * @desc build an insert statement string
	 * @access private
	 * @param string $type
	 * @return string
	 */
	public static function doCompile($type="insert")
	{
		$sql 	= strtoupper($type)
				. Sql_Compiler::SPACE
				. Sql_Compiler::SQL_INTO
				. Sql_Compiler::SPACE;

		if(Sql_Object :: has('tree.SubClause')){
			$sql .= Sql_Object :: get('tree.TableNames.0') . Sql_Compiler::SPACE;
			Sql_Object::set('backupTree', Sql_Object :: get('tree') );
			Sql_Object::set('tree', Sql_Object :: get('tree.SubClause') );
			$sql .= Sql_Compiler::doCompile();
		}
		else
		{
			if(Sql_Object :: has('tree.Statement'))
			{
				$sql .= implode(Sql_Compiler::SPACE, Sql_Object :: get('tree.Statement')) . Sql_Compiler::SPACE;
			}

			$sql	.= Sql_Object :: get('tree.TableNames.0') . Sql_Compiler::SPACE;

			if(Sql_Object :: has('tree.ColumnNames.0')){
				$sql .=  Sql_Compiler::OPENBRACE
					. implode(Sql_Compiler::COMMA . Sql_Compiler::SPACE, Sql_Object :: get('tree.ColumnNames') )
					. Sql_Compiler::CLOSEBRACE
					. Sql_Compiler::SPACE;
			}

			$sql .= Sql_Compiler::SQL_VALUES
				. Sql_Compiler::SPACE
				. Sql_Compiler::OPENBRACE;

			if(Sql_Object :: has('tree.Values'))
			{
				// multiple inserts
				if(Sql_Object :: get('tree.Values.0.0')){
					$values = Sql_Object :: get('tree.Values');
					$values_array = array();
					for ($j = 0; $j < count ($values); $j++)
					{
						$value_array = array();
						$value = $values[$j];
						for ($i = 0; $i < count ($value); $i++)
						{
							if(!empty($value[$i])) {
								$return = Sql_Compiler::getWhereValue ($value[$i]);
								if (Sql_Compiler::isError($return)) {
									return $return;
								}
								$value_array[] = $return;
							}
						}
						$values_array[] = implode(Sql_Compiler::COMMA . Sql_Compiler::SPACE, $value_array)
										. Sql_Compiler::CLOSEBRACE;
					}
					$sql .= implode(", (", $values_array);

				} else {
					for ($i = 0; $i < count (Sql_Object :: get('tree.Values')); $i++)
					{
						if(Sql_Object :: has('tree.Values.'.$i)) {
							if(Sql_Object :: get('tree.Values.'.$i.'.0')){

							}
							$value = Sql_Compiler::getWhereValue (Sql_Object :: get('tree.Values.'.$i) );
							if (Sql_Compiler::isError($value)) {
								return $value;
							}
							$value_array[] = $value;
						}
					}
					$sql 	.= implode(Sql_Compiler::COMMA . Sql_Compiler::SPACE, $value_array)
							. Sql_Compiler::CLOSEBRACE;
				}
			}
		}
		return $sql;

	}

	public static function compile( $tree )
	{
		Sql_Object::set("tree", $tree);
		return self::doCompile();
	}

}

