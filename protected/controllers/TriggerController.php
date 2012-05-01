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


class TriggerController extends Controller
{
	private static $delimiter = '//';

	public $trigger;
	public $table;
	public $schema;

	public $layout = false;

	public function __construct($id, $module=null)
	{
		$request = Yii::app()->getRequest();

		$this->trigger = $request->getParam('trigger');
		$this->table = $request->getParam('table');
		$this->schema = $request->getParam('schema');

		parent::__construct($id, $module);
		$this->connectDb($this->schema);
	}

	/**
	 * Creates a trigger.
	 */
	public function actionCreate()
	{
		$trigger = new Trigger();

		if(isset($_POST['query']))
		{
			$query = $_POST['query'];
			$cmd = $this->db->createCommand($query);

			try
			{
				$cmd->prepare();
				$cmd->execute();

				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('core', 'successAddTrigger'),
					null,
					$query);
				$response->refresh = true;
				$this->sendJSON($response);
			}
			catch(CDbException $ex)
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				$trigger->addError(null, Yii::t('core', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			}
		}
		else
		{
			$query = 'CREATE TRIGGER ' . $this->db->quoteTableName('name_of_trigger') . "\n"
				. '[AFTER|BEFORE] [INSERT|UPDATE|DELETE] ' . "\n"
				. 'ON ' . $this->db->quoteTableName($this->table) . ' FOR EACH ROW' . "\n"
				. 'BEGIN' . "\n"
				. '-- Definition start' . "\n\n"
				. '-- Definition end' . "\n"
				. 'END';
		}

		CHtml::generateRandomIdPrefix();
		$this->render('form', array(
			'trigger' => $trigger,
			'query' => $query,
		));
	}

	/**
	 * Drops trigger.
	 */
	public function actionDrop()
	{
		// Get post vars
		$triggerName = Yii::app()->request->getPost('trigger');

		$response = new AjaxResponse();
		try
		{
			$trigger = Trigger::model()->findByPk(array(
				'TRIGGER_SCHEMA' => $this->schema,
				'TRIGGER_NAME' => $triggerName,
			));
			$sql = $trigger->delete();
			$response->addNotification('success',
				Yii::t('core', 'successDropTrigger', array('{trigger}' => $trigger->TRIGGER_NAME)),
				null,
				$sql);
			$response->addData('success', true);
		}
		catch(DbException $ex)
		{
			$response->addNotification('error',
				Yii::t('core', 'errorDropTrigger', array('{trigger}' => $triggerName)),
				$ex->getText(),
				$ex->getSql());
			$response->addData('success', false);
		}
		$this->sendJSON($response);
	}

	/**
	 * Updates a trigger.
	 */
	public function actionUpdate()
	{
		$trigger = Trigger::model()->findByPk(array(
			'TRIGGER_SCHEMA' => $this->schema,
			'TRIGGER_NAME' => $this->trigger,
		));
		if(is_null($trigger))
		{
			$trigger = new Trigger();
		}

		if(isset($_POST['query']))
		{
			$query = $_POST['query'];
			try
			{
				// Split queries
				$splitter = new SqlSplitter($query);
				$splitter->delimiter = self::$delimiter;
				$queries = $splitter->getQueries();

				foreach($queries AS $query2)
				{
					$cmd = $this->db->createCommand($query2);
					$cmd->prepare();
					$cmd->execute();
				}

				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('core', 'successAlterTrigger'),
					null,
					$query);
				$response->refresh = true;
				$this->sendJSON($response);
			}
			catch(CDbException $ex)
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				$trigger->addError(null, Yii::t('core', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			}
		}
		else
		{
			$query = 'DROP TRIGGER ' . $this->db->quoteTableName($trigger->TRIGGER_NAME) . self::$delimiter . "\n"
				. $trigger->getCreateTrigger();
		}

		CHtml::generateRandomIdPrefix();
		$this->render('form', array(
			'trigger' => $trigger,
			'query' => $query,
		));
	}

}