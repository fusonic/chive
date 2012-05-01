<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo Yii::app()->name; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/css/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->getBaseUrl(); ?>/css/style.css" />

<link rel="shortcut icon" href="<?php echo BASEURL; ?>/images/favicon.ico" type="image/x-icon" />

<script type="text/javascript">
// Set global javascript variables
var basePath = '<?php echo BASEURL; ?>';
var baseUrl = '<?php echo Yii::app()->urlManager->baseUrl; ?>';
var iconPath = '<?php echo ICONPATH; ?>';
var themeUrl = '<?php echo Yii::app()->theme->baseUrl; ?>';
</script>

<?php
Yii::app()->clientScript->registerCoreScript('jquery');

$scriptFiles = array(
	'js/jquery/jquery.ui.js',
	'js/jquery/jquery.autocomplete.js',
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
	'js/ajaxResponse.js',
	'js/chive.js',
	'js/breadCrumb.js',
	'js/sideBar.js',
	'js/bookmark.js',
	'js/dataType.js',
	'js/notification.js',
	'js/profiling.js',
	'js/storageEngine.js',
	'js/views/schema/general.js',
	'js/views/schema/list.js',
	'js/views/information/general.js',
	'js/views/information/processes.js',
	'js/views/information/storageEngines.js',
	'js/views/privileges/users.js',
	'js/views/privileges/userSchemata.js',
	'js/views/privileges/userForm.js',
   	(CAP_ENABLED ? 'index.php/' : '') . 'assets/lang_js/' . Yii::app()->getLanguage() . '.js',
);
foreach($scriptFiles AS $file)
{
	echo '<script type="text/javascript" src="' . BASEURL . '/' . $file . '"></script>' . "\n";
}
?>

<?php Yii::app()->clientScript->registerScript('userSettings', Yii::app()->user->settings->getJsObject(), CClientScript::POS_HEAD); ?>
<script type="text/javascript">
$(document).ready(function() {
	sideBar.loadSchemata(function() {
		$('#schemaList').reloadListFilter($('#schemaSearch'));
	});
});
</script>
</head>
<body>

<div id="loading"><?php echo Yii::t('core', 'loading'); ?>...</div>

<div class="ui-layout-north">
	<div id="header">
		<div id="headerLeft">
			<a class="icon button" href="<?php echo BASEURL; ?>">
				<img src="<?php echo BASEURL; ?>/images/logo.png" alt="Chive" height="22" style="position: relative; top: 6px;" />
			</a>
			<?php echo Html::ajaxLink('schemata', array('class' => 'icon button')); ?>
				<?php echo Html::icon('server'); ?>
				<span><?php echo Yii::app()->user->host; ?></span>
			</a>
		</div>
		<div id="headerRight">
			<input type="text" id="globalSearch" value="<?php echo Yii::t('core', 'enterSchemaOrTable'); ?>" onclick="this.value = '';" class="search" />
			<a class="icon button" href="javascript:chive.refresh();">
				<?php echo Html::icon('refresh', 16, false, 'core.refresh'); ?>
			</a>
			<a class="icon button" href="https://bugs.launchpad.net/chive/+filebug" target="_blank">
				<?php echo Html::icon('ticket', 16, false, 'core.reportABug'); ?>
			</a>
			<a class="icon button" href="<?php echo Yii::app()->urlManager->baseUrl; ?>/site/logout">
				<?php echo Html::icon('logout', 16, false, 'core.logout'); ?>
			</a>
		</div>
	</div>
</div>
<div class="ui-layout-west">

<div id="sideBar">
<div class="sidebarHeader schemaList">
	<a class="icon" href="javascript:void(0)">
		<?php echo Html::icon('database', 24); ?>
		<span><?php echo Yii::t('core','schemata'); ?></span>
	</a>
	<img class="loading" src="<?php echo BASEURL; ?>/images/loading.gif" alt="<?php echo Yii::t('core', 'loading'); ?>..." />
</div>
<div class="sidebarContent schemaList">
	<input type="text" id="schemaSearch" class="search text" />

	<ul id="schemaList" class="list icon">
		<li class="nowrap template">
			<a href="<?php echo Yii::app()->createUrl('schema/#schemaName#'); ?>" class="icon">
				<?php echo Html::icon('database'); ?>
				<span>#schemaName#</span>
			</a>
		</li>
	</ul>

</div>
<div class="sidebarHeader">
	<a class="icon" href="javascript:void(0)">
		<?php echo Html::icon('info', 24); ?>
		<span><?php echo Yii::t('core', 'information'); ?></span>
	</a>
</div>
<div class="sidebarContent">
	<ul id="statusList" class="list icon">
		<li class="nowrap">
			<?php echo Html::ajaxLink('privileges/users', array('class' => 'icon')); ?>
				<?php echo Html::icon('privileges'); ?>
				<span><?php echo Yii::t('core', 'privileges'); ?></span>
			</a>
		</li>
		<li class="nowrap">
			<?php echo Html::ajaxLink('information/status', array('class' => 'icon')); ?>
				<?php echo Html::icon('chart'); ?>
				<span><?php echo Yii::t('core', 'status'); ?></span>
			</a>
		</li>
		<li class="nowrap">
			<?php echo Html::ajaxLink('information/variables', array('class' => 'icon')); ?>
				<?php echo Html::icon('variable'); ?>
				<span><?php echo Yii::t('core', 'variables'); ?></span>
			</a>
		</li>
		<li class="nowrap">
			<?php echo Html::ajaxLink('information/characterSets', array('class' => 'icon')); ?>
				<?php echo Html::icon('charset'); ?>
				<span><?php echo Yii::t('core', 'characterSet', array(2)); ?></span>
			</a>
		</li>
		<li class="nowrap">
			<?php echo Html::ajaxLink('information/storageEngines', array('class' => 'icon')); ?>
				<?php echo Html::icon('engine'); ?>
				<span><?php echo Yii::t('core', 'storageEngines'); ?></span>
			</a>
		</li>
		<li class="nowrap" style="margin-bottom: 10px">
			<?php echo Html::ajaxLink('information/processes', array('class' => 'icon')); ?>
				<?php echo Html::icon('process'); ?>
				<span><?php echo Yii::t('core', 'processes'); ?></span>
			</a>
		</li>
		<li class="nowrap">
			<?php echo Html::ajaxLink('information/about', array('class' => 'icon')); ?>
				<?php echo Html::icon('info'); ?>
				<span><?php echo Yii::t('core', 'about'); ?></span>
			</a>
		</li>
	</ul>
</div>

</div>
</div>
<div class="ui-layout-center" id="content"><?php echo $content; ?></div>

</body>
</html>