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
abstract class Controller extends CController
{

	/**
	 * @var CDbConnection
	 */
	protected $db;
	protected $request;

	/**
	 * Connects to the specified schema and assigns it to all models which need it.
	 *
	 * @param	$schema				schema
	 * @return	CDbConnection
	 */
	protected function connectDb($schema)
	{
		// Assign request
		$this->request = Yii::app()->getRequest();

		// Check parameter
		if(is_null($schema))
		{
			$this->db = null;
			return null;
		}

		// Connect to database
		$connectionString = 'mysql:host=' . Yii::app()->user->host . ';port=' . Yii::app()->user->port . ';dbname=' . $schema . '; charset=utf8';
		$this->db = new CDbConnection($connectionString,
			utf8_decode(Yii::app()->user->name),
			utf8_decode(Yii::app()->user->password));
		$this->db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES \'utf8\'');
		$this->db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET CHARACTER SET \'utf8\'');
		$this->db->charset = 'utf8';
		$this->db->emulatePrepare = true;
		$this->db->active = true;
		// Schema name is set in connection string
		// $this->db->createCommand('USE ' . $this->db->quoteTableName($schema))->execute();

		// Assign to all models which need it
		ActiveRecord::$db =
		Routine::$db =
		Row::$db =
		Trigger::$db =
		View::$db = $this->db;

		// Return connection
		return $this->db;
	}

	/**
	 * @see		CController::filters()
	 */
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	/**
	 * @see		CController::accessRules()
	 */
	public function accessRules()
	{
		return array(
			array('deny',
				'users' => array('?'),
			),
		);
	}

	/**
	 * @see CController::createUrl()
	 */
	public function createUrl($route, $params = array(), $ampersand = '&')
	{
		if(strlen($route) >= 0 && $route{0} == '#')
		{
			if(($query = Yii::app()->getUrlManager()->createPathInfo($params, '=', $ampersand)) !== '')
			{
				return $route . '?' . $query;
			}
			else
			{
				return $route;
			}
		}
		else
		{
			return parent::createUrl($route, $params, $ampersand);
		}
	}

	protected function sendJSON($data)
	{
		if($data instanceof AjaxResponse)
		{
			$content = $data->__toString();
		}
		elseif(!is_string($data))
		{
			$content = CJSON::encode($data);
		}
		else
		{
			$content = $data;
		}

		header("Content-type: application/json");
		echo $content;
		Yii::app()->end();
	}

}
