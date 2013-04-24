<?php

// Some dirty debugging methods
function pre($_value) { if($_value === null || $_value === false || $_value === true) { var_dump($_value); } else { echo "<pre>"; print_r($_value); echo "</pre>";	}}
function predie($_value) { pre($_value); Yii::app()->end(); }

if (!defined('__DIR__'))
{
    define('__DIR__', dirname(__FILE__));
}

// Yii debug mode
defined('YII_DEBUG') or define('YII_DEBUG', true);

// Chive phar mode
define('CAP_ENABLED', strpos(__FILE__, "phar://") === 0);
if(CAP_ENABLED)
{
    define("CAP_PATH", sys_get_temp_dir() . DIRECTORY_SEPARATOR . "chive_" . md5(__FILE__));
    @mkdir(CAP_PATH, 0777);
    @mkdir(CAP_PATH . DIRECTORY_SEPARATOR . "assets", 0777);
    @mkdir(CAP_PATH . DIRECTORY_SEPARATOR . "sessions", 0777);
    @mkdir(CAP_PATH . DIRECTORY_SEPARATOR . "user-config", 0777);
    copy(__DIR__ . DIRECTORY_SEPARATOR . "protected/runtime/user-config/default.xml", CAP_PATH . DIRECTORY_SEPARATOR . "user-config" . DIRECTORY_SEPARATOR . "default.xml");
}

// Load Yii
require('yii/yii.php');

if(!ini_get('date.timezone'))
{
	// Set a fallback timezone if the current php.ini does not contain a default timezone setting.
	// If the environment is setup correctly, we won't override the timezone.
	date_default_timezone_set("UTC");
}

// Create web application
$app = YiiBase::createWebApplication(__DIR__ . DIRECTORY_SEPARATOR . 'protected/config/' . (CAP_ENABLED ? 'phar' : 'main') . '.php');
$app->getSession()->setCookieParams(array('path' => $app->getBaseUrl(false)));

// Define constants
define('BASEURL', Yii::app()->baseUrl);
define('ICONPATH', BASEURL . '/images/icons/' . Yii::app()->params->iconPack);

$validPaths = array(
	'site',
	'index.php',
    'asset',
);

if(!$app->user->isGuest)
{
	$app->db->connectionString = 'mysql:host=' . $app->user->host . ';port=' . $app->user->port . ';dbname=information_schema';
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

		header("Content-type: application/json");
		echo $response->__toString();
		$app->end();
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
$app->getComponent('messages')->publishJavaScriptMessages();

// Run application
$app->run();
