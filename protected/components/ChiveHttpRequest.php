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


class ChiveHttpRequest extends CHttpRequest
{
	
	/**
	 * @see CHttpRequest::normalizeRequest()
	 */
	protected function normalizeRequest()
	{
		$this->normalizeEOL($_POST);
		$this->normalizeEOL($_GET);
		$this->normalizeEOL($_REQUEST);
		
		parent::normalizeRequest();
	}
	
	/**
	 * Normalizes all EOL types to LFs in all $data strings
	 * @param	mixed				$data				any type, is changed directly 
	 * 													instead of returning a value
	 */
	protected function normalizeEOL(&$data) 
	{
		if(is_array($data) || is_object($data))
		{
			foreach($data as &$var)
			{
				$this->normalizeEOL($var);
			}
		}
		elseif(is_string($data))
		{
			$data = str_replace("\r", "\n", str_replace("\r\n", "\n", $data));
		} 
	}
	
}