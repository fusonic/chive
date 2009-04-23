<?php

// change the following paths if necessary
$yii='yii/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

function pre($_value) {
	echo "<pre>";
	print_r($_value);
	echo "</pre>";
}

function predie($_value) {
	pre($_value);
	Yii::app()->end();
}

function toCamelcase($lower_case_and_underscored_word) {
	$replace = str_replace(" ", "", ucwords(str_replace("_", " ", $lower_case_and_underscored_word)));
	return $replace;
}
function toUnderscore($camel_cased_word = null) {
	$tmp = _replace($camel_cased_word, array (
		'/([A-Z]+)([A-Z][a-z])/' => '\\1_\\2',
		'/([a-z\d])([A-Z])/' => '\\1_\\2'
	));
	return $tmp;
}
function _replace($search, $replacePairs) {
	return preg_replace(array_keys($replacePairs), array_values($replacePairs), $search);
}
function pdbg($data, $color="orange", $Line=null, $File=null, $height=180, $width=800, $textcolor="#000000") {
	$dbg = debug_backtrace();
	print "<div style=\"width:".$width."px;float:left;margin:5px\">";
	print "<div style=\"border:1px solid #999;font-size:11px;\">";
	print "<div style=\"font-family:arial,helvetica;background-color:".$color.";color:".$textcolor.";padding:2px 5px;font-weight:bold;border-bottom:1px solid #999;\">";
	if(empty($line))
	    print $File;
	else
	    print $File.', LINE: '.$Line.' ';
	$offset = (isset($dbg[1])) ? 1:0;
	if($offset>0)
		print $dbg[$offset]["class"].$dbg[$offset]["type"].$dbg[$offset]["function"]."(".count( $dbg[$offset]["args"]).")";
	print "</div>";
	print "<textarea style=\"width:100%;height:".$height."px;border:none;padding:0 0 0 5px;font-size:11px\">";
	print_r($data);
	print "</textarea></div>";
	print "</div>";
}

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',false);

$console = false;

if($console == true)
	$config = dirname(__FILE__).'/protected/config/dev.php';

require_once($yii);
$app = Yii::createWebApplication($config);

// Define icon path
define('ICONPATH', Yii::app()->baseUrl . DIRECTORY_SEPARATOR . Yii::app()->params->iconpack . DIRECTORY_SEPARATOR);

if(!$app->user->isGuest) {
	$app->db->connectionString = 'mysql:host=' . $app->user->host . ';dbname=information_schema';
	$app->db->username= $app->user->name;
    $app->db->password= $app->user->password;
    $app->db->autoConnect = true;
}

$session = Yii::app()->session;
$request = Yii::app()->getRequest();

$language = $session->itemAt('language') ? $session->itemAt('language') : $request->getPreferredLanguage();
$theme = $session->itemAt('theme') ? $session->itemAt('theme') : 'standard';

$app->setLanguage($language);
$app->setTheme($theme);

// Unset jQuery in Ajax requests
if($request->isAjaxRequest)
{
	$app->clientScript->scriptMap['jquery.js'] = false;
}

Yii::app()->getComponent('messages')->publishJavaScriptMessages();

$app->run();