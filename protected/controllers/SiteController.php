<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2010 Fusonic GmbH
 *
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */


class SiteController extends Controller
{

	public function __construct($id, $module=null) {

		$request = Yii::app()->getRequest();

		if($request->isAjaxRequest)
		{
			$this->layout = false;
		}

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
	 * @see		CController::accessRules()
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('login', 'changeLanguage', 'changeTheme'),
				'users' => array('*'),
			),
			array('deny',
				'users' => array('?'),
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
        if(Yii::app()->getUrlManager()->showScriptName)
        {
            $scriptUrl = basename($_SERVER['SCRIPT_NAME']);
            if(strpos($_SERVER['REQUEST_URI'], $scriptUrl) === false)
            {
                header("location: index.php");
            }
        }

		$entries = array();

		if(ConfigUtil::getUrlFopen())
		{
			$xml = @simplexml_load_file('http://feeds.launchpad.net/chive/announcements.atom');

			if($xml != null)
			{
				$entries = $xml->entry;
			}
		}

		$this->render('index', array(
			'entries' => $entries,
			'formatter' => Yii::app()->getDateFormatter()
		));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->layout = "login";

		// Languages
        $languages = array();
        $files = opendir(Yii::app()->basePath . DIRECTORY_SEPARATOR . 'messages');
        while($file = readdir($files))
        {
            if(preg_match("/^\w\w(_\w\w)?$/", $file))
            {
                $languages[] = array(
                    'label' => Yii::t('language', $file),
                    'icon' => 'images/language/' . $file . '.png',
                    'url' => Yii::app()->createUrl('/site/changeLanguage/' . $file),
                    'htmlOptions' => array('class'=>'icon'),
                );
            }
        }

		$currentLanguage = Yii::app()->getLanguage();
		if(strlen($currentLanguage) == 2)
		{
			$currentLanguage .= '_' . $currentLanguage;
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

		$form = new LoginForm();
		// collect user input data
		$request = Yii::app()->getRequest();
		if($request->isPostRequest || ($request->getQuery("host") !== null && $request->getQuery("username") !== null))
		{
			if($request->isPostRequest)
			{
				$form->attributes = array(
					"host" => $request->getPost("host"),
					"port" => $request->getPost("port"),
					"username" => $request->getPost("username"),
					"password" => $request->getPost("password")
				);

				$form->redirectUrl = $request->getPost("redirectUrl");
			}
			else
			{
				$form->attributes = array(
					"host" => $request->getQuery("host"),
					"port" => $request->getPost("port"),
					"username" => $request->getQuery("username"),
					"password" => ($request->getQuery("password") !== null ? $request->getQuery("password") : "")
				);
			}
			// validate user input and redirect to previous page if valid
			if($form->validate())
			{
				$redirectUrl = $request->getPost("redirectUrl");
				if($redirectUrl !== null && !StringUtil::endsWith($redirectUrl, "site/login"))
				{
					$this->redirect($redirectUrl);
				}
				else
				{
					$this->redirect(Yii::app()->homeUrl);
				}
			}
		}

		$validBrowser = true;
		if($_SERVER['HTTP_USER_AGENT'])
		{
			preg_match('/MSIE (\d+)\.\d+/i', $_SERVER['HTTP_USER_AGENT'], $res);
			if(count($res) == 2 && $res[1] <= 7)
			{
				$validBrowser = false;
			}
		}

		$this->render('login',array(
			'form'=>$form,
			'languages'=>$languages,
			'hosts'=>$hosts,
			'themes'=>$themes,
			'validBrowser' => $validBrowser,
		));
	}

	public function actionKeepAlive()
	{
		Yii::app()->end('OK');
	}

	/**
	 * Change the language
	 */
	public function actionChangeLanguage()
	{
		Yii::app()->session->add('language', Yii::app()->getRequest()->getParam('id'));
		$this->redirect(Yii::app()->createUrl('site/login'));
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

	public function actionSearch()
	{

		$cmdBuilder = new CDbCommandBuilder(Yii::app()->db->getSchema());

		$criteria = new CDbCriteria;
		$criteria->condition = "TABLE_NAME LIKE :table OR TABLE_SCHEMA LIKE :schema";
		$criteria->params = array(
			":table"=>"%" . Yii::app()->getRequest()->getParam('q') . "%",
			":schema"=>"%" . Yii::app()->getRequest()->getParam('q') . "%"
		);
		$criteria->order = 'TABLE_SCHEMA, TABLE_NAME';

		$items = array();

		$lastSchemaName = '';
		foreach(Table::model()->findAll($criteria) AS $table)
		{
			if($table->TABLE_SCHEMA != $lastSchemaName)
			{
				$items[] = CJSON::encode(array(
					'text' => '<span class="icon schema">' . Html::icon('database') . '<span>' . StringUtil::cutText($table->TABLE_SCHEMA, 30) . '</span></span>',
					'target' => Yii::app()->createUrl('schema/' . $table->TABLE_SCHEMA),
					'plain' => $table->TABLE_SCHEMA,
				));
			}

			$lastSchemaName = $table->TABLE_SCHEMA;

			$items[] = CJSON::encode(array(
				'text' => '<span class="icon table">' . Html::icon('table') . '<span>' . StringUtil::cutText($table->TABLE_NAME, 30) . '</span></span>',
				'target' => Yii::app()->createUrl('schema/' . $table->TABLE_SCHEMA) . '#tables/' . $table->TABLE_NAME . '/browse',
				'plain' => $table->TABLE_NAME
			));
		}

		Yii::app()->end(implode("\n", $items));

	}

}
