<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Chive</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->getBaseUrl(); ?>/css/style.css" />
<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/css/patch/ie7.css"/>
<![endif]-->

<link rel="shortcut icon" href="<?php echo BASEURL; ?>/images/favicon.ico" type="image/x-icon" />

<script type="text/javascript">
// Set global javascript variables
var baseUrl = '<?php echo BASEURL; ?>';
var iconPath = '<?php echo BASEURL . '/images/icons/fugue'; ?>';
var themeUrl = '<?php echo Yii::app()->theme->baseUrl; ?>';
</script>

<?php
$scriptFiles = array(
	'js/jquery/jquery.js',
	'js/jquery/jquery-ui-1.7.1.custom.min.js',
	'js/jquery/jquery.blockUI.js',
	'js/jquery/jquery.checkboxTable.js',
	'js/jquery/jquery.editableTable.js',
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
	'js/views/column/form.js',
	'js/views/foreignKey/form.js',
	'js/views/index/form.js',
	'js/views/schema/general.js',
	'js/views/schema/list.js',
	'js/views/schema/routines.js',
	'js/views/schema/tables.js',
	'js/views/schema/views.js',
	'js/views/table/general.js',
	'js/views/table/browse.js',
	'js/views/table/form.js',
	'js/views/global/browse.js',
	'js/views/global/export.js',
	'js/views/global/import.js',
	'js/views/table/structure.js',
	'js/views/view/general.js',
	'js/components/EditArea/edit_area_full.js',
    'js/components/EditArea/fusonic_extensions/editarea.js',
	'assets/lang_js/' . Yii::app()->getLanguage() . '.js',
);

foreach($scriptFiles AS $file)
{
	echo '<script type="text/javascript" src="' . BASEURL . '/' . $file . '"></script>' . "\n";
}
?>

<script type="text/javascript">
$.ui.dialog.defaults.width = 400;

$(document).ready(function() {
	sideBar.loadTables(<?php echo json_encode($this->_schema->SCHEMA_NAME); ?>, function() {
		$('#tableList').setupListFilter($('#tableSearch'));
		$('#bookmarkList').setupListFilter($('#bookmarkSearch'));
	});
});
</script>

<?php Yii::app()->clientScript->registerScript('userSettings', Yii::app()->user->settings->getJsObject(), CClientScript::POS_HEAD); ?>

</head>
<body>

  <!---
  <div id="loading2" style="display: none; width: 100%; height: 100%; opacity: 0.4; background: #000 no-repeat url(<?php echo Yii::app()->baseUrl ?>/images/loading3.gif) center center; position: absolute; z-index: 99999999 !important; top: 0px; left: 0px;">
  	loading
  </div>
  --->

  <div id="loading"><?php echo Yii::t('core', 'loading'); ?>...</div>

  <div id="addBookmarkDialog" title="<?php echo Yii::t('core', 'addBookmark'); ?>" style="display: none">
	<?php echo Yii::t('core', 'enterAName'); ?><br />
	<input type="text" id="newBookmarkName" name="newBookmarkName" />
  </div>

  <div id="deleteBookmarkDialog" title="<?php echo Yii::t('core', 'deleteBookmark'); ?>" style="display: none">
  	<?php echo Yii::t('core', 'doYouReallyWantToDeleteBookmark'); ?>
  	<ul></ul>
  </div>

  <div class="ui-layout-north">
	<div id="header">
		<div id="headerLeft">
			<ul class="breadCrumb">
				<li>
					<a href="<?php echo BASEURL; ?>">
						<img src="<?php echo BASEURL; ?>/images/logo.png" alt="Chive" />
					</a>
				</li>
				<li>
					<a href="<?php echo BASEURL; ?>/#schemata" class="icon">
						<?php echo Html::icon('server', 24); ?>
						<span><?php echo Yii::app()->user->host; ?></span>
					</a>
				</li>
			</ul>
		</div>
		<div id="header-inner">
			<div id="headerRight">
				<a class="icon button" href="javascript:chive.refresh();" style="margin-right: 9px;">
					<?php echo Html::icon('refresh'); ?>
					<span><?php echo Yii::t('core', 'refresh'); ?></span>
				</a>
				<a class="icon button" href="<?php echo BASEURL; ?>/site/logout" style="margin-right: 9px;">
					<?php echo Html::icon('logout'); ?>
					<span><?php echo Yii::t('core', 'logout'); ?></span>
				</a>
			</div>
		</div>
	</div>
  </div>
  <div class="ui-layout-west">

  	<div id="sideBar">
  		<div class="sidebarHeader tableList">
			<a class="icon">
				<?php echo Html::icon('table', 24, false, 'core.tables'); ?>
				<span><?php echo Yii::t('core', 'tables'); ?></span>
			</a>
			<img class="loading" src="images/loading.gif" alt="<?php echo Yii::t('core', 'loading'); ?>..." />
		</div>
		<div class="sidebarContent tableList">

			<input type="text" id="tableSearch" class="search text" />

			<ul class="list icon nowrap" id="tableList">
				<li class="nowrap template">
					<?php echo Html::ajaxLink('tables/#tableName#/browse', array('class' => 'icon')); ?>
						<?php echo Html::icon('browse', 16, false, 'plain:#rowCountText#'); ?>
					</a>
					<?php echo Html::ajaxLink('tables/#tableName#/structure', array('class' => 'icon')); ?>
						<span>#tableName#</span>
					</a>
					<div class="listIconContainer">
						<a href="javascript:chive.goto('tables/#tableName#/insert')">
							<?php echo Html::icon('add', 16, false, 'core.insertNewRow'); ?>
						</a>
					</div>
				</li>
			</ul>

		</div>
  		<div class="sidebarHeader">
			<a class="icon" href="#views">
				<?php echo Html::icon('view', 24, false, 'core.views'); ?>
				<span><?php echo Yii::t('core', 'views'); ?></span>
			</a>
		</div>
		<div class="sidebarContent">

			<input type="text" id="viewSearch" class="search text" />

			<ul class="list icon nowrap" id="viewList">
				<?php foreach($this->_schema->views AS $view) { ?>
					<li>
						<?php echo Html::ajaxLink('views/' . $view->TABLE_NAME . '/browse', array('class' => 'icon')); ?>
							<?php echo Html::icon('view', 16, false, 'core.browse'); ?>
						</a>
						<?php echo Html::ajaxLink('views/' . $view->TABLE_NAME . '/structure', array('class' => 'icon')); ?>
							<span><?php echo $view->TABLE_NAME; ?></span>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
  		<div class="sidebarHeader">
			<a class="icon">
				<?php echo Html::icon('bookmark', 24, false, 'core.bookmarks'); ?>
				<span><?php echo Yii::t('core', 'bookmarks') ?></span>
			</a>
		</div>
		<div class="sidebarContent">

			<input type="text" id="bookmarkSearch" class="search text" />

			<ul class="list icon nowrap" id="bookmarkList">
				<?php if($bookmarks = Yii::app()->user->settings->get('bookmarks', 'database', $this->schema)) { ?>
					<?php foreach($bookmarks AS $key => $bookmark) { ?>
						<li id="bookmark_<?php echo $bookmark['id']; ?>">
							<?php echo Html::ajaxLink('bookmark/show/' . $bookmark['id'], array('class' => 'icon', 'title' => $bookmark['query'])); ?>
								<?php echo Html::icon('bookmark'); ?>
								<span><?php echo $bookmark['name']; ?></span>
							</a>
							<div class="listIconContainer">
								<a href="javascript:void(0);" onclick="Bookmark.remove('<?php echo $this->schema; ?>', '<?php echo $bookmark['id']; ?>');">
									<?php echo Html::icon('delete', 16, false, 'core.delete'); ?>
								</a>
								<a href="javascript:void(0);" onclick="Bookmark.execute('<?php echo $this->schema; ?>', '<?php echo $bookmark['id']; ?>');">
									<?php echo Html::icon('execute', 16, false, 'core.execute'); ?>
								</a>
							</div>
						</li>
					<?php } ?>
				<?php } ?>
			</ul>
		</div>
	</div>
  </div>
  <div class="ui-layout-center" id="content">
  	<?php echo $content; ?>
  </div>

</body>
</html>