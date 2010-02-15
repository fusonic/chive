<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2010 Fusonic GmbH
 *
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */


class ConfigUtil 
{
	
	/**
	 * Checks if allow_url_fopen is on or off
	 *
	 * @return	bool
	 */
	public static function getUrlFopen() 
	{
		if(ini_get("allow_url_fopen"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Returns the maximum filesize to upload
	 *
	 * @param	bool		return as formatted string
	 * @return	mixed
	 */
	public static function getMaxUploadSize($_asString = false, $_value = null) 
	{
		$maxUpload = ConfigUtil::ini2bytes(ini_get("upload_max_filesize"));
		$maxPost = ConfigUtil::ini2bytes(ini_get("post_max_size"));
		
		if($_value != null) 
		{
			if($_value < $maxUpload)
			{
				$maxUpload = $_value;
			}
			if($_value < $maxPost)
			{
				$maxPost = $_value;
			}
		}
		
		if($maxPost < $maxUpload)
		{
			$maxUpload = $maxPost;
		}
			
		if($_asString)
		{
			return Formatter::fileSize($maxUpload);
		}
		else
		{
			return $maxUpload;
		}
	}
	
	public static function ini2bytes($_value) 
	{
	   $_value = trim($_value);
	   $last = strtolower(substr($_value, strlen($_value) - 1));

	   switch($last) 
	   {
	       case 'g':
	           $_value *= 1024;
	       case 'm':
	           $_value *= 1024;
	       case 'k':
	           $_value *= 1024;
	   }

	   return (int)$_value;
	}
	
}