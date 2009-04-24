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

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);

$console = false;

if($console == true)
	$config = dirname(__FILE__).'/protected/config/dev.php';

require_once($yii);
$app = Yii::createWebApplication($config);

// Define constants
define('BASEURL', Yii::app()->baseUrl);
define('ICONPATH', BASEURL . '/' . Yii::app()->params->iconpack);

$session = Yii::app()->getSession();
$request = Yii::app()->getRequest();

if(!$app->user->isGuest)
{
	$app->db->connectionString = 'mysql:host=' . $app->user->host . ';dbname=information_schema';
	$app->db->username= $app->user->name;
    $app->db->password= $app->user->password;
    $app->db->autoConnect = true;

}
elseif(!in_array($request->getPathInfo(), array('site/login', 'index.php')))
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

$language = $session->itemAt('language') ? $session->itemAt('language') : $request->getPreferredLanguage();
$theme = $session->itemAt('theme') ? $session->itemAt('theme') : 'standard';

$app->setLanguage($language);
$app->setTheme($theme);

// Unset jQuery in Ajax requests
if($request->isAjaxRequest)
{
	$app->clientScript->scriptMap['jquery.js'] = false;
	$app->clientScript->scriptMap['jquery.min.js'] = false;
}

Yii::app()->getComponent('messages')->publishJavaScriptMessages();

$app->run();