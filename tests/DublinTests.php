<?php

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
			$suite->addTestSuite($info['filename'] . 'Test');
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
	}

}

?>