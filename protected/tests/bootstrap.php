<?php
$yiit = dirname(__FILE__) . '/../../yii/yiit.php';
$config = dirname(__FILE__) . '/../config/tests.php';
require_once($yiit);
require_once(dirname(__FILE__) . '/WebTestCase.php');
require_once(dirname(__FILE__) . '/ChiveTestCase.php');
Yii::createWebApplication($config);