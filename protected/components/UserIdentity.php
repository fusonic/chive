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
class UserIdentity extends CUserIdentity
{
	/**
	 * @var string host
	 */
	public $host;
	/**
	 * @var int port
	 */
	public $port;

	/**
	 * Constructor.
	 * @param string username
	 * @param string password
	 */
	public function __construct($username,$password,$host,$port)
	{
		$this->username=$username;
		$this->password=$password;
		$this->host=$host;
		$this->port=$port;
	}

	/*
	 * Authenticates the user against database
	 * @return bool
	 */
	public function authenticate()
	{

		$db = new CDbConnection();

		// Set username and password
		$db->username = $this->username;
		$db->password = $this->password;
		$db->emulatePrepare = true;
		$db->connectionString = 'mysql:host=' . $this->host . ';dbname=information_schema;port=' . $this->port;

		try {

			$db->active = true;

			Yii::app()->setComponent('db', $db);

			// Store password in UserIdentity
			$this->setState('password', $this->password);

			// Create settings array
			$this->setState('settings', new UserSettingsManager($this->host, $this->username));
			$this->setState('privileges', new UserPrivilegesManager($this->host, $this->username));
			$this->setState("host", $this->host);
			$this->setState("port", $this->port);

		}
		catch (CDbException $ex)
		{
			$this->errorMessage = $ex->getMessage();
			return false;
		}

		return true;

	}

}
