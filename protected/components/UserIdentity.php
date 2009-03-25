<?php

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

	public function authenticate()
	{

		$db = Yii::app()->db;

		// Set username and password
		$db->username = $this->username;
		$db->password = $this->password;

		try {

			$db->active = true;
			$this->errorCode = self::ERROR_NONE;

			// Store password in UserIdentity
			$this->setState("password", $this->password);

		} catch (Exception $ex) {

			$this->errorCode = self::ERROR_AUTHENTICATION_FAILED;
			$this->errorMessage = $ex->getMessage();

		}

		return !$this->errorCode;

	}

}
