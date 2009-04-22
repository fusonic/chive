<?php

/**
 *
 * Sql_Compiler
 * @package Sql
 * @author Thomas Sch�fer
 * @since 02.12.2008
 * @desc compiles sql statements into string
 */
/**
 *
 * Sql_Compiler
 * @package Sql
 * @author Thomas Sch�fer
 * @since 02.12.2008 07:49:30
 * @desc compiles sql statements into string
 */
class Sql_Compiler {

	const LINEBREAK = "\n";
	const OPENBRACE = "(";
	const CLOSEBRACE = ")";
	const COMMA = ",";
	const SEMICOLON = ";";
	const COLON = ".";
	const ESCAPE = "'";
	const SPACE = " ";
	const TICK = "`";
	const ALIAS = " AS ";
	const ON = " ON ";
	const ASTERISK = "*";
	const DBLQUOTE = '"';
	const QUOTE =  "'";

	const SQL_NOT = "NOT";
	const SQL_NULL = "NULL";
	const SQL_IS = "IS";
	const SQL_WHERE = "WHERE";
	const SQL_AND = "AND";
	const SQL_OR = "OR";
	const SQL_GROUPBY = "GROUP BY";
	const SQL_HAVING = "HAVING";
	const SQL_SELECT = "SELECT";
	const SQL_FROM = "FROM";
	const SQL_DISTINCT = "DISTINCT";
	const SQL_ORDERBY = "ORDER BY";
	const SQL_LIMIT = "LIMIT";
	const SQL_INSERT = "INSERT INTO";
	const SQL_REPLACE = "REPLACE INTO";
	const SQL_DELETE = "DELETE";
	const SQL_UPDATE = "UPDATE";
	const SQL_SET = "SET";
	const SQL_INTO = "INTO";
	const SQL_VALUES = "VALUES";
	const SQL_UNION = "UNION";
	const SQL_ADD = "ADD";
	const SQL_ALTER = "ALTER";
	const SQL_CREATE = "CREATE";
	const SQL_VIEW = "VIEW";
	const SQL_INDEX = "INDEX";
	const SQL_TABLE = "TABLE";
	const SQL_SHOW = "SHOW";
	const SQL_GRANT = "GRANT";
	const SQL_COLUMNS = "COLUMNS";
	const SQL_EXPLAIN = "EXPLAIN";
	const SQL_DESCRIBE = "DESCRIBE";
	const SQL_ANALYSE = "ANALYSE";
	const SQL_OPTIMIZE = "OPTIMIZE";
	const SQL_DROP = "DROP";
	const SQL_PRIMARYKEY = "PRIMARY KEY";
	const SQL_DEFAULT = "DEFAULT";
	const SQL_KEY = "KEY";
	const SQL_USING = "USING";
	const SQL_ON = "ON";


	/**
	 * linebreak char property
	 * @var string
	 */
	public static $breakline = self::LINEBREAK;

	/**
	 * indentation level property
	 * @var integer
	 */
	protected static $indent = 0;

	/**
	 * sql object tree
	 * @var array
	 */
	protected $tree;

	/**
	 * holder of copy
	 * @var array
	 */
	protected $backupTree;


	/**
	 * notBreakLine
	 *
	 * @desc unset line break
	 * @access public
	 * return void
	 */
	public static function notBreakline() {
		self::$breakLine = '';
	}

	/**
	 * setIndent
	 * @desc set indentation string
	 * @desc public
	 * @param string $indent
	 */
	public static function setIndent($indent) {
		self::$indent = $indent;
	}

	/**
	 * indent
	 *
	 * @access private
	 * @desc indentation string by level
	 * @return string
	 */
	public static function indent() {
		return str_pad(self::SPACE, 2 * (self::$indent+1),self::SPACE);
	}

	/**
	 * isError
	 *
	 * @desc check if is error
	 * @param Exception $data
	 * @param string $code
	 * @return mixed
	 */
	public static function isError($data, $code = null)
	{
		if (is_a($data, 'Exception')) {
			if (is_null($code)) {
				return true;
			} elseif (is_string($code)) {
				return $data->getMessage() == $code;
			} else {
				return $data->getCode() == $code;
			}
		}
		return false;
	}

	/**
	 * raiseError
	 *
	 * @desc raise an exception
	 * @access private
	 * @param string $message
	 * @return Exception
	 */
	public static function raiseError($message) {
		throw new Exception($message);
	}

	public static function escape($value) {
		return self::DBLQUOTE . $value . self::DBLQUOTE;
	}

	public static function compileColumns($sql) {

		for ($i = 0; $i < count(Sql_Object :: get('tree.ColumnNames')); $i++) {
            // added 2008-01-16
            if(Sql_Object :: has('tree.ColumnTableAliases') and Sql_Object :: count('tree.ColumnTableAliases')) {
                $column = '';
                if(Sql_Object :: length('tree.ColumnTableAliases.' . $i)>0)
                    $column = Sql_Object :: get('tree.ColumnTableAliases.' . $i) . ".";
                $column .= Sql_Object :: get('tree.ColumnNames.' . $i);
            } elseif(Sql_Object :: has('tree.ColumnTables') and Sql_Object :: count('tree.ColumnTables')) {
                $column = '';
                if(Sql_Object :: length('tree.ColumnTables.' . $i)>0)
                $column = Sql_Object :: get('tree.ColumnTables.' . $i) . ".";
                $column .= Sql_Object :: get('tree.ColumnNames.' . $i);
            } else {
			    $column = Sql_Object :: get('tree.ColumnNames.' . $i);
            }
			if (Sql_Object :: get('tree.ColumnAliases.' . $i) != '') {
				$column .= Sql_Compiler :: ALIAS . Sql_Object :: get('tree.ColumnAliases.' . $i);
			}
			// add only if there is a column
			if(strlen($column)) {
				$column_names[] = $column;
			}
		}

		// loop on functions
		for ($i = 0; $i < count(Sql_Object :: get('tree.Function')); $i++) {
			if (Sql_Object :: has('tree.Function.' . $i . '.Arg')) {
				$funcName = Sql_Object :: get('tree.Function.' . $i . '.Name');
				$column = Sql_Object :: get('tree.Function.' . $i . '.Name') . Sql_Compiler :: OPENBRACE;
				if (Sql_Object :: has('tree.Function.' . $i . '.Distinct')) {
					$column .= Sql_Compiler :: SQL_DISTINCT . Sql_Compiler :: SPACE;
				}
				switch (strtolower($funcName)) {
					case 'case' :
					case 'if' :
						$column_names[] = Sql_CompilerFlow::compile($funcName, Sql_Object :: get('tree.Function.' . $i));
						break;
					case Sql_Object::has("functions.".strtolower($funcName)):
						$column_names[] = Sql_CompilerFunction::compile($funcName, Sql_Object :: get('tree.Function.' . $i));
						break;
					default :
						if (is_array(Sql_Object :: get('tree.Function.' . $i . '.Arg'))) {
							$column .= implode(Sql_Compiler :: COMMA, Sql_Object :: get('tree.Function.' . $i . '.Arg'));
						} else {
							$column .= Sql_Object :: get('tree.Function.' . $i . '.Arg');
						}
						$column .= Sql_Compiler :: CLOSEBRACE;
						if (Sql_Object :: get('tree.Function.' . $i . '.Alias') != '') {
							$column .= Sql_Compiler :: ALIAS . Sql_Object :: get('tree.Function.' . $i . '.Alias');
						}
						$column_names[] = $column;
						break;
				}
			}
		}

		if (isset ($column_names)) {
			$sql .= implode(", ", $column_names);
		}

		return $sql;
	}

	/**
	 * values of where
	 *
	 * @desc set condition items by type
	 * @access private
	 * @param array $arg
	 * @return string
	 */
	public static function getWhereValue ($arg)
	{
		switch ($arg['Type'])
		{
			case 'null':
			case 'ident':
			case 'real_val':
			case 'int_val':
				$value = $arg['Value'];
				break;
			case 'text_val':
				$value = self::ESCAPE .$arg['Value']. self::ESCAPE;
				break;
			case 'subselect':
				$value = self::OPENBRACE;
				$value .= self::doCompile($arg['Value']);
				$value .= self::CLOSEBRACE;
				break;
			case 'subclause':
				$value = self::OPENBRACE . self::compileSearchClause($arg['Value']). self::CLOSEBRACE;
				break;
			default:
				return self::raiseError('Unknown type: '.$arg['type']);
		}
		return $value;
	}

	/**
	 * get params
	 *
	 * @desc identify parameters within compilation
	 * @access private
	 * @param array $arg
	 * @return string
	 */
	public static function getParams($arg)
	{
		for ($i = 0; $i < count ($arg['Type']); $i++) {
			switch ($arg['Type'][$i]) {
				case 'ident':
				case 'real_val':
				case 'int_val':
					$value[] = $arg['Value'][$i];
					break;
				case 'text_val':
					$value[] = self::ESCAPE .$arg['Value'][$i]. self::ESCAPE;
					break;
				default:
					return self::raiseError('Unknown type: '.$arg['Type']);
			}
		}
		$value = self::OPENBRACE . implode(self::COMMA . self::SPACE, $value) . self::CLOSEBRACE;
		return $value;
	}

	/**
	 * search clause
	 *
	 * @desc build sql where statement part
	 * @accress private
	 * @param array $where_clause
	 * @return string
	 */
	public static function compileSearchClause($where_clause)
	{
		$value = '';
		if (isset ($where_clause['Left']['Value'])) {
			$value = self::getWhereValue ($where_clause['Left']);
			if (self::isError($value)) {
				return $value;
			}
			$sql = $value;
		} else {
			$value = self::compileSearchClause($where_clause['Left']);
			if (self::isError($value)) {
				return $value;
			}
			$sql = $value;
		}
		if (isset ($where_clause['Op'])) {
			if (strtolower($where_clause['Op']) == 'in') {
				if($where_clause["Right"]["Type"]=="command") {
					// new instance enabling sub-selects
					$value 	= self::OPENBRACE
							. Sql_Compiler::compile($where_clause["Right"]["Value"])
							. self::CLOSEBRACE;

				} else {
					$value = self::getParams($where_clause['Right']);
				}
				if (self::isError($value)) {
					return $value;
				}

				$sql 	.= self::SPACE
						. $where_clause['Op']
						. self::SPACE
						. $value;

			} elseif (strtolower($where_clause['Op']) == 'is') {
				if (isset ($where_clause['Neg'])) {
					$value 	= self::SQL_NOT
							. self::SPACE
							. self::SQL_NULL;
				} else {
					$value = self::SQL_NULL;
				}
				$sql 	.= self::SPACE
						. self::SQL_IS
						. self::SPACE
						. $value;
			} elseif (strtolower($where_clause['Op']) == 'between') {
				// added 2008-12-19
				$sql .= self::SPACE;
				$sql .= $where_clause['Op'];
				$sql .= self::SPACE;

				if(isset($where_clause['Right'])){
					$sql .= self::SPACE;
					$sql .= $where_clause['Right']['Value']['Left']["Value"];
					$sql .= self::SPACE;
					$sql .= $where_clause['Right']['Value']["Op"];
					$sql .= self::SPACE;
					$sql .= $where_clause['Right']['Value']['Right']["Value"];
				}
			} else {
				$sql .= self::SPACE.$where_clause['Op'].self::SPACE;
				if (isset ($where_clause['Right']['Value'])) {
					$value = self::getWhereValue ($where_clause['Right']);
					if (self::isError($value)) {
						return $value;
					}
					$sql .= $value;
				} else {
					$value = self::compileSearchClause($where_clause['Right']);
					if (self::isError($value)) {
						return $value;
					}
					$sql .= $value;
				}
			}
		}
		return $sql;
	}

	private function reCompile($array){
		$object = new Sql_Compiler();
		return $object->compile($array);
	}

	private static function doCompile($array){
		$object = new Sql_Compiler();
		return $object->compile($array);
	}

	public function compile($array = null)
	{

		$this->tree = $array;
		switch ($this->tree['Command']) {
			case 'union':
			case 'select':
			case 'update':
			case 'delete':
			case 'insert':
			case 'replace':
				$className = __CLASS__.ucfirst($this->tree['Command']);
				return call_user_func(array($className,"compile"), $array);
			default:
				return self::raiseError('Unknown action: '.$this->tree['Command']);
		}

	}

}

