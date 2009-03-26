<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><% echo $this->pageTitle; %></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<!-- (en) Add your meta data here -->
<!-- (de) Fuegen Sie hier ihre Meta-Daten ein -->
<link rel="stylesheet" type="text/css" href="<% echo Yii::app()->request->baseUrl; %>/css/main.css" />
<link rel="stylesheet" type="text/css" href="<% echo Yii::app()->theme->getBaseUrl(); %>/css/style.css" />
<!--[if lte IE 7]>
<link href="css/patches/patch_my_layout.css" rel="stylesheet" type="text/css" />
<![endif]-->

<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.layout.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery-ui-1.7.1.custom.js', CClientScript::POS_HEAD); ?>

<script type="text/javascript">

$(document).ready(function() {

	$('body').layout({
		// General
		applyDefaultStyles: false,

		north__size: 42,
		north__resizable: false,
		north__closable: false,
		north__spacing_open: 1,

	});

	$('body').click(function() {
		$('.dropdown').slideUp();
	});

});

</script>

</head>
<body>
  <div class="ui-layout-north">
	<div id="header">
		<div id="headerLeft">

		</div>
		<div id="headerLogo">
		</div>
		<div id="headerRight">
			<?php $this->widget('application.components.MainMenu',array(
				'items'=>array(
						array(
							'label'=>'<img src="images/country/' . substr(Yii::app()->request->getPreferredLanguage(), 0, 2) . '.png" alt="" title="" /><span>' . Yii::t('language', substr(Yii::app()->request->getPreferredLanguage(), 0, 2)) . "</span>",
							'url'=>array('#'),
							'htmlOptions' => array(
								'style'=>'width: 150px;',
								'class'=>'icon',
									'onclick'=>'$("#languageSelect").slideDown(); return false;'
							),
						),
				),
			)); ?>
		</div>
	</div>
  </div>

  <div class="ui-layout-center">

  		<% echo $content; %>
  </div>

</body>
</html>