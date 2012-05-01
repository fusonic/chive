<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo Yii::app()->name; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

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
	'js/ace/ace.js',
	'js/ace/mode-sql.js',
	'js/ace/theme-chive-uncompressed.js',
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
	sideBar.loadTables(<?php echo CJSON::encode($this->_schema->SCHEMA_NAME); ?>, function() {
		$('#tableList').setupListFilter($('#tableSearch'));
		$('#bookmarkList').setupListFilter($('#bookmarkSearch'));
	});
	sideBar.loadViews(<?php echo CJSON::encode($this->_schema->SCHEMA_NAME); ?>, function() {
		$('#viewList').setupListFilter($('#viewSearch'));
	});
});
</script>
</head>
<body>

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
			<a class="icon button" href="<?php echo Yii::app()->getBaseUrl(true); ?>">
				<img src="<?php echo BASEURL; ?>/images/logo.png" alt="Chive" height="22" style="position: relative; top: 6px;" />
			</a>
			<a href="<?php echo Html::getBaseUrlWithScriptName() . '#schemata'; ?>" class="icon button">
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
  		<div class="sidebarHeader tableList">
			<a class="icon" href="javascript:void(0)">
				<?php echo Html::icon('table', 24, false, 'core.tables'); ?>
				<span><?php echo Yii::t('core', 'tables'); ?></span>
			</a>
			<img class="loading" src="<?php echo BASEURL; ?>/images/loading.gif" alt="<?php echo Yii::t('core', 'loading'); ?>..." />
		</div>
		<div class="sidebarContent tableList">

			<input type="text" id="tableSearch" class="search text" />

			<ul class="list icon nowrap" id="tableList">
				<li class="nowrap template">
					<div class="listIconContainer">
						<?php echo Html::ajaxLink('tables/#tableName#/search', array('class' => 'icon')); ?>
							<?php echo Html::icon('search', 16, false, 'core.search'); ?>
						</a>
						<?php echo Html::ajaxLink('tables/#tableName#/insert', array('class' => 'icon')); ?>
							<?php echo Html::icon('add', 16, false, 'core.insertNewRow'); ?>
						</a>
					</div>
					<?php echo Html::ajaxLink('tables/#tableName#/browse', array('class' => 'icon')); ?>
						<?php echo Html::icon('browse', 16, false, 'plain:#rowCountText#'); ?>
					</a>
					<?php echo Html::ajaxLink('tables/#tableName#/structure', array('class' => 'icon')); ?>
						<span>#tableName#</span>
					</a>
				</li>
			</ul>

			<div class="noEntries">
				<?php echo Yii::t('core', 'noTables'); ?>
			</div>

		</div>
  		<div class="sidebarHeader">
			<a class="icon" href="javascript:void(0)">
				<?php echo Html::icon('view', 24, false, 'core.views'); ?>
				<span><?php echo Yii::t('core', 'views'); ?></span>
			</a>
			<img class="loading" src="<?php echo BASEURL; ?>/images/loading.gif" alt="<?php echo Yii::t('core', 'loading'); ?>..." />
		</div>
		<div class="sidebarContent">

			<input type="text" id="viewSearch" class="search text" />

			<ul class="list icon nowrap" id="viewList">
				<li class="nowrap template">
					<?php echo Html::ajaxLink('views/#viewName#/browse', array('class' => 'icon')); ?>
						<?php echo Html::icon('browse', 16, false); ?>
					</a>
					<?php echo Html::ajaxLink('views/#viewName#/structure', array('class' => 'icon')); ?>
						<span>#viewName#</span>
					</a>
				</li>
			</ul>

			<div class="noEntries">
				<?php echo Yii::t('core', 'noViews'); ?>
			</div>

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
							<div class="listIconContainer">
								<a href="javascript:void(0);" onclick="Bookmark.remove('<?php echo $this->schema; ?>', '<?php echo $bookmark['id']; ?>');">
									<?php echo Html::icon('delete', 16, false, 'core.delete'); ?>
								</a>
								<a href="javascript:void(0);" onclick="Bookmark.execute('<?php echo $this->schema; ?>', '<?php echo $bookmark['id']; ?>');">
									<?php echo Html::icon('execute', 16, false, 'core.execute'); ?>
								</a>
							</div>
							<?php echo Html::ajaxLink('bookmark/show/' . $bookmark['id'], array('class' => 'icon', 'title' => $bookmark['query'])); ?>
								<?php echo Html::icon('bookmark'); ?>
								<span><?php echo $bookmark['name']; ?></span>
							</a>
						</li>
					<?php } ?>
				<?php } ?>
			</ul>
			<?php if(!$bookmarks) { ?>
				<div class="noEntries">
					<?php echo Yii::t('core', 'noBookmarksFound'); ?>
				</div>
			<?php } ?>
		</div>
	</div>
  </div>
  <div class="ui-layout-center" id="content">
  	<?php echo $content; ?>
  </div>

</body>
</html>