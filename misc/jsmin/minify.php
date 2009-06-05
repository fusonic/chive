<?php
include 'jsmin-1.1.1.php';


$dir = '/var/www/dublin/trunk/js/jquery';
$handler = opendir($dir);

$mergedJs = '';

while($file = readdir($handler))
{
	if($file == '.' || $file == '..')
		continue;

	echo 'reading ' . $dir . '/' . $file . '<br/>';

	$mergedJs .= "\n\n\n\n" . '/* ' . $file . ' */' . "\n\n";
	$mergedJs .= JSMin::minify(file_get_contents($dir. '/' . $file));
}

closedir($handler);

// Write to file
file_put_contents($dir . '/all.js', $mergedJs);

?>