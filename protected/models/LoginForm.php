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


class LoginForm extends CFormModel
{

	public $username;
	public $password;
	public $rememberMe;
	public $host = 'localhost';
	public $port = '3306';
	public $redirectUrl;

	/**
	 * @see		CFormModel::rules();
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, host', 'required'),
			// port number must be empty or a 16 bit unsigned integer
			array('port', 'numerical',
				'allowEmpty' => true,
				'integerOnly' => true,
				'min' => 1,
				'max' => 65535
			),
			// set default MySQL port if nothing specified
			array('port', 'default',
				'setOnEmpty' => true,
				'value' => 3306
			),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * @see		CFormModel::attributeLabels()
	 */
	public function attributeLabels()
	{
		return array(
			'host'=>Yii::t('core','host'),
			'port'=>Yii::t('core','port'),
			'username'=>Yii::t('core','username'),
			'password'=>Yii::t('core','password'),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$identity = new UserIdentity($this->username,$this->password, $this->host, $this->port);

			if($identity->authenticate())
			{
				Yii::app()->user->login($identity);
			}
			else
			{
				$this->addError(null, $identity->errorMessage);
			}
		}
	}

}
