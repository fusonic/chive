<?php

class SiteController extends CController
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
				'actions' => array('login')
			),
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'expression' => !Yii::app()->user->isGuest,
			),
			array('deny',  // deny all users
				'users'=>array('*'),
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

		$this->render('index');


	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->layout = "login";


		// Languages
		$availableLanguages = array(
			'de'=>'Deutsch',
			'en'=>'English',
			'nl'=>'Dutch',
		);

		$languages = array();
		foreach($availableLanguages AS $key=>$language) {

			$languages[] = array(
				'label'=>Yii::t('language', $language),
				'icon'=>'images/country/' . $key . '.png',
				'url'=>Yii::app()->request->baseUrl . '/language/' . $key,
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
		// display the login form

		$this->render('login',array(
			'form'=>$form,
			'languages'=>$languages,
			'hosts'=>$hosts,
		));
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