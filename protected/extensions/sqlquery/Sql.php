<?php


/**
 *
 * Sql
 * @package Model
 * @subpackage Model_Sql
 * @author Thomas Sch�fer
 * @since 05.08.2008 15:30:41
 * @version 0.2.1
 * @desc parses and compiles sql statements
*/
/**
 *
 * Sql
 * @package Sql
 * @author Thomas Sch�fer
 * @since 05.08.2008 15:30:41
 * @version 0.2.1
 * @desc parses and compiles sql statements
*/
class Sql {


	private $properties = array(
		"Adapter" => false,
	);

	/**
	 * construct and set adapter name
	 *
	 * @param string $adapter mysql|mysqli <= QDataObject
	 */
	public function __construct($adapter="mysqli") {
		$this->properties["Adapter"] = strtolower($adapter);
	}

	/**
	 * facade for Sql_Parser::parse
	 * parse sql and merge with properties
	 *
	 * @param string $sql
	 * @return self
	 */
	public function parse($sql) {
		$parser = new Sql_Parser($sql);
		$parsed = $parser->parse();
		if(is_array($parsed)) {
			$this->properties = array_merge($this->properties, $parsed);
			return $this;
		} else {
			$this->properties["Error"] = $parsed;
			return $this;
		}
	}

	/**
	 * facade for Sql_Compiler::compile
	 * compile properties to sql
	 *
	 * @return string
	 */
	public function compile() {
		$compile = new Sql_Compiler();
		$sql = $compile->compile($this->properties);
		//$sql = str_replace("'?'","?",$sql);
		return $sql;
	}

	/**
	* facade for compile
	* @return string
	*/
    public function getSql($array=null){
    	return $this->compile($array);
    }


	/*join methods*/
	/**
	 * setJoinLeft
	 * @desc left join
	 * @param array $array
	 * @return self
	*/
    public function setJoinLeft($array) {
		$this->setJoin("left", $array);
		return $this;
	}

	/**
	 * setJoinOuterLeft
	 * @desc left outer join
	 * @param array $array
	 * @return self
	*/
	public function setJoinOuterLeft($array) {
		$this->setJoin("outer left", $array);
		return $this;
	}

	/**
	 * setJoinRight
	 * @desc left right
	 * @param array $array
	 * @return self
	*/
	public function setJoinRight($array) {
		$this->setJoin("right", $array);
		return $this;
	}

	/**
	 * setJoinOuterLeft
	 * @desc right outer join
	 * @param array $array
	 * @return self
	*/
	public function setJoinOuterRight($array) {
		$this->setJoin("outer right", $array);
		return $this;
	}

	/**
	 * setJoinInner
	 * @desc inner join
	 * @param array $array
	 * @return self
	*/
	public function setJoinInner($array) {
		$this->setJoin("inner", $array);
		return $this;
	}

	/**
	 * setJoin
	 * @desc common join builder
	 * @access private
	 * @param array $array
	 * @return void
	*/
	private function setJoin($type, $array){
		$this->properties["Join"][] = strtoupper($type). " JOIN";

		$a = explode(".", $array["Left"]["Value"]);
		$this->properties["TableNames"][] = count($a)==1?"":$a[0];
		$this->properties["TableAliases"][] = isset($array["Left"]["Alias"])
				? $array["Left"]["Alias"]:'';

		$b = explode(".", $array["Right"]["Value"]);

		$this->properties["TableNames"][] = count($b)==1?"":$b[0];
		$this->properties["TableAliases"][] = isset($array["Right"]["Alias"])
				? $array["Right"]["Alias"]:'';
		$this->properties["Joins"][] = $array;
	}

	/**
	 * setProperty
	 * @desc common property setter
	 * @param array $array
	 * @return self
	*/
	public function setProperty($key, $value) {
		$this->properties[$key] = $value;
	}

	/*where methods*/
	/**
	 * setAndWhere
	 * @desc default condition
	 * @param array $array
	 * @return self
	*/
	public function setAndWhere($array) {
		if(empty($this->properties["Where"])) {
			$where = $array;
		} else {
			$subwhere = array();
			$subwhere["Left"] = $this->getWhere();
			$subwhere["Op"] = "AND";
			$subwhere["Right"] = $array;
			$where["Left"]["Value"] = $subwhere;
			$where["Left"]["Type"] = "subclause";
		}
		$this->setWhere($where);
		return $this;
	}

	/**
	 * setOrWhere
	 * @desc default condition builder
	 * @param array $array
	 * @return self
	*/
	public function setOrWhere($array) {
		$where = array();
		if(empty($this->properties["Where"])) {
			$where["Left"]["Value"] = $array;
			$where["Left"]["Type"] = "subclause";
		} else {
			$subwhere = array();
			$subwhere["Left"] = $this->getWhere();
			$subwhere["Op"] = "OR";
			$subwhere["Right"] = $array;
			$where["Left"]["Value"] = $subwhere;
			$where["Left"]["Type"] = "subclause";
		}
		$this->setWhere($where);
		return $this;
	}

	/*having method*/
	/**
	 * setAndHaving
	 * @desc having
	 * @param array $array
	 * @return self
	*/
	public function setAndHaving($array) {
		if(empty($this->properties["Having"])) {
			$having = $array;
		} else {
			$subhaving = array();
			$subhaving["Left"] = $this->getHaving();
			$subhaving["Op"] = "AND";
			$subhaving["Right"] = $array;
			$having["Left"]["Value"] = $subhaving;
			$having["Left"]["Type"] = "subclause";
		}
		$this->setHaving($having);
		return $this;
	}

	/**
	 * setOrHaving
	 * @desc having
	 * @param array $array
	 * @return self
	*/
	public function setOrHaving($array) {
		$having = array();
		if(empty($this->properties["Having"])) {
			$having["Left"]["Value"] = $array;
			$having["Left"]["Type"] = "subclause";
		} else {
			$subhaving = array();
			$subhaving["Left"] = $this->getHaving();
			$subhaving["Op"] = "OR";
			$subhaving["Right"] = $array;
			$having["Left"]["Value"] = $subhaving;
			$having["Left"]["Type"] = "subclause";
		}
		$this->setWhere($having);
		return $this;
	}

	/**
	 * __call
	 * @desc dynamically calling properties
	 * - has => checks if a property exists
	 * - add => adds a new array to specified property
	 * - set => sets a property
	 * - get => gets a property
	 * @example $sqlObject->getTableNames()
	 * @param array $array
	 * @return self
	*/
	public function __call($funcName, $args) {
		$methodType = substr($funcName, 0, 3);
		$method = substr($funcName, 3);
		switch ($methodType)
		{
			case "has":
				if(array_key_exists($method, $this->properties)) {
					if(isset($this->properties[$method])) {
						return true;
					} else {
						return false;
					}
				}
				break;
			case "add":
				if(is_array($args[0])) {
					foreach($args[0] as $arg){
						$this->properties[$method][] = $arg;
					}
				} else {
					$this->properties[$method][] = $args[0];
				}
				return $this;
			case "set":
				if(array_key_exists($method, $this->properties)) {
					$this->properties[$method] = $args[0];
				} else {
					$this->properties[$method] = $args[0];
				}
				return $this;
			case "get":
				if(array_key_exists($method, $this->properties)) {
					if(isset($args[0]) and isset($this->properties[$method][$args[0]]) ) {
						return $this->properties[$method][$args[0]];
					} else {
						return $this->properties[$method];
					}
				}
				break;

		}
	}


	/**
	 * helper
	 */

	/**
	 * concatHelper
	 * @param string
	 * @desc string that joins to values of a concatenation
	 * @return array
	 */
	public static function concatHelper() {
		$string = "";
		if(func_num_args()>0) {
			$args = func_get_args();
			$string = implode("", $args);
		} else {
			$string = ' ';
		}
		return array( $string );
	}
	/**
	 * inHelper
	 *
	 * @desc setups in condition part
	 * @param array $array array(1,2,5)
	 * @return array
	 */
	public static function inHelper($array){
		$in = array();
		foreach($array as $value) {
			$in["Value"][] = $value;
			$in["Type"][] = "int_val";
		}
		return $in;
	}

	/**
	 * whereHelper
	 *
	 * @desc setups where condition values
	 * @param mixed $leftValue
	 * @param mixed $rightValue
	 * @param mixed $operator
	 * @param mixed $leftType
	 * @param mixed $rightType
	 * @return array
	 */
	public static function whereHelper($leftValue,$rightValue,$operator="=",$leftType="ident",$rightType="int_val"){
		switch(strtolower( $operator ) )
		{
			case "in":
				return array(
					"Left"=>array( "Value"=>$leftValue, "Type"=>$leftType ),
					"Op"=>$operator,
					"Right"=> self::inHelper($rightValue)
				);
			default:
				return array(
					"Left"=>array( "Value"=>$leftValue, "Type"=>$leftType ),
					"Op"=>$operator,
					"Right"=>array( "Value"=>$rightValue, "Type"=>$rightType )
				);
		}
	}

	/**
	 * functionHelper
	 *
	 * @desc setups functions
	 * @param array $array
	 * @return array
	 */
	public static function functionHelper($array) {
		switch(strtolower( $array[0] ) )
		{
			case "concat":
				$arrMap = array();
				$arrMap["Name"] = $array[0];
				foreach($array[1] as $key => $value) {
					 switch(gettype($value)) {
						case "array": $arrMap["Arg"][] = Sql_Parser::DBLQUOTE . implode("", $value ) . Sql_Parser::DBLQUOTE; break;
						default: $arrMap["Arg"][] = $value; break;
					 }
				}
				if(isset($array[2]) and is_string($array[2])) {
					$arrMap["Alias"] = $array[2];
				}
				return array( $arrMap );
			default:
				$arguments = count($array);
				if($arguments>1) {
					$result = array();
					if(isset($array[0])) {
						$result[0]["Name"] = strtoupper( $array[0] );
					}
					if( isset($array[1]) and isset($array[1]["Type"]) and isset($array[1]["Value"]))
					{
						// single argument function
						switch($array[1]["Type"]){
							case "ident":
							case "int_val":
							case "real_val":
								$result[0]["Arg"][0] = $array[1]["Value"];
							break;
							default:
								$result[0]["Arg"][0] = '"'.$array[1]["Value"].'"';
							break;
						}

					}
					elseif (isset( $array[1][0] ) and isset($array[1][0]["Type"]) and isset($array[1][0]["Value"]))
					{
						// double and more arguments functions
						foreach($array[1] as $index => $value){
							switch($value["Type"]){
								case "ident":
								case "int_val":
								case "real_val":
									$result[0]["Arg"][$index] = $value["Value"];
								break;
								default:
									$result[0]["Arg"][$index] = '"'.$value["Value"].'"';
								break;
							}
						}
					}
					if(isset($array[2]) and is_string($array[2])) {
						$result[0]["Alias"] = $array[2];
					}
					return $result;
				}
				break;
		}
	}




}

