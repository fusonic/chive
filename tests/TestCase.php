<?php

require_once('PHPUnit/Framework.php');

class TestCase extends PHPUnit_Framework_TestCase
{

	protected function executeSqlFile($file)
	{
		echo exec('mysql -h' . DB_HOST . ' -u' . DB_USER . (DB_PASSWORD ? ' -p' . DB_PASSWORD : '') . ' --default-character-set=utf8 ' . DB_NAME . '<"' . $file . '"');
	}

}

?>