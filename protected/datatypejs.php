<?php

require_once(dirname(__FILE__) . '/models/DataType.php');
require_once(dirname(__FILE__) . '/models/StorageEngine.php');

// Data types
$types = json_encode(DataType::$types);
$file = file_get_contents(dirname(__FILE__) . '/../js/dataType.js');
$file = preg_replace('/types: .+?$/m', 'types: ' . $types . ',', $file);
file_put_contents(dirname(__FILE__) . '/../js/dataType.js', $file);

// Storage engines
$engines = json_encode(StorageEngine::$engines);
$file = file_get_contents(dirname(__FILE__) . '/../js/storageEngine.js');
$file = preg_replace('/engines: .+?$/m', 'engines: ' . $engines . ',', $file);
file_put_contents(dirname(__FILE__) . '/../js/storageEngine.js', $file);

?>