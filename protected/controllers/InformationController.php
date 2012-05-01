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


class InformationController extends Controller
{

	public function __construct($id, $module = null)
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			$this->layout = false;
		}

		parent::__construct($id, $module);
	}

	/**
	 * Shows all currently running processes on the MySQL server.
	 */
	public function actionProcesses()
	{
		Yii::app()->getDb()->setActive(true);
		$cmd = Yii::app()->getDb()->createCommand('SHOW PROCESSLIST');
		$processes = $cmd->queryAll();

		$this->render('processes', array(
			'processes' => $processes,
		));
	}

	/**
	 * Kills a process on the server.
	 */
	public function actionKillProcess()
	{
		$ids = CJSON::decode(Yii::app()->getRequest()->getParam('ids'));

		$response = new AjaxResponse();
		$response->refresh = true;

		foreach($ids AS $id)
		{
			$sql = 'KILL ' . $id;

			try
			{
				Yii::app()->getDb()->setActive(true);
				$cmd = Yii::app()->getDb()->createCommand($sql);

				$cmd->prepare();
				$cmd->execute();

				$response->addNotification('success', Yii::t('core', 'successKillProcess', array('{id}' => $id)), null, $sql);
			}
			catch(CDbException $ex)
			{
				$ex = new DbException($cmd);
				$response->addNotification('error', Yii::t('core', 'errorKillProcess', array('{id}' => $id)), $ex->getText(), $sql);
			}

		}

		$this->sendJSON($response);
	}

	/**
	 * Shows all installed storage engines.
	 */
	public function actionStorageEngines()
	{
		$engines = StorageEngine::model()->findAll();

		$this->render('storageEngines', array(
			'engines' => $engines,
		));
	}

	/**
	 * Shows all installed character sets.
	 */
	public function actionCharacterSets()
	{
		$cmd = Yii::app()->getDb()->createCommand('SHOW CHARACTER SET');
		$charactersets = $cmd->queryAll();

		$charsets = array();
		foreach($charactersets AS $set)
		{
			$charsets[$set['Charset']] = $set;
		}

		// Fetch collations into charsets
		$cmd = Yii::app()->getDb()->createCommand('SHOW COLLATION');
		$collations = $cmd->queryAll();

		foreach($collations AS $collation)
		{
			$charsets[$collation['Charset']]['collations'][] = $collation;
		}

		$this->render('characterSets', array(
			'charsets' => $charsets,
		));
	}

	/**
	 * Shows all server variables.
	 */
	public function actionVariables()
	{
		$cmd = Yii::app()->getDb()->createCommand('SHOW GLOBAL VARIABLES');
		$data = $cmd->queryAll();

		$variables = array();
		foreach($data AS $entry)
		{
			$prefix = substr($entry['Variable_name'], 0, strpos($entry['Variable_name'], '_'));
			$variables[$prefix][$entry['Variable_name']] = $entry['Value'];
		}

		$this->render('variables', array(
			'variables' => $variables,
		));
	}

	/**
	 * Shows current server status.
	 */
	public function actionStatus()
	{
		$cmd = Yii::app()->getDb()->createCommand('SHOW GLOBAL STATUS');
		$data = $cmd->queryAll();

		$status = array();
		foreach($data AS $entry)
		{
			$prefix = substr($entry['Variable_name'], 0, strpos($entry['Variable_name'], '_'));
			$status[$prefix][$entry['Variable_name']] = $entry['Value'];
		}

		$this->render('status', array(
			'status' => $status,
		));
	}

	/**
	 * Shows the about page
	 */
	public function actionAbout()
	{
		$this->render('about', array(
		));
	}

}