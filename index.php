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
define('ICONPATH', BASEURL . '/images/icons/' . Yii::app()->params->iconPack);

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
elseif(!preg_match('/^(' . implode('|', $validPaths) . ')/i', Yii::app()->urlManager->parseUrl($request)))
{
	if($request->isAjaxRequest)
	{
		$response = new AjaxResponse();
		$response->redirectUrl = Yii::app()->createUrl('site/login');
		$response->send();
	}
	else
	{
		$request->redirect(Yii::app()->createUrl('site/login'));
	}
}

// Language
if($session->itemAt('language'))
{
	$app->setLanguage($session->itemAt('language'));
}
elseif($request->getPreferredLanguage() && is_dir('protected/messages/' . $request->getPreferredLanguage()))
{
	$app->setLanguage($request->getPreferredLanguage());
}
else
{
	$app->setLanguage('en_us');
}

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

// Run application
$app->run();