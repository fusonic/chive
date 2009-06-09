<?php

class AjaxSettingsController extends Controller
{
	/**
	 * Action to set a setting via Ajax.
	 */
	public function actionSet()
	{
		$name = $_POST['name'];
		$scope = (isset($_POST['scope']) ? $_POST['scope'] : null);
		$object = (isset($_POST['object']) ? $_POST['object'] : null);
		$value = $_POST['value'];
		Yii::app()->user->settings->set($name, $value, $scope, $object);
		Yii::app()->user->settings->saveSettings();
	}

	/**
	 * Action to add a value to an array setting.
	 */
	public function actionAdd()
	{
		$name = $_POST['name'];
		$scope = (isset($_POST['scope']) ? $_POST['scope'] : null);
		$object = (isset($_POST['object']) ? $_POST['object'] : null);
		$value = $_POST['value'];

		$oldValue = Yii::app()->user->settings->get($name, $scope, $object);

		if($oldValue && !is_array($oldValue))
		{
			$oldValue = (array)$oldValue;
		}

		$oldValue[] = $value;

		Yii::app()->user->settings->set($name, $oldValue, $scope, $object);
		Yii::app()->user->settings->saveSettings();
	}

	/**
	 * Action to toggle a boolean setting (invert old value).
	 */
	public function actionToggle()
	{
		$name = $_POST['name'];
		$scope = (isset($_POST['scope']) ? $_POST['scope'] : null);
		$object = (isset($_POST['object']) ? $_POST['object'] : null);

		$oldValue = Yii::app()->user->settings->get($name, $scope, $object);

		Yii::app()->user->settings->set($name, !$oldValue, $scope, $object);
		Yii::app()->user->settings->saveSettings();
	}
}