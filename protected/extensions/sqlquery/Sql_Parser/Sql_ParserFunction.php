<?php

/**
 *
 * Sql_ParserFunction
 * @package Sql
 * @subpackage Sql_Compiler
 * @author Thomas Sch&#65533;fer
 * @since 30.11.2008 07:49:30
 * @desc parses a sql function into array
 */
class Sql_ParserFunction {
	
	const BITSHIFT =  "<<";
	
	public static function doParse($recursing=false)
	{
		$opts = array();
		$function = strtoupper( Sql_Object::token() );
		$opts['Name'] = $function;

		Sql_Parser::getTok();

		if (Sql_Object::token() != Sql_Parser::OPENBRACE) 
		{
			return Sql_Parser::raiseError('Expected "("', __LINE__);
		}
		
		switch (strtolower( $function ) ) 
		{
			// single argument functions
			case 'bit_count':
			case 'bit_or':
			case 'bit_and':
			case "pi":
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
			case 'ln':
			case 'max':
			case 'min':
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
				$opts = self::processSingle($opts, "int_val");
				if(isset($opts["error"])) {
					return $opts["result"];
				}						
			break;
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
			case "rand":
			case 'reverse':
			case 'rtrim':
			case 'soundex':
			case 'space':
			case 'ucase':
			case 'unhex':
			case 'upper':
				$opts = self::processSingle($opts, "text_val");
				if(isset($opts["error"])) {
					return $opts["result"];
				}						
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
				$opts = self::processDouble($opts);
				if(isset($opts["error"])) {
					return $opts["result"];
				}						
			break;
			// special argument function => distinctive
			case 'count':
				$opts = self::processDistinctive($opts);
				if(isset($opts["error"])) {
					return $opts["result"];
				}						
			break;
			// infinite argument functions
			case 'concat':
			case 'concat_ws':
			case 'make_set':
			case 'elt':
				$opts = self::processInfinite($opts);
				if(isset($opts["error"])) {
					return $opts["result"];
				}
				break;
			default:
				// other
				Sql_Parser::getTok();
				$opts['Arg'] = Sql_Object::lexer()->tokText;
				break;
		}
		
		Sql_Parser::getTok();
		if (Sql_Object::token() != Sql_Parser::CLOSEBRACE) 
		{
			// works for single bit shifted functions
			if(Sql_Object::token()==self::BITSHIFT) {
				$opts['Arg']["Left"]["Value"] = $opts['Arg'][0];
				$opts['Arg']["Left"]["Type"] = "int_val";
				unset($opts['Arg'][0]);
				$opts['Arg']["Op"] = '<<';
				Sql_Parser::getTok();
				$opts['Arg']["Right"]["Value"] = Sql_Object::token();
				$opts['Arg']["Right"]["Type"] = "ident";
				$opts['process'] = "text_val";
				Sql_Parser::getTok();
			} else {
				return Sql_Parser::raiseError('Expected ")"', __LINE__);
			}
		}

		if(empty($recursing)) 
		{
			$opts = Sql_Parser::processAlias($opts);
		}
		
		return $opts;
	}
	
	private static function processInfinite($opts) {

		Sql_Parser::getTok();
		$increment=0;
		while (Sql_Object::token() != Sql_Parser::CLOSEBRACE) 
		{
			switch (Sql_Object::token()) 
			{
				case 'ident':
					$opts['Arg'][$increment]["Value"] = Sql_Object::lexer()->tokText;
					$opts['Arg'][$increment]["Type"] = Sql_Object::token();
					break;
				case 'text_val':
					$opts['Arg'][$increment]["Value"] = '"'.Sql_Object::lexer()->tokText.'"';
					$opts['Arg'][$increment]["Type"] = Sql_Object::token();
					break;
				case 'real_val':
				case 'int_val':
					$opts['Arg'][$increment]["Value"] = Sql_Object::lexer()->tokText;
					$opts['Arg'][$increment]["Type"] = Sql_Object::token();
					break;
				case ',':
					// do nothing
					$increment++;
					break;
				default:
					return array("error" => true, "result" => Sql_Parser::raiseError('Expected a string or a column name', __LINE__));
			}
			Sql_Parser::getTok();
		}
		
		Sql_Object::lexer()->pushBack();
		
		return $opts;		
	}
    
    private static function processDistinctive($opts){
		Sql_Parser::getTok();
		$increment=0;
		switch (Sql_Object::token()) 
		{
			case 'distinct':
				$opts['Distinct'] = true;

				Sql_Parser::getTok();

				if (Sql_Object::token() != 'ident') 
				{
					return array("error" => true, "result" => Sql_Parser::raiseError('Expected a column name', __LINE__));
				}
				
			case 'ident': 
			case '*':
				$opts['Arg'][$increment]["Value"] = Sql_Object::lexer()->tokText;
				$opts['Arg'][$increment]["Type"] = Sql_Object::token();
				break;
			default:
				return array("error" => true, "result" => Sql_Parser::raiseError('Invalid argument', __LINE__));
		}
		return $opts;	    	
    }

	/**
	 * processEmpty
	 * @desc processes sql functions which have no argument
	 * @return opts
	 */ 
    private static function processEmpty($opts){

		Sql_Parser::getTok();
		
		if(Sql_Object::token()==Sql_Parser::CLOSEBRACE) {
			$opts['Arg'] = false;
			Sql_Object::lexer()->pushBack();
		} else {
			return array("error" => true, "result" => Sql_Parser::raiseError('Invalid argument', __LINE__));
		}
		return $opts;	    	
    }
    
    /**
     * processSingle
     * @desc single argument sql function
     * @param array $opts option data of sql function part
     * @return array
     */
    private static function processSingle($opts){

		Sql_Parser::getTok();
		
		if(Sql_Object::token()!=Sql_Parser::CLOSEBRACE) 
		{
			if(Sql_Parser::isFunc()) {
				$opts['Arg'][]['Function'][0] = self::doParse(true);
			} else {
				switch (Sql_Object::token()) 
				{
					case 'ident': 
					case 'int_val':
					case 'real_val':
						$opts['Arg'][] = Sql_Object::lexer()->tokText;
						break;
					case 'text_val':
						$opts['Arg'][] = '"'. Sql_Object::lexer()->tokText.'"';
						break;
					default:
						return array("error" => true, "result" => Sql_Parser::raiseError('Invalid argument', __LINE__));
				}
			}
		} else {
			$opts["Arg"] = false;			
			Sql_Object::lexer()->pushBack();
		}
		
		return $opts;	    	
    }
    
    /**
     * processDouble
     * @desc double argument sql function
     * @param array $opts option data of sql function part
     * @param string $prcType int_val, text_val, real_val
     * @return array
     */
    private static function processDouble($opts, $prcType="text_val"){

		Sql_Parser::getTok();
		$increment = 0;
		while (Sql_Object::token() != Sql_Parser::CLOSEBRACE) {
			$position = ($increment==0) ? "Left" : "Right";
			switch (Sql_Object::token()) 
			{
				case Sql_Parser::isControlFlowFunction():
					$recurseOpts = array();
					$recurseOpts['Function'] = Sql_ParserFlow::parse(true);
					$opts['Arg'][$position]["Value"] = $recurseOpts;
					$opts['Arg'][$position]["Type"] = "Flowcontrol";
					break;
				case Sql_Parser::isFunc():
					$recurseOpts = array();
					$recurseOpts['Function'] = Sql_ParserFunction::parse(true);
					$opts['Arg'][$position]["Value"] = $recurseOpts;
					$opts['Arg'][$position]["Type"] = "Function";
					break;
				case 'ident': 
				case 'int_val':
				case 'real_val':
					$opts['Arg'][$position]["Value"] = Sql_Object::lexer()->tokText;
					$opts['Arg'][$position]["Type"] = Sql_Object::token();
					break;
				case ',':
					$increment++;
					break;
				case 'text_val':
					$opts['Arg'][$position]["Value"] = '"'. Sql_Object::lexer()->tokText.'"';
					$opts['Arg'][$position]["Type"] = Sql_Object::token();
					break;
				default:
					$errString = 'Invalid argument for '. $prcType." process";
					return array("error" => true, "result" => Sql_Parser::raiseError($errString, __LINE__));
			}
			Sql_Parser::getTok();
		}

		Sql_Object::lexer()->pushBack();

		return $opts;	    	
    }
    
    public function parse($recursing=false){
		return self::doParse($recursing);    	
    }
    
}

