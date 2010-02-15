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


class FileUtil 
{

	public static final function readDirectory($_path, $_recursively = false, $_type = false, $_readFilesize = false) 
	{
		$dir = dir($_path);
		$data = array();

		while($file = $dir->read()) 
		{
			if($file == "." || $file == ".." || $file == ".svn")
			{
				continue;
			}

			if($_type != "dir" && is_file($_path . "/" . $file)) 
			{
				if(!$_readFilesize)
				{
					$data[] = $_path . "/" . $file;
				}
				else 
				{
					$data[] = array (
						"name" => $_path . "/" . $file,
						"filesize" => filesize($_path . "/" . $file)
					);
				}
			}
			elseif(is_dir($_path . "/" . $file)) 
			{
				if($_type != "file")
				{
					$data[] = $_path . "/" . $file;
				}

				if($_recursively)
				{
					$data = array_merge(self::readDirectory($_path . "/" . $file, $_recursively, $_type, $_readFilesize), $data);
				}
			}
		}

		return $data;
	}

}