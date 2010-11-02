<?php

class SqlUtil
{
	public static function FixTable(&$table) 
	{
		foreach($table as &$row) {
			self::FixRow($row);
		}
	}
	
	public static function FixRow(&$row) 
	{
	    if ($row) 
	    {
	    	foreach ($row as &$value) 
	    	{
	    		self::FixValue($value);
	    	}
	    }
	}
	 
	 public static function FixValue(&$value)
	 {
		if (is_string($value))
		{
			if (preg_match('/^\d+\.\d+$/', $value)) 
			{ 
				$value = (string)(float)$value;
			}
			elseif (strlen($value) == 1 && ($asciiValue = ord($value)) < 2) 
			{
				$value = (int)(bool)$asciiValue;
			}
			else 
			{
				$value = rtrim($value, chr(0)); // for char data type
			}
		}		      
	 }
}