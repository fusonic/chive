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


define('YII_ENABLE_AUTOLOAD',false);
define('YII_ENABLE_EXCEPTION_HANDLER',false);
define('YII_ENABLE_ERROR_HANDLER',false);

require_once('global.php');
require_once('YiiBase.php');

class Yii extends YiiBase
{
	private static $_testApp;

	public static function app()
	{
		return self::$_testApp;
	}

	public static function setApplication($app)
	{
		self::$_testApp=$app;
		parent::setApplication($app);
	}

	/*public static function setPathOfAlias($alias,$path)
	{
		if(empty($path))
			unset(self::$_aliases[$alias]);
		else
			self::$_aliases[$alias]=rtrim($path,'\\/');
	}*/
}

require_once(dirname(__FILE__).'/TestWebApplication.php');
require_once(dirname(__FILE__).'/TestApplication.php');