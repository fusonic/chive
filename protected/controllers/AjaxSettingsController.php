<?php

class AjaxSettingsController extends CController
{

	public function __construct($id, $module=null) {

		if(Yii::app()->user->isGuest)
		{
			throw new CException(Yii::t('yii','Guests are not allowed to save or retrieve settings.'));
		}

		parent::__construct($id, $module);

	}

	/**
	 * Logout the current user and redirect to homepage.
	 */
	public function actionSet()
	{
		$name = $_POST['name'];
		$scope = (isset($_POST['scope']) ? $_POST['scope'] : null);
		$value = $_POST['value'];
		Yii::app()->user->settings->set($name, $value, $scope);
		Yii::app()->user->settings->saveSettings();
	}

}