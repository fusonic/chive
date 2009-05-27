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

<?php
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
	'js/jquery/jquery.tableForm.js',
	'js/lib/json.js',
	'js/main.js',
	'js/bookmark.js',
	'js/dataType.js',
	'js/notification.js',
	'js/profiling.js',
	'js/storageEngine.js',
	'js/views/column/form.js',
	'js/views/index/form.js',
	'js/views/schema/general.js',
	'js/views/schema/list.js',
	'js/views/schema/show.js',
	'js/views/schema/processes.js',
	'js/views/table/general.js',
	'js/views/table/browse.js',
	'js/views/table/form.js',
	'js/views/table/structure.js',
	'assets/lang_js/' . Yii::app()->getLanguage() . '.js',
);
foreach($scriptFiles AS $file)
{
	echo '<script type="text/javascript" src="' . BASEURL . '/' . $file . '"></script>' . "\n";
}
?>

<?php Yii::app()->clientScript->registerScript('userSettings', Yii::app()->user->settings->getJsObject(), CClientScript::POS_HEAD); ?>

</head>
<body>

  <div id="loading">
  	<?php echo Yii::t('core', 'loading'); ?>...
  </div>

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
					array('label'=>'Refresh','icon'=>'refresh', 'url'=>'javascript:void(0)', 'htmlOptions'=>array('onclick'=>'return reload();'), 'visible'=>!Yii::app()->user->isGuest),
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

			<input type="text" id="schemaSearch" class="search text" />

			<ul id="schemaList" class="list icon">
				<?php foreach(Schema::model()->findAll(array('order' => 'SCHEMA_NAME ASC')) AS $schema) { ?>
					<li class="nowrap">
						<a href="<?php echo Yii::app()->baseUrl; ?>/schema/<?php echo $schema->SCHEMA_NAME; ?>">
							<com:Icon name="database" size="16" />
						</a>
						<a href="<?php echo Yii::app()->baseUrl; ?>/schema/<?php echo $schema->SCHEMA_NAME; ?>">
							<?php echo $schema->SCHEMA_NAME; ?>
						</a>
					</li>
				<?php } ?>
			</ul>

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
		<!--- TRIGGERS
  		<div class="sidebarHeader">
			<a class="icon">
				<img src="images/icons/script_fav_24.png" />
				<span>Triggers</span>
			</a>
		</div>
		<div class="sidebarContent">
			triggers
		</div>
		--->
  		<div class="sidebarHeader">
			<a class="icon">
				<com:Icon name="info" size="24" />
				<span>Information</span>
			</a>
		</div>
		<div class="sidebarContent">
			<ul id="statusList" class="list icon">
				<li class="nowrap">
					<a class="icon" href="#schemata/status">
						<com:Icon name="chart" size="16" />
						<?php echo Yii::t('core', 'status'); ?>
					</a>
				</li>
				<li class="nowrap">
					<a class="icon" href="#schemata/variables">
						<com:Icon name="variable" size="16" />
						<?php echo Yii::t('database', 'variables'); ?>
					</a>
				</li>
				<li class="nowrap">
					<a class="icon" href="#schemata/charactersets">
						<com:Icon name="charset" size="16" />
						<?php echo Yii::t('database', 'characterSets'); ?>
					</a>
				</li>
				<li class="nowrap">
					<a class="icon" href="#schemata/storageengines">
						<com:Icon name="engine" size="16" />
						<?php echo Yii::t('database', 'storageEngines'); ?>
					</a>
				</li>
				<li class="nowrap">
					<a class="icon" href="#schemata/processes">
						<com:Icon name="process" size="16" />
						<?php echo Yii::t('database', 'processes'); ?>
					</a>
				</li>
			</ul>
		</div>

	</div>
  </div>
  <div class="ui-layout-center" id="content">
  	<?php echo $content; ?>
  </div>

</body>
</html>