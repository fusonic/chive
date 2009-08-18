<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
 * 
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */


/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	const ERROR_AUTHENTICATION_FAILED = 3;

	/**
	 * @var string host
	 */
	public $host;

	/**
	 * Constructor.
	 * @param string username
	 * @param string password
	 */
	public function __construct($username,$password,$host)
	{
		$this->username=$username;
		$this->password=$password;
		$this->host=$host;
	}

	public function authenticate()
	{

		$db = new CDbConnection();

		// Set username and password
		$db->username = $this->username;
		$db->password = $this->password;
		$db->connectionString = 'mysql:host=' . $this->host . ';dbname=information_schema';

		try {

			$db->active = true;

			Yii::app()->setComponent('db', $db);

			$this->errorCode = self::ERROR_NONE;

			// Store password in UserIdentity
			$this->setState('password', $this->password);

			// Create settings array
			$this->setState('settings', new UserSettingsManager($this->host, $this->username));
			$this->setState('privileges', new UserPrivilegesManager($this->host, $this->username));
			$this->setState("host", $this->host);

		}
		catch (Exception $ex)
		{
			$this->errorCode = self::ERROR_AUTHENTICATION_FAILED;
			$this->errorMessage = $ex->getMessage();
		}

		return !$this->errorCode;

	}

}
