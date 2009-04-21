<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $this->pageTitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<!-- (en) Add your meta data here -->
<!-- (de) Fuegen Sie hier ihre Meta-Daten ein -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->getBaseUrl(); ?>/css/style.css" />
<!--[if lte IE 7]>
<link href="css/patches/patch_my_layout.css" rel="stylesheet" type="text/css" />
<![endif]-->

<script type="text/javascript">
	// Set global javascript variables
	var baseUrl = '<?php echo Yii::app()->baseUrl; ?>';
	var iconPath = '<?php echo Yii::app()->baseUrl . '/images/icons/fugue'; ?>';
</script>

<?php Yii::app()->clientScript->registerScript('userSettings', Yii::app()->user->settings->getJsObject(), CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery/jquery.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery/jquery.purr.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/lib/json.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/notification.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/main.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery/jquery.layout.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery/jquery.listFilter.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery/jquery.tableForm.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery/jquery-ui-1.7.1.custom.min.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery/jquery.checkboxTable.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery/jquery.form.js', CClientScript::POS_HEAD); ?>

<script type="text/javascript"> </script>

</head>
<body>

  <div id="loading"><?php echo Yii::t('core', 'loading'); ?>...</div>

  <div class="ui-layout-north">
	<div id="header">
		<div id="headerLeft">
			<img src="<?php echo Yii::app()->request->baseUrl . "/images/logo.png"; ?>" />
		</div>
		<div id="headerRight">
			<?php $this->widget('application.components.MainMenu',array(
				'items'=>array(
					array('label'=>'Home', 'icon'=>'home', 'url'=>array('/site/index'), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
					array('label'=>'Logout', 'icon'=>'logout', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
				),
			)); ?>
		</div>
	</div>
  </div>
  <div class="ui-layout-west">

  <div id="sideBar">
  		<div class="sidebarHeader">
			<a class="icon">
				<com:Icon name="database" size="24" text="database.schemata" />
				<span><?php echo Yii::t('database','schemata'); ?></span>
			</a>
		</div>
		<div class="sidebarContent">
			<!---
			<ul class="list icon">
				<?php foreach(Schema::model()->findAll(array('order'=>'SCHEMA_NAME ASC')) AS $schema) { ?>
					<li class="nowrap">
						<a href="<?php echo $schema->SCHEMA_NAME ?>">
							<com:Icon name="database" size="16" />
						</a>
						<a href="<?php echo Yii::app()->baseUrl; ?>/schema/<?php echo $schema->SCHEMA_NAME; ?>">
							<?php echo $schema->SCHEMA_NAME; ?>
						</a>
					</li>
				<?php } ?>
			</ul>
			 --->

			<com:SchemaTreeView />

		</div>
  		<div class="sidebarHeader">
			<a class="icon">
				<com:Icon name="privilege" size="24" />
				<span>Privileges</span>
			</a>
		</div>
		<div class="sidebarContent">
		</div>
  		<div class="sidebarHeader">
			<a class="icon">
				<com:Icon name="routine" size="24" />
				<span>Routines</span>
			</a>
		</div>
		<div class="sidebarContent">
			routines
		</div>
  		<div class="sidebarHeader">
			<a class="icon">
				<img src="images/icons/script_fav_24.png" />
				<span>Triggers</span>
			</a>
		</div>
		<div class="sidebarContent">
			triggers
		</div>

	</div>
  </div>
  <div class="ui-layout-center">
  	<?php echo $content; ?>
  </div>

</body>
</html>