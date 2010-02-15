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