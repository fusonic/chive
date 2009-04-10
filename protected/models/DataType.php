<?php

class DataType
{

	public static function supportsCollation($dataType)
	{
		if(in_array($dataType, array('char', 'varchar', 'smalltext', 'text', 'mediumtext', 'longtext', 'enum', 'set')))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

}

?>