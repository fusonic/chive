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


require_once('yii.php');
require_once('PHPUnit/Framework.php');

class DublinTests extends PHPUnit_Framework_TestSuite
{

	public static $app;

	public static function suite()
	{
		$suite = new DublinTests();

		// Add tests
		$files = self::rglob('*/*.php');
		foreach($files AS $file)
		{
			require_once($file);
			$info = pathinfo($file);
			$class = $info['filename'] . 'Test';
			eval('$enabled = ' . $class . '::$enabled;');
			if($enabled)
			{
				$suite->addTestSuite($class);
			}
		}

		return $suite;
	}


	public static function rglob($pattern='*', $flags = 0, $path='')
	{
	    $paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
	    $files=glob($path.$pattern, $flags);
	    foreach ($paths as $path) { $files=array_merge($files,self::rglob($pattern, $flags, $path)); }
	    return $files;
	}

	protected function setUp()
	{
		self::$app = new TestWebApplication(dirname(__FILE__) . '/../protected/config/tests.php');
		self::$app->setLanguage('en');
	}

}

?>