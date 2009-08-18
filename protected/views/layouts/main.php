<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
 * 
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Chive</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- (en) Add your meta data here -->
<!-- (de) Fuegen Sie hier ihre Meta-Daten ein -->
<link rel="stylesheet" type="text/css"
	href=" echo Yii::app()->request->baseUrl; ?>/css/main.css" />
<link rel="stylesheet" type="text/css"
	href=" echo Yii::app()->theme->getBaseUrl(); ?>/css/style.css" />
<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" href=" echo BASEURL; ?>/css/patch/ie7.css"/>
<![endif]-->

<link rel="shortcut icon" href=" echo BASEURL; ?>/images/favicon.ico" type="image/x-icon" />

<script type="text/javascript">
// Set global javascript variables
var baseUrl = ' echo Yii::app()->baseUrl; ?>';
var iconPath = ' echo Yii::app()->baseUrl . '/images/icons/fugue'; ?>';
</script>


$scriptFiles = array(
	'js/jquery/jquery.js',
	'js/jquery/jquery-ui-1.7.1.custom.min.js',
	'js/jquery/jquery.blockUI.js',
	'js/jquery/jquery.checkboxTable.js',
	'js/jquery/jquery.form.js',
	'js/jquery/jquery.jeditable.js',
	'js/jquery/jquery.layout.js',
	'js/jquery/jquery.listFilter.js',
	'js/jquery/jquery.purr.js',
	'js/jquery/jquery.selectboxes.js',
	'js/jquery/jquery.hotkey.js',
	'js/jquery/jquery.tableForm.js',
	'js/lib/json.js',
	'js/main.js',
	'js/breadCrumb.js',
	'js/sideBar.js',
	'js/bookmark.js',
	'js/dataType.js',
	'js/notification.js',
	'js/profiling.js',
	'js/storageEngine.js',
	'js/views/schema/general.js',
	'js/views/schema/list.js',
	'js/views/information/processes.js',
	'js/views/information/storageEngines.js',
	'js/views/privileges/users.js',
	'js/views/privileges/userSchemata.js',
	'js/views/privileges/userForm.js',
	'js/components/EditArea/edit_area_full.js',
	'js/components/EditArea/fusonic_extensions/editarea_autogrow.js',
   	'assets/lang_js/' . Yii::app()->getLanguage() . '.js',
);
foreach($scriptFiles AS $file)
{
	echo '<script type="text/javascript" src="' . BASEURL . '/' . $file . '"></script>' . "\n";
}
?>

 Yii::app()->clientScript->registerScript('userSettings', Yii::app()->user->settings->getJsObject(), CClientScript::POS_HEAD); ?>
<script type="text/javascript">
$(document).ready(function() {
	sideBar.loadSchemata(function() {
		$('#schemaList').reloadListFilter($('#schemaSearch'));
	});
});
</script>
</head>
<body>

<div id="loading"> echo Yii::t('core', 'loading'); ?>...</div>

<div class="ui-layout-north">
<div id="header">
<div id="headerLeft">
<ul class="breadCrumb">
	<li>
		<a href=" echo Yii::app()->baseUrl . '/#schemata'; ?>">
			<img src=" echo Yii::app()->baseUrl . "/images/logo.png"; ?>" />
		</a>
	</li>
</ul>
</div>
<div id="headerRight"> $this->widget('application.components.MainMenu',array(
				'items'=>array(
array('label'=>'Home', 'icon'=>'home', 'url'=>array('/site/index'), 'visible'=>!Yii::app()->user->isGuest),
array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
array('label'=>'Refresh','icon'=>'refresh', 'url'=>'javascript:void(0)', 'htmlOptions'=>array('onclick'=>'return refresh();'), 'visible'=>!Yii::app()->user->isGuest),
array('label'=>'Logout', 'icon'=>'logout', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
),
)); ?></div>
</div>
</div>
<div class="ui-layout-west">

<div id="sideBar">
<div class="sidebarHeader schemaList">
	<a class="icon" href="javascript:void(0)">
		<com:Icon name="database" size="24" text="database.schemata" />
		<span> echo Yii::t('database','schemata'); ?></span>
	</a>
	<img class="loading" src="images/loading.gif" alt=" echo Yii::t('core', 'loading'); ?>..." />
</div>
<div class="sidebarContent schemaList">
	<input type="text" id="schemaSearch" class="search text" />

	<ul id="schemaList" class="list icon">
		<!---
		 foreach(Schema::model()->findAll(array('order' => 'SCHEMA_NAME ASC')) AS $schema) { ?>
			<li class="nowrap">
				<a href=" echo Yii::app()->baseUrl; ?>/schema/ echo $schema->SCHEMA_NAME; ?>" class="icon">
					<com:Icon name="database" size="16" />
					<span> echo $schema->SCHEMA_NAME; ?></span>
				</a>
			</li>
		 } ?>
		--->
		<li class="nowrap template">
			<a href=" echo Yii::app()->baseUrl; ?>/schema/#schemaName#" class="icon">
				<com:Icon name="database" size="16" />
				<span>#schemaName#</span>
			</a>
		</li>
	</ul>

</div>
<div class="sidebarHeader">
	<a class="icon">
		<com:Icon name="privilege" size="24" />
		<span>Privileges</span>
	</a>
</div>
<div class="sidebarContent">
	<ul id="statusList" class="list icon">
		<li class="nowrap">
			<a class="icon" href="#privileges/users">
				<com:Icon name="user" size="16" />
				 echo Yii::t('core', 'users'); ?>
			</a>
		</li>
	</ul>
</div>
<div class="sidebarHeader">
	<a class="icon">
		<com:Icon name="info" size="24" />
		<span>Information</span>
	</a>
</div>
<div class="sidebarContent">
	<ul id="statusList" class="list icon">
		<li class="nowrap"><a class="icon" href="#information/status"> <com:Icon
			name="chart" size="16" />  echo Yii::t('core', 'status'); ?> </a>
		</li>
		<li class="nowrap"><a class="icon" href="#information/variables"> <com:Icon
			name="variable" size="16" />  echo Yii::t('database', 'variables'); ?>
		</a></li>
		<li class="nowrap"><a class="icon" href="#information/characterSets"> <com:Icon
			name="charset" size="16" />  echo Yii::t('database', 'characterSets'); ?>
		</a></li>
		<li class="nowrap"><a class="icon" href="#information/storageEngines">
		<com:Icon name="engine" size="16" />  echo Yii::t('database', 'storageEngines'); ?>
		</a></li>
		<li class="nowrap"><a class="icon" href="#information/processes"> <com:Icon
			name="process" size="16" />  echo Yii::t('database', 'processes'); ?>
		</a></li>
	</ul>
</div>

</div>
</div>
<div class="ui-layout-center" id="content"> echo $content; ?></div>

</body>
</html>
