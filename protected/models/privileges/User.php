<?php

class User extends CActiveRecord
{

	public static $db;
	public $plainPassword;

	public static function splitId($id)
	{
		if(preg_match('/\'(.*)\'@\'(.*)\'$/', $id, $res))
		{
			return array(
				'User' => $res[1],
				'Host' => $res[2],
			);
		}
		else
		{
			return null;
		}
	}

	/**
	 * @see		CActiveRecord::model()
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see		CActiveRecord::getDbConnection()
	 */
	public function getDbConnection()
	{
		return self::$db;
	}

	/**
	 * @see		CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @see		CActiveRecord::primaryKey()
	 */
	public function primaryKey() {
		return array(
			'Host',
			'User',
		);
	}

	public function safeAttributes()
	{
		return array(
			'User',
			'Host',
			'plainPassword',
			'GlobalPrivileges',
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'User' => Yii::t('core', 'username'),
			'Password' => Yii::t('core', 'password'),
			'plainPassword' => Yii::t('core', 'password'),
		);
	}

	private function getPrivilegeColumn($priv)
	{
		switch($priv)
		{
			case 'SHOW DATABASES':
				return 'Show_db_priv';
			case 'CREATE TEMPORARY TABLES':
				return 'Create_tmp_table_priv';
			case 'REPLICATION SLAVE':
				return 'Repl_slave_priv';
			case 'REPLICATION CLIENT':
				return 'Repl_client_priv';
			default:
				return ucfirst(strtolower(str_replace(' ', '_', $priv))) . '_priv';
		}
	}

	public function checkGlobalPrivilege($priv)
	{
		return $this->attributes[$this->getPrivilegeColumn($priv)] == 'Y';
	}

	public function getId()
	{
		return md5($this->User . '@' . $this->Host);
	}

	/**
	 * Returns an array containing all global privileges of the user.
	 *
	 * @return	array					global privileges
	 */
	public function getGlobalPrivileges($group = null)
	{
		$res = array();

		$privs = array_keys(self::getAllGlobalPrivileges($group));

		foreach($privs AS $priv)
		{
			if($this->checkGlobalPrivilege($priv))
			{
				$res[] = $priv;
			}
		}

		if(count($res) == count($privs))
		{
			return array(
				'ALL PRIVILEGES',
			);
		}
		else
		{
			return $res;
		}
	}

	public function setGlobalPrivileges($data)
	{
		// Set all privileges to No
		foreach(array_keys(self::getAllGlobalPrivileges()) AS $priv)
		{
			$this->{$this->getPrivilegeColumn($priv)} = 'N';
		}

		// Set given privileges to Yes
		foreach(array_keys($data) AS $priv)
		{
			$this->{$this->getPrivilegeColumn($priv)} = 'Y';
		}
	}

	public static function getAllGlobalPrivileges($group = null)
	{
		$privs = array(
			'data' => array(
				'SELECT' => null,
				'INSERT' => null,
				'UPDATE' => null,
				'DELETE' => null,
				'FILE' => null,
			),
			'structure' => array(
				'CREATE' => null,
				'ALTER' => null,
				'INDEX' => null,
				'DROP' => null,
				'CREATE TEMPORARY TABLES' => null,
				'SHOW VIEW' => null,
				'CREATE ROUTINE' => null,
				'ALTER ROUTINE' => null,
				'EXECUTE' => null,
				'CREATE VIEW' => null,
			),
			'administration' => array(
				'GRANT' => null,
				'SUPER' => null,
				'PROCESS' => null,
				'RELOAD' => null,
				'SHUTDOWN' => null,
				'SHOW DATABASES' => null,
				'LOCK TABLES' => null,
				'REFERENCES' => null,
				'REPLICATION CLIENT' => null,
				'REPLICATION SLAVE' => null,
				'CREATE USER' => null,
			),
		);

		if($group)
		{
			return $privs[$group];
		}
		else
		{
			return array_merge($privs['data'], $privs['structure'], $privs['administration']);
		}
	}

	protected function afterSave()
	{
		if(!is_null($this->plainPassword))
		{
			$cmd = self::$db->createCommand('UPDATE `user` SET `Password` = PASSWORD(' . self::$db->quoteValue($this->plainPassword) . ')
				WHERE `User` = ' . self::$db->quoteValue($this->User) . '
				AND `Host` = ' . self::$db->quoteValue($this->Host));
			$cmd->execute();
		}
	}

}