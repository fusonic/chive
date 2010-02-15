<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo Yii::app()->name; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- (en) Add your meta data here -->
<!-- (de) Fuegen Sie hier ihre Meta-Daten ein -->
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/css/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->getBaseUrl(); ?>/css/style.css" />
<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/css/patch/ie7.css"/>
<![endif]-->

<link rel="shortcut icon" href="<?php echo BASEURL; ?>/images/favicon.ico" type="image/x-icon" />

<script type="text/javascript">
// Set global javascript variables
var baseUrl = '<?php echo BASEURL; ?>';
var iconPath = '<?php echo ICONPATH; ?>';
var themeUrl = '<?php echo Yii::app()->theme->baseUrl; ?>';
</script>

<?php
$scriptFiles = array(
	'js/jquery/jquery.js',
	'js/jquery/jquery-ui-1.7.1.custom.min.js',
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
	'js/jquery/jquery.progressbar.js',
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
	'js/components/EditArea/edit_area_full.js',
	'js/components/EditArea/fusonic_extensions/editarea_autogrow.js',
   	'assets/lang_js/' . Yii::app()->getLanguage() . '.js',
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
			<a href="<?php echo BASEURL; ?>/#schemata" class="icon button">
				<?php echo Html::icon('server'); ?>
				<span><?php echo Yii::app()->user->host; ?></span>
			</a>
		</div>
		<div id="headerRight">
			<input type="text" id="globalSearch" value="Enter schema or table..." style="color: #AAA; margin-right: 5px;" onclick="this.value = '';" />
			<a class="icon button" href="javascript:chive.refresh();">
				<?php echo Html::icon('refresh', 16, false, 'core.refresh'); ?>
			</a>
			<a class="icon button" href="<?php echo BASEURL; ?>/site/logout">
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
	<img class="loading" src="images/loading.gif" alt="<?php echo Yii::t('core', 'loading'); ?>..." />
</div>
<div class="sidebarContent schemaList">
	<input type="text" id="schemaSearch" class="search text" />

	<ul id="schemaList" class="list icon">
		<li class="nowrap template">
			<a href="<?php echo Yii::app()->baseUrl; ?>/schema/#schemaName#" class="icon">
				<?php echo Html::icon('database'); ?>
				<span>#schemaName#</span>
			</a>
		</li>
	</ul>

</div>
<div class="sidebarHeader">
	<a class="icon">
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
			<a class="icon" href="javascript:chive.goto('information/about')">
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