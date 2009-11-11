<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
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


Yii::import('system.web.CApplication');

class TestApplication extends CApplication
{
	public function processRequest()
	{
	}

	/**
	 * Removes all runtime files.
	 */
	public function reset()
	{
		$runtimePath=$this->getRuntimePath();
		if(is_dir($runtimePath) && ($folder=@opendir($runtimePath))!==false)
		{
			while($entry=@readdir($folder))
			{
				if($entry==='.' || $entry==='..')
					continue;
				$path=$runtimePath.DIRECTORY_SEPARATOR.$entry;
				@unlink($path);
			}
			@closedir($folder);
		}
	}

	public function loadGlobalState()
	{
		parent::loadGlobalState();
	}

	public function saveGlobalState()
	{
		parent::saveGlobalState();
	}
}