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

<?php Yii::app()->clientScript->registerScript('userSettings', Yii::app()->user->settings->getJsObject(), CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/main.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.layout.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery-ui-1.7.1.custom.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.checkboxTable.js', CClientScript::POS_HEAD); ?>

<script type="text/javascript"> </script>

</head>
<body>

  <div class="ui-layout-north">
	<div id="header">
		<div id="headerLeft">
			<img src="<% echo Yii::app()->request->baseUrl . "/images/logo.png"; %>" />
		</div>
		<div id="headerLogo">
		</div>
		<div id="headerRight">
			<?php $this->widget('application.components.MainMenu',array(
				'items'=>array(
					array('label'=>'Home', 'url'=>array('/site/index'), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Databases', 'url'=>array('/database'), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
					array('label'=>'Logout', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
				),
			)); ?>
		</div>
	</div>
  </div>
  <div class="ui-layout-west">

  <div class="basic" id="MainMenu">
  		<div class="sidebarHeader">
			<a class="icon">
				<img src="images/icons/table_24.png" />
				<span>Tables</span>
			</a>
		</div>
		<div class="sidebarContent">
			<a href="#site/index">ctd1_acc_account</a><br/>
			<a href="#site/login">ctd1_acc_account</a><br/>
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
  		<div class="sidebarHeader">
			<a class="icon">
				<img src="images/icons/table_24.png" />
				<span>Views</span>
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
  		<div class="sidebarHeader">
			<a class="icon">
				<img src="images/icons/script_fav_24.png" />
				<span>Triggers</span>
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