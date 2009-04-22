<?php

class Sql_CompilerFlow {

	/**
	 * compileControlFlow
	 * @desc redirects to different control flow functions
	 * @param string $name name of control flow function 
	 * @param array $tree 
	 * @param bool $recursing optional 
	 */
	public static function doCompile($name, $tree, $recursing) {
		return call_user_func(array(__CLASS__,"compile".$name),$tree, $recursing);
	}
	
	/**
	 * compileCASE
	 * @desc compiles nested case control flow sets
	 * => control flow function CASE
	 * @param array $tree
	 * @param bool $recursing
	 * @return string
	 */	
	private function compileCASE($tree, $recursing=false){

		if(isset($tree["Function"])) {
			// if it is a nested function then recurse
			$column = self::doCompile($tree["Function"]["Name"],$tree["Function"], true);
		} 
		elseif(isset($tree["Name"])) 
		{

			if(is_array($tree["Arg"])) {
				$column = $tree["Name"] . " "; // function name	
				foreach($tree["Arg"] as $index => $value) {
					switch($value["Type"])
					{
						case 'real_val':
						case 'int_val':
						case 'null':
						case 'ident':
							$column .= $value["Value"]." ";
							break;
						case 'flowcontrol':
							$column .= strtoupper($value["Value"])." ";
							break;
						case 'text_val':
							$column .= $value["Value"]." ";
							break;
						case 'Subclause':
							$column .= Sql_Compiler::OPENBRACE
									. $this->reCompile($value["Subclause"])
									. Sql_Compiler::CLOSEBRACE." "
									;
							break;
					}
				}
				$column .= "END ";
			}
		}
		return $column;
	}
	
	/**
	 * compileIF
	 * @desc compiles nested if control flow sets
	 * => control flow function IF
	 * @param array $tree
	 * @param bool $recursing
	 * @return string
	 */	
	private static function compileIF($tree, $recursing=false){
		
		if(isset($tree["Function"])) {
			// if it is a nested function then recurse
			$column = self::doCompile($tree["Function"]["Name"],$tree["Function"], true);
		} 
		elseif(isset($tree["Name"])) 
		{
			if(is_array($tree["Arg"])) {
				$column = $tree["Name"] . Sql_Compiler::OPENBRACE; // function name	
				$implosion = implode (Sql_Compiler::COMMA, $tree);
				if(strstr(strtolower($implosion),'array')) { // check on error
					$funcTree = array();
					foreach($tree["Arg"] as $index => $value){
						if(count($value)==1){
							$valueNode = $value[0];
						}
						if(is_array($valueNode) && isset($valueNode["Function"]) && isset($valueNode["Function"]["Name"])) {
							$funcTree[] = self::doCompile($valueNode["Function"]["Name"], $valueNode, true);
						} else {
							if(is_array($value)) {
								$funcTree[] = implode("", $value);	
							} else {
								$funcTree[] = $value;
							}
						}
					}
					$column .= implode(Sql_Compiler::COMMA, $funcTree);
				} else {
					$column .= $implosion;									
				}
				$column .= Sql_Compiler::CLOSEBRACE;
			}
			
			// only top most function shall have an alias
			if (empty($recursing) and $tree['Alias'] != '') {
				$column .= Sql_Compiler::ALIAS . $tree['Alias'];
			}
		}
		return $column;		
	}

	
    public function compile($name, $tree, $recursing=false){
		return self::doCompile($name,$tree, $recursing);    	
    }

}

