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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<!-- (en) Add your meta data here -->
<!-- (de) Fuegen Sie hier ihre Meta-Daten ein -->
<link rel="stylesheet" type="text/css" href=" echo BASEURL; ?>/css/main.css" />
<link rel="stylesheet" type="text/css" href="<% echo Yii::app()->theme->getBaseUrl(); %>/css/style.css" />
<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" href=" echo BASEURL; ?>/css/patch/ie7.css"/>
<![endif]-->

<link rel="shortcut icon" href=" echo BASEURL; ?>/images/favicon.ico" type="image/x-icon" />

 Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery/jquery.js', CClientScript::POS_HEAD); ?>
 Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery/jquery-ui-1.7.1.custom.min.js', CClientScript::POS_HEAD); ?>
 Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/views/site/login.js', CClientScript::POS_HEAD); ?>

</head>
<body>
	<div id="header">
		<div id="header-inner">
			<a class="icon button" href="javascript:void(0);" style="margin-right: 9px;" onclick="$('#themeDialog').dialog('open');">
				<img src=" echo BASEURL; ?>/themes/  echo Yii::app()->getTheme()->name; ?>/images/icon.png" />
				<span> echo ucfirst(Yii::app()->getTheme()->name); ?></span>
			</a>
			<a class="icon button" href="javascript:void(0);" style="margin-right: 9px;" onclick="$('#languageDialog').dialog('open');">
				<img src=" echo BASEURL; ?>/images/country/ echo substr(Yii::app()->getLanguage(),0,2); ?>.png" />
				<span> echo Yii::t('language', Yii::app()->getLanguage()); ?></span>
			</a>
		</div>
	</div>

   echo $content; ?>

  <script type="text/javascript">
		login.setup();
  </script>

</body>
</html>