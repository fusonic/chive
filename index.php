<?php

// Set session properties
session_name('chiveSession');
session_save_path('protected/runtime/sessions');

// Paths
$yii = 'yii/yii.php';
$config = dirname(__FILE__) . '/protected/config/main.php';

function pre($_value) {
	echo "<pre>";
	print_r($_value);
	echo "</pre>";
}

function predie($_value) {
	pre($_value);
	Yii::app()->end();
}

// Yii debug mode
defined('YII_DEBUG') or define('YII_DEBUG', true);

// Create Yii application
require_once($yii);
$app = Yii::createWebApplication($config);

// Define constants
define('BASEURL', Yii::app()->baseUrl);
define('ICONPATH', BASEURL . '/' . Yii::app()->params->iconpack);

$session = Yii::app()->getSession();
$request = Yii::app()->getRequest();

$validPaths = array(
	'site',
	'index.php',
);

if(!$app->user->isGuest)
{
	$app->db->connectionString = 'mysql:host=' . $app->user->host . ';dbname=information_schema';
	$app->db->username= $app->user->name;
    $app->db->password= $app->user->password;
    $app->db->autoConnect = true;
    $app->db->setActive(true);
}
elseif(!preg_match('/^(' . implode('|', $validPaths) . ')/i', $request->getPathInfo()))
{
	if($request->isAjaxRequest)
	{
		$response = new AjaxResponse();
		$response->redirectUrl = Yii::app()->baseUrl . '/site/login';
		$response->send();
	}
	else
	{
		$request->redirect(Yii::app()->baseUrl . '/site/login');
	}

}

// Language
$language = $session->itemAt('language') ? $session->itemAt('language') : $request->getPreferredLanguage();
$app->setLanguage($language);

// Theme
$theme = $session->itemAt('theme') ? $session->itemAt('theme') : 'standard';
$app->setTheme($theme);

// Unset jQuery in Ajax requests
if($request->isAjaxRequest)
{
	$app->clientScript->scriptMap['jquery.js'] = false;
	$app->clientScript->scriptMap['jquery.min.js'] = false;
}

// Publis messages for javascript usage
Yii::app()->getComponent('messages')->publishJavaScriptMessages();

/*
$db = new CDbConnection('mysql:host=' . 'localhost' . ';dbname=rowtest; charset=utf8',
			utf8_decode('root'),
			utf8_decode(''));

$db->active = true;
$cmd = $db->createCommand(file_get_contents('dump.sql'));
try 
{
$cmd->execute();	
}
catch(Exception $ex)
{
	predie($ex);
}
			
#$splitter = new SqlSplitter(file_get_contents('/var/www/dublin/trunk/dump.sql'));
#predie($splitter->getQueries());
*/

// Run application
$app->run();