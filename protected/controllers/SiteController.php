<?php

class SiteController extends Controller
{

	public function __construct($id, $module=null) {

		if(Yii::app()->request->isAjaxRequest)
			$this->layout = false;

		parent::__construct($id, $module);

	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('login', 'changeLanguage', 'changeTheme'),
				'users'=>array('*'),
			),
			array('deny',
				'users'=>array('?'),
			),
		);

	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'

		$tables = array();

		$this->render('index', array('tables'=>$tables));


	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->layout = "login";

		// Languages
		$availableLanguages = FileUtil::readDirectory('protected/messages', false, 'dir');

		$currentLanguage = Yii::app()->getLanguage();

		$languages = array();
		foreach($availableLanguages AS $key=>$language) {

			$full = substr($language, strrpos($language, '/')+1);
			$short = substr($full, 0, 2);

			// Don't display containers and active language
			if($short == $full || $full == $currentLanguage)
				continue;

			$languages[] = array(
				'label'=>Yii::t('language', $full),
				'icon'=>'images/country/' . $short . '.png',
				'url'=>Yii::app()->request->baseUrl . '/site/changeLanguage/' . $full,
				'htmlOptions'=>array('class'=>'icon'),
			);

		}

		$availableThemes = Yii::app()->getThemeManager()->getThemeNames();
		$activeTheme = Yii::app()->getTheme()->getName();

		$themes = array();
		foreach($availableThemes AS $theme) {

			if($activeTheme == $theme)
				continue;

			$themes[] = array(
				'label'=> ucfirst($theme),
				'icon'=> '/themes/' . $theme . '/images/icon.png',
				'url'=>Yii::app()->request->baseUrl . '/site/changeTheme/' . $theme,
				'htmlOptions'=>array('class'=>'icon'),
			);
		}

		// Hosts
		$hosts = array(
			'web'=>'web',
			'localhost'=>'localhost',
			'127.0.0.1'=>'127.0.0.1',
		);

		$form=new LoginForm;
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$form->attributes=$_POST['LoginForm'];
			// validate user input and redirect to previous page if valid
			if($form->validate())
				$this->redirect(Yii::app()->user->returnUrl);
		}

		$this->render('login',array(
			'form'=>$form,
			'languages'=>$languages,
			'hosts'=>$hosts,
			'themes'=>$themes,
		));
	}

	/**
	 * Change the language
	 */
	public function actionChangeLanguage()
	{
		Yii::app()->session->add('language', $_GET['id']);
		$this->redirect(Yii::app()->homeUrl);
	}
	/**
	 * Change the theme
	 */
	public function actionChangeTheme()
	{
		Yii::app()->session->add('theme', $_GET['id']);
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Logout the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}