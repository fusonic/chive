<?php

/**
 *
 * Sql_ParserSelect
 * @package Sql
 * @subpackage Sql_Parser
 * @author Thomas Schaefer
 * @since 30.11.2008 
 * @desc parses a sql select into object
 */

/**
 *
 * Sql_ParserSelect
 * @package Sql
 * @subpackage Sql_Parser
 * @author Thomas Sch&#65533;fer
 * @since 30.11.2008 07:49:30
 * @desc parses a sql select into object
 */
class Sql_ParserSelect {

	/**
	 * parse select
	 *
	 * @param bool $subSelect
	 * @return array
	 */
	public static function doParse($subSelect = false) 
	{
		$tree = array('Command' => 'select');

        $look = Sql_Object::lexer()->lookaheadToken();
        // safety line
        if(Sql_Object::token()=="select"){
            Sql_Parser::getTok();
        }

        switch($look) {
            case "all":
            case "distinct":
            case "distinctrow":
                $tree['Quantifier'] = Sql_Object::token();
                break;
        }

        $look = Sql_Object::lexer()->lookaheadToken();
        switch($look) {
            case Sql_Parser::isSelectedKeyword($look):
                while(Sql_Parser::isSelectedKeyword($look)) {
                    $look = Sql_Object::lexer()->lookaheadToken();
                    $tree["SelectOptions"][] = Sql_Object::token();
                    Sql_Parser::getTok();
                }
                break;
        }
        
		$tree = Sql_Parser::parseColumns($tree);
				
		if (Sql_Object::token() != 'from') {
			return Sql_Parser::raiseError('Expected "from"', __LINE__);
		}

		####################
				
		Sql_Parser::getTok();
		$tree['TableNames'] = array();
		
		while (Sql_Object::token() == 'ident') 
		{
		    $tableNameToken = Sql_Object::lexer()->tokText;
		    // added 2008-01-16
			if(strpos($tableNameToken,".")>0)
			{
			    $arrTableNameToken = explode(".",$tableNameToken);
				switch(count($arrTableNameToken)) {
				    case 2:
				        if(empty($tree['DatabaseNames'])) $tree['DatabaseNames'] = array();
        				$tree['DatabaseNames'][] = $arrTableNameToken[0];
        				$tree['TableNames'][] = $arrTableNameToken[1];
        			break;
        			default:
        			   $tree['TableNames'][] = Sql_Object::lexer()->tokText;
        				break;
        		}
        	} 
        	else 
        	{
    			$tree['TableNames'][] = Sql_Object::lexer()->tokText;
    		}
			
			Sql_Parser::getTok();
			
			if (Sql_Object::token() == 'ident') 
			{
				$tree['TableAliases'][] = Sql_Object::lexer()->tokText;
				Sql_Parser::getTok();
			} 
			elseif (Sql_Object::token() == 'as') 
			{
				Sql_Parser::getTok();
				if (Sql_Object::token() == 'ident') 
				{
					$tree['TableAliases'][] = Sql_Object::lexer()->tokText;
				} 
				else 
				{
					return Sql_Parser::raiseError('Expected table alias', __LINE__);
				}
				
				Sql_Parser::getTok();
				
			} else {
				$tree['TableAliases'][] = '';
			}
			
			if (Sql_Object::token() == 'on') 
			{
				$clause = Sql_Parser::parseSearchClause();
			
				if (Sql_Parser::isError($clause)) 
				{
					return $clause;
				}
				
				$tree['Joins'][] = $clause;
			} 
			else 
			{
				$tree['Joins'][] = '';
			}
			
			if (Sql_Object::token() == ',') 
			{
				$tree['Join'][] = ',';
				Sql_Parser::getTok();
			} 
			elseif (Sql_Object::token() == 'join') 
			{
				$tree['Join'][] = 'JOIN';
				Sql_Parser::getTok();
			} 
			elseif ((Sql_Object::token() == 'cross') or (Sql_Object::token() == 'inner')) 
			{
				$join = Sql_Object::lexer()->tokText;
				Sql_Parser::getTok();
				if (Sql_Object::token() != 'join') {
					return Sql_Parser::raiseError('Expected token "join"', __LINE__);
				}
				$tree['Join'][] = strtoupper($join).' JOIN';
			
				Sql_Parser::getTok();
			} 
			elseif ((Sql_Object::token() == 'left') or (Sql_Object::token() == 'right')) 
			{
				$join = Sql_Object::lexer()->tokText;
			
				Sql_Parser::getTok();
			
				if (Sql_Object::token() == 'join') 
				{
					$tree['Join'][] = strtoupper($join).' JOIN';
				} 
				elseif (Sql_Object::token() == 'outer') 
				{
					$join .= ' outer';
					Sql_Parser::getTok();
					if (Sql_Object::token() == 'join') {
						$tree['Join'][] = strtoupper($join).' JOIN';
					} else {
						return Sql_Parser::raiseError('Expected token "join"', __LINE__);
					}
				} 
				else 
				{
					return Sql_Parser::raiseError('Expected token "outer" or "join"', __LINE__);
				}
				
				Sql_Parser::getTok();
				
			} 
			elseif (Sql_Object::token() == 'natural') 
			{
				$join = Sql_Object::lexer()->tokText;
			
				Sql_Parser::getTok();
			
				if (Sql_Object::token() == 'join') 
				{
					$tree['Join'][] = strtoupper($join).' JOIN';
				} 
				elseif ((Sql_Object::token() == 'left') or (Sql_Object::token() == 'right')) 
				{
					$join .= ' '. Sql_Object::token();
					
					Sql_Parser::getTok();
					
					if (Sql_Object::token() == 'join') 
					{
						$tree['Join'][] = strtoupper($join).' JOIN';
					} 
					elseif (Sql_Object::token() == 'outer') 
					{
						$join .= ' '.Sql_Object::token();
						Sql_Parser::getTok();
						if (Sql_Object::token() == 'join') 
						{
							$tree['Join'][] = strtoupper($join).' JOIN';
						} 
						else 
						{
							return Sql_Parser::raiseError('Expected token "join" or "outer"', __LINE__);
						}
					} 
					else 
					{
						return Sql_Parser::raiseError('Expected token "join" or "outer"', __LINE__);
					}
				} 
				else 
				{
					return Sql_Parser::raiseError('Expected token "left", "right" or "join"', __LINE__);
				}
				
				Sql_Parser::getTok();
				
			} 
			elseif ((Sql_Object::token() == 'where') or
			(Sql_Object::token() == 'order') or
			(Sql_Object::token() == 'limit') or
			(is_null(Sql_Object::token()))) 
			{
				break;
			}
		}
		
		###############################
		while (
			!is_null(Sql_Object::token()) and 
			(!$subSelect or Sql_Object::token() != Sql_Parser::OPENBRACE) and 
			Sql_Object::token() != Sql_Parser::CLOSEBRACE and 
			Sql_Object::token() != Sql_Parser::SEMICOLON
		) {
			
			switch (Sql_Object::token()) 
			{
				case 'where':
					$clause = Sql_Parser::parseSearchClause();
					
					if (Sql_Parser::isError($clause)) 
					{
						return $clause;
					}
					
					$tree['Where'] = $clause;
					
					break;
				case 'order':
					
					Sql_Parser::getTok();
					
					if (Sql_Object::token() != 'by') 
					{
						return Sql_Parser::raiseError('Expected "by"', __LINE__);
					}
					
					Sql_Parser::getTok();
					
					while (Sql_Object::token() == 'ident') 
					{
						$col = Sql_Object::lexer()->tokText;
						
						Sql_Parser::getTok();
						
						if (Sql_Object::has("synonyms.".Sql_Object::token())) 
						{
							$order = Sql_Object::get("synonyms.".Sql_Object::token());
							if (($order != 'asc') && ($order != 'desc')) 
							{
								return Sql_Parser::raiseError('Unexpected token', __LINE__);
							}
							Sql_Parser::getTok();
						} 
						else 
						{
							$order = 'ASC';
						}
						
						if (Sql_Object::token() == ',') 
						{
							Sql_Parser::getTok();
						}
						
						$tree['SortOrder'][$col] = strtoupper($order);
					}
					break;
				case 'limit':
					
					Sql_Parser::getTok();
					
					if (Sql_Object::token() != 'int_val') 
					{
						return Sql_Parser::raiseError('Expected an integer value', __LINE__);
					}
					
					$length = Sql_Object::lexer()->tokText;
					$start = 0;
					
					Sql_Parser::getTok();
					
					if (Sql_Object::token() == ',') 
					{
						Sql_Parser::getTok();
					
						if (Sql_Object::token() != 'int_val') 
						{
							return Sql_Parser::raiseError('Expected an integer value', __LINE__);
						}
					
						$start = $length;
						$length = Sql_Object::lexer()->tokText;
					
						Sql_Parser::getTok();
					
					}
					$tree['Limit'] = array('Start'=>$start, 'Length'=>$length);
					break;
				case 'group':
					
					Sql_Parser::getTok();
					
					if (Sql_Object::token() != 'by') 
					{
						return Sql_Parser::raiseError('Expected "by"', __LINE__);
					}
					
					Sql_Parser::getTok();
					
					while (Sql_Object::token() == 'ident') 
					{
						$col = Sql_Object::lexer()->tokText;
					
						Sql_Parser::getTok();
					
						if (Sql_Object::token() == ',') 
						{
							Sql_Parser::getTok();
						}
						
						$tree['GroupBy'][] = $col;
						
					}
					break;
				case 'having':
					
					$clause = Sql_Parser::parseSearchClause();
					
					if (Sql_Parser::isError($clause)) 
					{
						return $clause;
					}
					
					$tree['Having'] = $clause;
					
					break;
				default:
					return Sql_Parser::raiseError('Unexpected clause', __LINE__);
			}
		}
        
        
        // added due to Alireza Eliaderani's mail from 2008-01-16 
        if(isset($tree['ColumnTables']) and count($tree['ColumnTables']) ) 
        {
            $tree['ColumnTableAliases'] = array();
    		foreach($tree['ColumnTables'] as &$colTbls){
    			if(in_array($colTbls,$tree['TableAliases'])){
    				$tree['ColumnTableAliases'][]=$colTbls;
    				$index=array_search($colTbls,$tree['TableAliases']);
    				$colTbls=$tree['TableNames'][$index];
    			}else{
    				$tree['ColumnTableAliases'][]="";
    			}
    		}    		
    		
        }
		return $tree;
	}

	public static function parse(){
		return self::doParse();
	}
}

