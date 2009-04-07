<?php

require_once('PHPUnit/Framework.php');

class TestCase extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		$app = new TestWebApplication(dirname(__FILE__) . '/../protected/config/tests.php');
		parent::setUp();
	}

	public function executeSqlFile($file)
	{
		//echo 'cat "' . $file . '">`mysql -h' . DB_HOST . ' -u' . DB_USER . (DB_PASSWORD ? ' -p' . DB_PASSWORD : '') . ' ' . DB_NAME . '`';
		echo exec('mysql -h' . DB_HOST . ' -u' . DB_USER . (DB_PASSWORD ? ' -p' . DB_PASSWORD : '') . ' --default-character-set=utf8 ' . DB_NAME . '<"' . $file . '"');
	}

}

?>