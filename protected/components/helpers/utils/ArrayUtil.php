<?php

class ArrayUtil 
{
	
	public static function toJavaScriptObject($_array)
	{
		$return = '{';
		
		if(is_array($_array))
		{
			$count = count($_array);
			
			foreach($_array AS $key => $value) 
			{
				
				if(is_null($value)) 
				{
					$return .= $key . ': null';
				}
				else
				{
					$return .= $key . ':\'' . $value . '\'';
				}
				
				$count--; 			
				
				if($count > 0)
					$return .= ',';
				
			}
			
		}
		else
		{
			if(is_numeric($_array))
				$return .= $_array;
			else
				$return .= "'" . $_array . "'";
		}
		
		$return .= '}';
		
		return $return;
	}
	
}

?>