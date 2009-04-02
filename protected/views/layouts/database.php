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

<script type="text/javascript">
	// Set global javascript variables
	var baseUrl = '<%= Yii::app()->baseUrl; %>';
</script>

<?php Yii::app()->clientScript->registerScript('userSettings', Yii::app()->user->settings->getJsObject(), CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/main.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.layout.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.tableForm.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery-ui-1.7.1.custom.min.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.checkboxTable.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.form.js', CClientScript::POS_HEAD); ?>


</head>
<body>

  <div id="loading"><%= Yii::t('core', 'loading'); %>...</div>

  <div class="ui-layout-north">
	<div id="header">
		<div id="headerLeft">
			<ul class="breadCrumb">
				<li id="bc_root">
					<a href="http://www.example.com/dublin" style="float:left; margin-right: 5px;">
						<img src="<%= Yii::app()->baseUrl . "/images/logo.png"; %>" />
					</a>
				</li>
				<?php if(isset($_GET['schema'])) { ?>
					<li id="bc_schema">
						<span>&raquo;</span>
						<a class="icon" href="<%= Yii::app()->baseUrl %>/database/<%= $_GET['schema'] %>">
							<com:Icon name="database" size="24" />
							<span><?php echo $_GET['schema']; ?></span>
						</a>
					</li>
				<?php } ?>
				<li id="bc_table" style="display: none;">
					<span>&raquo;</span>
					<a class="icon" href="<%= Yii::app()->baseUrl %>/database/<%= $_GET['schema'] %>">
						<com:Icon name="table" size="24" />
						<span>test</span>
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
					array('label'=>'Refresh','icon'=>'refresh', 'url'=>array(), 'htmlOptions'=>array('onclick'=>'return reload();'), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Databases', 'icon'=>'server', 'url'=>array('/#databases'), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
					array('label'=>'Logout', 'icon'=>'logout', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
				),
			)); ?>
		</div>
	</div>
  </div>
  <div class="ui-layout-west">

  <div class="basic" id="MainMenu">
  		<div class="sidebarHeader">
			<a class="icon">
				<com:Icon name="table" size="24" text="database.tables" />
				<span><?php echo Yii::t('database', 'tables'); ?></span>
			</a>
		</div>
		<div class="sidebarContent">

			<ul class="list icon">
				<?php foreach(Table::model()->findAll(array('select'=>'TABLE_NAME, TABLE_ROWS', 'condition'=>'TABLE_SCHEMA=:schema', 'params'=>array(':schema'=>$_GET['schema']), 'order'=>'TABLE_NAME ASC')) AS $table) { ?>
					<li class="nowrap">
						<a href="#tables/<%= $table->getName(); %>/<?php echo ($table->getRowCount() ? 'browse' : 'structure'); ?>">
							<?php $this->widget('Icon', array('name'=>'browse', 'size'=>16, 'disabled'=>!$table->getRowCount(), 'title'=>Yii::t('database', 'amountRows', array('{amount}'=>$table->getRowCount() ? $table->getRowCount() : 0)))); ?>
						</a>
						<a href="#tables/<%= $table->getName(); %>/structure">
							<span><?php echo $table->getName(); ?></span>
						</a>
					</li>
				<?php } ?>
			</ul>

		</div>
  		<div class="sidebarHeader">
			<a class="icon">
				<com:Icon name="view" size="24" text="database.views" />
				<span><%= Yii::t('database', 'views') %></span>
			</a>
		</div>
		<div class="sidebarContent">
			<ul class="select">
				<?php foreach(View::model()->findAll(array('select'=>'TABLE_NAME','condition'=>'TABLE_SCHEMA=:schema', 'params'=>array(':schema'=>$_GET['schema']), 'order'=>'TABLE_NAME ASC')) AS $table) { ?>
					<li class="nowrap">
						<%= CHtml::openTag('a', array('href'=>'#tables/'.$table->getName().'/browse')); %>
							<com:Icon name="browse" size="16" text="core.username" />
						<%= CHtml::closeTag('a'); %>
						<%= CHtml::openTag('a', array('href'=>'#tables/'.$table->getName().'/structure')); %>
							<span><?php echo $table->getName(); ?></span>
						<%= CHtml::closeTag('a'); %>
					</li>
				<?php } ?>
			</ul>
		</div>
  		<div class="sidebarHeader">
			<a class="icon">
				<img src="images/icons/table_24.png" />
				<span>Procedures</span>
			</a>
		</div>
		<div class="sidebarContent">
			ctd1_acc_account<br/>
			ctd1_acc_bonuspayment<br/>
			ctd1_acc_payment<br/>
			ctd1_acc_transaction<br/>
			ctd1_acp_group<br/>
			ctd1_acp_link<br/>
			ctd1_acp_subgroup<br/>
			ctd1_adm_admedia<br/>
			ctd1_adm_admedia2campaign<br/>
			ctd1_aff_website<br/>
			ctd1_cat_category<br/>
			ctd1_cat_category2module<br/>
			ctd1_cmm_commissionmodel<br/>
			ctd1_cmm_objective<br/>
			ctd1_cmp_campaign<br/>
			ctd1_cmp_campaign2object<br/>
			ctd1_com_mail<br/>
			ctd1_com_mailqueue<br/>
			ctd1_com_mailvariable<br/>
			ctd1_com_notification<br/>
			ctd1_com_notificationsetting<br/>
			ctd1_com_notificationvariable<br/>
			ctd1_frp_request<br/>
			ctd1_frp_visitor<br/>
			ctd1_lng_pack<br/>
			ctd1_lng_value<br/>
			ctd1_nav_link<br/>
			ctd1_nav_navigation<br/>
			ctd1_nwl_newsletter<br/>
			ctd1_pcr_page<br/>
			ctd1_pcr_page2object<br/>
			ctd1_reg_invitation<br/>
			ctd1_reg_term<br/>
			ctd1_rep_report<br/>
			ctd1_shp_integration<br/>
			ctd1_sty_attribute2color<br/>
			ctd1_sty_attribute2style<br/>
			ctd1_sty_box<br/>
			ctd1_sty_box2layout<br/>
			ctd1_sty_box2object<br/>
			ctd1_sty_boxclose<br/>
			ctd1_sty_boxtab<br/>
			ctd1_sty_layout<br/>
			ctd1_sty_predefboxtab<br/>
			ctd1_sty_predefboxtabsetting<br/>
			ctd1_sty_predefboxtabsetting2boxtab<br/>
			ctd1_sys_accesscontrol<br/>
			ctd1_sys_country<br/>
			ctd1_sys_forgotpw<br/>
			ctd1_sys_group<br/>
		</div>
	</div>
  </div>
  <div class="ui-layout-center">
  	<% echo $content; %>
  </div>

</body>
</html>