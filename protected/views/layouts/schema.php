<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $this->pageTitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->getBaseUrl(); ?>/css/style.css" />
<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL; ?>/css/patch/ie7.css"/>
<![endif]-->

<link rel="shortcut icon" href="<?php echo Yii::app()->baseUrl; ?>/images/favicon.ico" />

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
	'js/views/schema/processes.js',
	'js/views/schema/tables.js',
	'js/views/schema/views.js',
	'js/views/table/general.js',
	'js/views/table/browse.js',
	'js/views/table/form.js',
	'js/views/table/structure.js',
	'js/views/view/general.js',
	'protected/extensions/CodePress/codepress/codepress.js',		// @todo (rponudic) change this to valid path
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

  <!---
  <div id="loading2" style="display: none; width: 100%; height: 100%; opacity: 0.4; background: #000 no-repeat url(<?php echo Yii::app()->baseUrl ?>/images/loading3.gif) center center; position: absolute; z-index: 99999999 !important; top: 0px; left: 0px;">
  	loading
  </div>
  --->

  <div id="loading"><?php echo Yii::t('core', 'loading'); ?>...</div>

  <div id="addBookmarkDialog" title="<?php echo Yii::t('core', 'addBookmark'); ?>" style="display: none">
	<?php echo Yii::t('message', 'enterAName'); ?><br />
	<input type="text" id="newBookmarkName" name="newBookmarkName" />
  </div>

  <div id="deleteBookmarkDialog" title="<?php echo Yii::t('core', 'deleteBookmark'); ?>" style="display: none">
  	<?php echo Yii::t('message', 'doYouReallyWantToDeleteBookmark'); ?>
  </div>

  <div class="ui-layout-north">
	<div id="header">
		<div id="headerLeft">
			<ul class="breadCrumb">
				<li>
					<a href="<?php echo Yii::app()->baseUrl . '/#schemata'; ?>" style="float:left; margin-right: 5px;">
						<img src="<?php echo Yii::app()->baseUrl . "/images/logo.png"; ?>" alt="logo" />
					</a>
				</li>
				<?php if($this->schema) { ?>
					<li id="bc-schema">
						<!---<span>&raquo;</span>--->
						<a class="icon" href="<?php echo Yii::app()->baseUrl; ?>/schema/<?php echo $this->schema; ?>">
							<com:Icon name="database" size="24" />
							<span><?php echo $_GET['schema']; ?></span>
						</a>
					</li>
				<?php } ?>
				<li id="bc-table" style="display: none;">
					<!---<span>&raquo;</span>--->
					<a class="icon" href="<?php echo Yii::app()->baseUrl; ?>/database/<?php $this->schema; ?>">
						<com:Icon name="table" size="24" />
						<span></span>
					</a>
				</li>
			</ul>
		</div>
		<div id="headerLogo">
		</div>
		<div id="headerRight">
			<?php $this->widget('application.components.MainMenu',array(
				'items'=>array(
					array('label'=>'Home', 'icon'=>'home', 'url'=>array('/site/index'), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Refresh','icon'=>'refresh', 'url'=>'javascript:void(0)', 'htmlOptions'=>array('onclick'=>'return refresh();'), 'visible'=>!Yii::app()->user->isGuest),
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
				<com:Icon name="table" size="24" text="database.tables" />
				<span><?php echo Yii::t('database', 'tables'); ?></span>
			</a>
		</div>
		<div class="sidebarContent">

			<input type="text" id="tableSearch" class="search text" />

			<ul class="list icon nowrap" id="tableList">
				<?php foreach(Table::model()->findAllByAttributes(array('TABLE_SCHEMA' => $_GET['schema'])) AS $table) { ?>
					<li>
						<a href="#tables/<?php echo $table->TABLE_NAME; ?>/<?php echo ($table->getRowCount() ? 'browse' : 'structure'); ?>" class="icon">
							<?php $this->widget('Icon', array('name'=>'browse', 'size'=>16, 'disabled'=>!$table->getRowCount(), 'title'=>Yii::t('database', 'Xrows', array('{amount}'=>$table->getRowCount() ? $table->getRowCount() : 0)))); ?>
						</a>
						<a href="#tables/<?php echo $table->TABLE_NAME; ?>/structure" class="icon">
							<span><?php echo $table->TABLE_NAME; ?></span>
						</a>
						<div class="listIconContainer">
							<a href="#tables/<?php echo $table->TABLE_NAME; ?>/insert">
								<com:Icon name="add" size="16" text="core.insertNewRow" />
							</a>
						</div>
					</li>
				<?php } ?>
			</ul>

		</div>
  		<div class="sidebarHeader">
			<a class="icon" href="#views">
				<com:Icon name="view" size="24" text="database.views" />
				<span><?php echo Yii::t('database', 'views') ?></span>
			</a>
		</div>
		<div class="sidebarContent">
			<ul class="list icon nowrap">
				<?php foreach(View::model()->findAllByAttributes(array('TABLE_SCHEMA' => $_GET['schema'])) AS $view) { ?>
					<li>
						<a href="#views/<?php echo $view->TABLE_NAME; ?>/browse" class="icon">
							<com:Icon name="view" size="16" text="database.browse" />
						</a>
						<a href="#views/<?php echo $view->TABLE_NAME; ?>/structure" class="icon">
							<span><?php echo $view->TABLE_NAME; ?></span>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
  		<div class="sidebarHeader">
			<a class="icon">
				<com:Icon name="procedure" size="24" />
				<span>Routines</span>
			</a>
		</div>
		<div class="sidebarContent">
			<ul class="list icon nowrap">
				<?php foreach(Routine::model()->findAllByAttributes(array('ROUTINE_SCHEMA' => $_GET['schema'])) AS $routine) { ?>
					<li>
						<span class="icon">
							<?php if($routine->ROUTINE_TYPE == 'FUNCTION') { ?>
								<com:Icon name="function" size="16" text="database.function" />
							<?php } else { ?>
								<com:Icon name="procedure" size="16" text="database.procecure" />
							<?php } ?>
						</span>
						<span class="icon">
							<span><?php echo $routine->ROUTINE_NAME; ?></span>
						</span>
					</li>
				<?php } ?>
			</ul>
		</div>
  		<div class="sidebarHeader">
			<a class="icon">
				<com:Icon name="bookmark" size="24" text="core.bookmarks" />
				<span><?php echo Yii::t('core', 'bookmarks') ?></span>
			</a>
		</div>
		<div class="sidebarContent">

			<input type="text" id="bookmarkSearch" class="search text" />

			<ul class="list icon nowrap" id="bookmarkList">
			<?php if(Yii::app()->user->settings->get('bookmarks', 'database', $this->schema)) { ?>
				<?php foreach(Yii::app()->user->settings->get('bookmarks', 'database', $this->schema) AS $key=>$bookmark) { ?>
					<li id="bookmark_<?php echo $bookmark['id']; ?>">
						<a href="#bookmark/show/<?php echo $bookmark['id']; ?>" class="icon" title="<?php echo $bookmark['query']; ?>">
							<com:Icon size="16" name="bookmark" />
							<span><?php echo $bookmark['name']; ?></span>
						</a>
						<div class="listIconContainer">
							<a href="javascript:void(0);" onclick="Bookmark.remove('<?php echo $this->schema; ?>', '<?php echo $bookmark['id']; ?>');">
								<com:Icon name="delete" size="16" title="core.delete" disabled={true} />
							</a>
							<a href="javascript:void(0);" onclick="Bookmark.execute('<?php echo $this->schema; ?>', '<?php echo $bookmark['id']; ?>');">
								<com:Icon name="execute" size="16" title="action.execute" />
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