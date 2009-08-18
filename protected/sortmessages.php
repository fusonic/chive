<?php

$xml = new SimpleXmlElement(file_get_contents('messages/en/core.xml'));
$data = array();
foreach($xml->entry AS $entry)
{
	$data[(string)$entry['id']] = (string)$entry;
}

$keys = array_keys($data);
sort($keys);

$newXml = new SimpleXmlElement('<category id="core"></category>');
foreach($keys AS $key)
{
	$entry = $newXml->addChild("entry", $data[$key]);
	$entry->addAttribute('id', $key);
}

$doc = new DOMDocument('1.0');
$doc->preserveWhiteSpace = false;
$doc->loadXML($newXml->asXML());
$doc->formatOutput = true;
file_put_contents('messages/en/core.xml',  str_replace('  <entry', '	<entry', $doc->saveXML()));

?>