<?php

// Create phar archive
$phar = new Phar("chive_" . @$argv[1] . ".phar.php");

// Load locales
$locales = glob("chive/protected/messages/*", GLOB_ONLYDIR);
foreach($locales as &$locale)
{
    $locale = basename($locale);
}

// Delete locales we don't need
$yiiLocales = glob("chive/yii/i18n/data/*.php");
foreach($yiiLocales as $locale)
{
    $localeName = pathinfo($locale, PATHINFO_FILENAME);
    if(!in_array($localeName, $locales))
    {
        unlink($locale);
    }
}

// Remove some yii components we don't need
exec("rm chive/yii/gii -rf");
exec("rm chive/yii/cli -rf");
exec("rm chive/yii/console -rf");
exec("rm chive/yii/db/schema/mssql -rf");
exec("rm chive/yii/db/schema/oci -rf");
exec("rm chive/yii/db/schema/pgsql -rf");
exec("rm chive/yii/db/schema/sqlite -rf");
exec("rm chive/yii/messages/?? -rf");
exec("rm chive/yii/messages/??_?? -rf");
exec("rm chive/yii/test -rf");
exec("rm chive/yii/vendors/htmlpurifier -rf");
exec("rm chive/yii/vendors/TextHighlighter -rf");
exec("rm chive/yii/views/?? -rf");
exec("rm chive/yii/views/??_?? -rf");
exec("rm chive/yii/web/js/source/autocomplete -rf");
exec("rm chive/yii/web/js/source/jui -rf");
exec("rm chive/yii/web/js/source/rating -rf");
exec("rm chive/yii/web/js/source/treeview -rf");
exec("rm chive/yii/web/js/source/yiitab -rf");
exec("rm chive/yii/web/js/source/jquery.js");
exec("rm chive/yii/web/widgets/captcha -rf");
exec("rm chive/yii/zii -rf");

// Add all files
$sourceDir = __DIR__ . "/chive";
$i = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceDir), RecursiveIteratorIterator::SELF_FIRST);
foreach($i as $file)
{
	$basename = basename($file);
	if($basename{0} == ".")
	{
		continue;
	}

	$targetPath = substr($file, strlen($sourceDir) + 1);

	if(is_dir($file))
	{
		$phar->addEmptyDir($targetPath);
	}
	elseif(!substr_compare($file, ".php", -4))
	{
		// Get content
		echo "- Adding PHP " . $file . "\n";
		$content = php_strip_whitespace($file);
		$content = str_replace("realpath(", "ltrim(", $content);
		file_put_contents($file, $content);
		$phar->addFile($file, $targetPath);
	}
	elseif(!substr_compare($file, ".js", -3))
	{
		echo "- Adding JS " . $file . "\n";
		exec("./jsmin <" . $file . " >" . $file . ".min");
		unlink($file);
		$phar->addFile($file . ".min", $targetPath);
	}
	/*
	elseif(!substr_compare($file, ".css", -4))
	{
		echo "- Adding CSS " . $file . "\n";
		exec("./jsmin <" . $file . " >" . $file . ".min");
		unlink($file);
		$phar->addFile($file . ".min", $targetPath);
	}
	*/
	elseif(!substr_compare($file, ".xml", -4) && strpos($file, $sourceDir . "/protected/messages/") === 0)
	{
		echo "- Adding XML " . $file . "\n";
		$content = gzcompress(file_get_contents($file), 9);
		unlink($file);
		file_put_contents($file . ".gz", $content);
		$phar->addFile($file . ".gz", $targetPath . ".gz");
	}
	else
	{
		echo "- Adding " . $file . "\n";
		$phar->addFile($file, $targetPath);
	}
}

$stub = <<<LONG
<?php
Phar::webPhar();
__HALT_COMPILER();
LONG;
	
$phar->setStub($stub);