<?php

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