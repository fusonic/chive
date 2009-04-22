<?php

require_once(dirname(__FILE__) . '/models/DataType.php');

$types = json_encode(DataType::$types);

$file = file_get_contents(dirname(__FILE__) . '/../js/dataType.js');

$file = preg_replace('/types: .+?$/m', 'types: ' . $types . ',', $file);

file_put_contents(dirname(__FILE__) . '/../js/dataType.js', $file);

?>