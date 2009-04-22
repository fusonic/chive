<?php

/**
 *
 * Sql_ParserFunction
 * @package Sql
 * @subpackage Sql_Compiler
 * @author Thomas Sch&#65533;fer
 * @since 30.11.2008 07:49:30
 * @desc compiles a sql function into string
 */
class Sql_CompilerFunction {

	public static function doCompile($name, $tree, $recursing) {
		switch(strtolower($name))
		{
			case "pi":
			case "rand":
				$funcName = "Empty";
				break;
			// single argument functions
			case 'bit_count':
			case 'bit_or':
			case 'bit_and':
			case 'sum':
			case 'abs':
			case 'acos':
			case 'asin':
			case 'ceil':
			case 'ceiling':
			case 'cos':
			case 'cot':
			case 'crc32':
			case 'degrees':
			case 'exp':
			case 'floor':
			case 'format':
			case 'max':
			case 'min':
			case 'ln':
			case 'log':
			case 'log2':
			case 'log10':
			case 'radians':
			case 'rand':
			case 'round':
			case 'sign':
			case 'sin':
			case 'sqrt':
			case 'tan':
			// string functions
			case 'ascii':
			case 'bin':
			case 'bit_length':
			case 'char_length':
			case 'character_length':
			case 'lcase':
			case 'length':
			case 'lower':
			case 'ltrim':
			case 'oct':
			case 'octet_length':
			case 'ord':
			case 'quote':
			case 'reverse':
			case 'rtrim':
			case 'soundex':
			case 'space':
			case 'ucase':
			case 'unhex':
			case 'upper':
					$funcName = 'Single';
				break;
			// double argument functions
			case 'atan':
			case 'atan2':
			case 'pow':
			case 'power':
			case 'round':
			case 'truncate':
			case 'find_in_set':
			case 'format':
			case 'instr':
			case 'left':
			case 'locate':
			case 'repeat':
			case 'right':
			case 'substr':
			case 'substring':			
				$funcName = 'Double';				
				break;
			case 'count':
				$funcName = 'Distinctive';
				break;
			// infinite argument functions
			case 'concat':
			case 'concat_ws':
			case 'make_set':
			case 'elt':
				$funcName = 'Infinite';
				break;
			default:
				// other
				$funcName = 'Default';
				break;
				 
		}
		return call_user_func(array(__CLASS__,"process".$funcName),$tree, $recursing);
	}
	
	public static function processDefault($tree, $recursing){
		
	}

	public static function processEmpty($tree, $recursing){
		if(!isset($tree["Arg"]) || empty($tree["Arg"])) {
			$sql = $tree["Name"] . "(";
			$sql .= ")";
			if(isset($tree["Alias"])) {
				$sql .= " AS ". $tree["Alias"];
			}
			return $sql;			
		} else {
			return self::processSingle($tree, $recursing);
		}
	}

	public static function processSingle($tree, $recursing){
		$sql = $tree["Name"] . "(";
		if(is_array($tree["Arg"])) {
			if(is_array($tree["Arg"][0]) and isset( $tree["Arg"][0]["Function"]) ){
				$sql .= self :: doCompile($tree["Arg"][0]["Function"][0]["Name"], $tree["Arg"][0]["Function"][0], true);
			} else {
				$procSign = isset($tree["Arg"]["Left"]) ? true: false;
				if($procSign){
					$sql .= $tree["Arg"]["Left"]["Value"];
					$sql .= $tree["Arg"]["Op"];
					$sql .= $tree["Arg"]["Right"]["Value"];
				} else {
					$sign = ",";
					$sql .= implode($sign, $tree["Arg"]);
				}
			}
		} else {
			$sql .= $tree["Arg"];
		}
		$sql .= ")";
		if(isset($tree["Alias"])) {
			$sql .= " AS ". $tree["Alias"];
		}
		return $sql;
	}

	public static function processDouble($tree, $recursing){
		
		$sql = $tree["Name"] . "(";
		if(isset($tree["Arg"]["Left"])) {
			switch($tree["Arg"]["Left"]["Type"])
			{
				case "ident":
				case "int_val":
				case "real_val":
					$sql .= $tree["Arg"]["Left"]["Value"];
					break;
				case "text_val":
					$sql .= '"'. $tree["Arg"]["Left"]["Value"] .'"';
					break;
				case "Flowcontrol":					
					$sql .= Sql_CompileFlow::doCompile($tree["Arg"]["Left"]["Value"]["Name"], $tree["Arg"]["Left"]["Value"],true);
					break;
				case "Function":					
					$sql .= self::doCompile($tree["Arg"]["Left"]["Value"]["Name"], $tree["Arg"]["Left"]["Value"],true);
					break;
				
			}
			$sql .= ",";
		} else {
			
		}
		 
		if(isset($tree["Arg"]["Right"])) {
			switch($tree["Arg"]["Right"]["Type"])
			{
				case "ident":
				case "int_val":
				case "real_val":
					$sql .= $tree["Arg"]["Right"]["Value"];
					break;
				case "text_val":
					$sql .= '"'. $tree["Arg"]["Right"]["Value"] .'"';
					break;
				case "Flowcontrol":					
					$sql .= Sql_CompileFlow::doCompile($tree["Arg"]["Right"]["Value"]["Name"], $tree["Arg"]["Right"]["Value"],true);
					break;
				case "Function":					
					$sql .= self::doCompile($tree["Arg"]["Right"]["Value"]["Name"], $tree["Arg"]["Right"]["Value"],true);
					break;				
			}
		} else {
			
		}
		 
		$sql .= ")";
		if(isset($tree["Alias"])) {
			$sql .= " AS ". $tree["Alias"];
		}
		return $sql;
		
	}
	
	public static function processInfinite($tree, $recursing){}
	
	public static function processDistinctive($tree, $recursing){}
	
	
	public function compile($name, $tree, $recursing=false){
		return self::doCompile($name, $tree, $recursing);    	
    }
    
}

