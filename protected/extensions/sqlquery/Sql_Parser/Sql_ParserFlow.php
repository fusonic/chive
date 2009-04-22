<?php

/**
 *
 * Sql_ParserFlow
 * @package Sql
 * @subpackage Sql_Flow
 * @author Thomas Schaefer
 * @since 30.11.2008
 * @desc parses a sql flow control statement into object
 */
class Sql_ParserFlow {

	/**
	 * parseControlFlowOpts
	 * @desc parses a flow control function into tree
	 * @param bool $recursing
	 * @return array
	 */
	public function doParse($recursing=false)
	{
		$hasAlias = true;
		$hasBraces = true;
		
		$opts = array();
		$function = strtoupper( Sql_Object::token() );
		$opts['Name'] = $function;

		switch($function) {
			case "CASE":
				$hasAlias = false;
				$hasBraces = false;
				break;
			default:			
				Sql_Parser::getTok();
				break;
		}

		if ($hasBraces==true and Sql_Object::token() != Sql_Parser::OPENBRACE) {
			return Sql_Parser::raiseError('Expected "("', __LINE__);
		}
		
		switch (strtolower( $function ) ) {
			case 'case':
				$opts["Bridge"]=true;
				Sql_Parser::getTok();
				$i=0;
				while (Sql_Object::token() != "end") {
					switch(Sql_Object::token()) {
						case 'real_val':
						case 'int_val':
						case 'null':
							$opts["Arg"][$i]["Value"] = Sql_Object::lexer()->tokText;
							$opts["Arg"][$i]["Type"] = Sql_Object::token();
							break;
						case 'text_val':
							$opts["Arg"][$i]["Value"] = "'".Sql_Object::lexer()->tokText."'";
							$opts["Arg"][$i]["Type"] = Sql_Object::token();
							break;
						default: 
							if(Sql_Parser::isControlFlowFunction()){
								$opts["Arg"][$i]["Type"] = "flowcontrol";
							} elseif(Sql_Object::token()=="(") {
								Sql_Parser::getTok();
								switch(Sql_Object::token())
								{
									case "select":
										$opts["Arg"][$i]["Type"] = "Subclause";
										$opts["Arg"][$i]["Subclause"] = Sql_ParserSelect::parse(true);
									break;
								}
							} else {
								$opts["Arg"][$i]["Type"] = "ident";
							}
							$opts["Arg"][$i]["Value"] = Sql_Object::lexer()->tokText;
							break;
					}
					$i++;
					Sql_Parser::getTok();
				}
				if(Sql_Object::token()=="end"){
					Sql_Parser::getTok();
				}
				Sql_Object::lexer()->pushBack();
			break;
			case 'if':
				Sql_Parser::getTok();
				$increment = 0;
				while (Sql_Object::token() != Sql_Parser::CLOSEBRACE) {
					switch (Sql_Object::token()) {
						case Sql_Parser::isControlFlowFunction():
							$recurseOpts = array();
							$recurseOpts['Function'] = self::parseControlFlowOpts(true);
							$opts['Arg'][$increment][] = $recurseOpts;
							break;
						case Sql_Parser::isOperator():
						case 'real_val':
						case 'int_val':
						case 'ident':
						case 'null':
							$opts['Arg'][$increment][] = Sql_Object::lexer()->tokText;
							break;
						case 'text_val':
							$opts['Arg'][$increment][] = "'".Sql_Object::lexer()->tokText."'";
							break;
						case ',':
							$increment++;
							// do increment
							break;
						default:
							return Sql_Parser::raiseError('Expected a string or a column name', __LINE__);
					}
					Sql_Parser::getTok();
				}
				Sql_Object::lexer()->pushBack();
				break;			
			break;
			default:
				Sql_Parser::getTok();
				$opts['Arg'] = Sql_Object::lexer()->tokText;
				break;
		}

		if($hasBraces==true) {
			Sql_Parser::getTok();
			if (Sql_Object::token() != Sql_Parser::CLOSEBRACE) {
				return Sql_Parser::raiseError('Expected ")"', __LINE__);
			}
		}

		if(empty($recursing) and $hasAlias == true) {
			$opts = Sql_Parser::processAlias($opts);
		}
		
		return $opts;
	}

    public function parse($recursing=false){
		return self::doParse($recursing);    	
    }


	private function parseControlFlowOpts($recursing=false)
	{
		$hasAlias = true;
		$hasBraces = true;
		
		$opts = array();
		$function = strtoupper( Sql_Object::token() );
		$opts['Name'] = $function;

		switch($function) {
			case "CASE":
				$hasAlias = false;
				$hasBraces = false;
				break;
			default:			
				Sql_Parser::getTok();
				break;
		}

		if ($hasBraces==true and Sql_Object::token() != Sql_Parser::OPENBRACE) {
			return Sql_Parser::raiseError('Expected "("');
		}
		
		switch (strtolower( $function ) ) {
			case 'case':
				$opts["Bridge"]=true;
				Sql_Parser::getTok();
				$i=0;
				while (Sql_Object::token() != "end") {
					switch(Sql_Object::token()) {
						case 'real_val':
						case 'int_val':
						case 'null':
							$opts["Arg"][$i]["Value"] = Sql_Object::lexer()->tokText;
							$opts["Arg"][$i]["Type"] = Sql_Object::token();
							break;
						case 'text_val':
							$opts["Arg"][$i]["Value"] = "'".Sql_Object::lexer()->tokText."'";
							$opts["Arg"][$i]["Type"] = Sql_Object::token();
							break;
						default: 
							if(Sql_Parser::isControlFlowFunction()){
								$opts["Arg"][$i]["Type"] = "flowcontrol";
							} elseif(Sql_Object::token()=="(") {
								Sql_Parser::getTok();
								switch(Sql_Object::token())
								{
									case "select":
										$opts["Arg"][$i]["Type"] = "Subclause";
										$opts["Arg"][$i]["Subclause"] = Sql_ParserSelect::doParse(true);
									break;
								}
							} else {
								$opts["Arg"][$i]["Type"] = "ident";
							}
							$opts["Arg"][$i]["Value"] = Sql_Object::lexer()->tokText;
							break;
					}
					$i++;
					Sql_Parser::getTok();
				}
				if(Sql_Object::token()=="end"){
					Sql_Parser::getTok();
				}
				Sql_Object::lexer()->pushBack();
			break;
			case 'if':
				Sql_Parser::getTok();
				$increment = 0;
				while (Sql_Object::token() != Sql_Parser::CLOSEBRACE) {
					switch (Sql_Object::token()) {
						case Sql_Parser::isControlFlowFunction():
							$recurseOpts = array();
							$recurseOpts['Function'] = self::parseControlFlowOpts(true);
							$opts['Arg'][$increment][] = $recurseOpts;
							break;
						case Sql_Parser::isOperator():
						case 'real_val':
						case 'int_val':
						case 'ident':
						case 'null':
							$opts['Arg'][$increment][] = Sql_Object::lexer()->tokText;
							break;
						case 'text_val':
							$opts['Arg'][$increment][] = "'".Sql_Object::lexer()->tokText."'";
							break;
						case ',':
							$increment++;
							// do increment
							break;
						default:
							return Sql_Parser::raiseError('Expected a string or a column name');
					}
					Sql_Parser::getTok();
				}
				Sql_Object::lexer()->pushBack();
				break;			
			break;
			default:
				Sql_Parser::getTok();
				$opts['Arg'] = Sql_Object::lexer()->tokText;
				break;
		}

		if($hasBraces==true) {
			Sql_Parser::getTok();
			if (Sql_Object::token() != Sql_Parser::CLOSEBRACE) {
				return Sql_Parser::raiseError('Expected ")"');
			}
		}

		if(empty($recursing) and $hasAlias == true) {
			$opts = Sql_Parser::processAlias($opts);
		}
		
		return $opts;
	}

}

