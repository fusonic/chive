<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo Yii::app()->name; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/css/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->getBaseUrl(); ?>/css/style.css" />

<link rel="shortcut icon" href="<?php echo BASEURL; ?>/images/favicon.ico" type="image/x-icon" />
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery/jquery.ui.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/views/site/login.js', CClientScript::POS_HEAD); ?>

</head>
<body>
	<div id="header">
		<div id="headerRight">
			<a class="icon button" href="javascript:void(0);" style="margin-right: 9px;" onclick="$('#themeDialog').dialog('open');">
				<img class="icon icon16" src="<?php echo BASEURL; ?>/themes/<?php  echo Yii::app()->getTheme()->name; ?>/images/icon.png" alt="<?php echo ucfirst(Yii::app()->getTheme()->name); ?>" />
				<span><?php echo ucfirst(Yii::app()->getTheme()->name); ?></span>
			</a>
			<a class="icon button" href="javascript:void(0);" style="margin-right: 9px;" onclick="$('#languageDialog').dialog('open');">
				<img class="icon icon16" src="<?php echo BASEURL; ?>/images/language/<?php echo Yii::app()->getLanguage(); ?>.png" alt="<?php echo Yii::t('language', Yii::app()->getLanguage()); ?>" />
				<span><?php echo Yii::t('language', Yii::app()->getLanguage()); ?></span>
			</a>
		</div>
	</div>

  <?php echo $content; ?>

  <script type="text/javascript">
		login.setup();
  </script>

</body>
</html>