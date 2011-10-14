<?php

// Some dirty debugging methods
function pre($_value) { if($_value === null || $_value === false || $_value === true) { var_dump($_value); } else { echo "<pre>"; print_r($_value); echo "</pre>";	}}
function predie($_value) { pre($_value); Yii::app()->end(); }

// Yii debug mode
defined('YII_DEBUG') or define('YII_DEBUG', true);

// Load Yii
require('yii/yii.php');

if(!date_default_timezone_get())
{
	// Set a fallback timezone if the current php.ini does not contain a default timezone setting.
	// If the environment is setup correctly, we won't override the timezone.
	date_default_timezone_set("UTC");
}

// Create web application
$app = Yii::createWebApplication('protected/config/main.php');

// Define constants
define('BASEURL', Yii::app()->baseUrl);
define('ICONPATH', BASEURL . '/images/icons/' . Yii::app()->params->iconPack);

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
elseif(!preg_match('/^(' . implode('|', $validPaths) . ')/i', Yii::app()->urlManager->parseUrl($app->request)))
{
	if($app->request->isAjaxRequest)
	{
		$response = new AjaxResponse();
		$response->redirectUrl = Yii::app()->createUrl('site/login');
		$response->send();
	}
	else
	{
		$app->catchAllRequest = array('site/login');
	}
}

// Language
if($app->session->itemAt('language'))
{
	$app->setLanguage($app->session->itemAt('language'));
}
elseif($app->request->getPreferredLanguage() && is_dir('protected/messages/' . $app->request->getPreferredLanguage()))
{
	$app->setLanguage($app->request->getPreferredLanguage());
}
else
{
	$app->setLanguage('en_us');
}

// Theme
$theme = $app->session->itemAt('theme') ? $app->session->itemAt('theme') : 'standard';
$app->setTheme($theme);

// Unset jQuery in Ajax requests
if($app->request->isAjaxRequest)
{
	$app->clientScript->scriptMap['jquery.js'] = false;
	$app->clientScript->scriptMap['jquery.min.js'] = false;
}

// Publish messages for javascript usage
Yii::app()->getComponent('messages')->publishJavaScriptMessages();

// Run application
$app->run();