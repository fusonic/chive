<?php

class User extends ActiveRecord
{
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
		return '\'' . $this->User . '\'@\'' . $this->Host . '\'';
	}

	public function getDomId()
	{
		return md5($this->getId());
	}

	/**
	 * Returns an array containing all global privileges of the user.
	 *
	 * @return	array					global privileges
	 * @return	array					do not summarize to ALL PRIVILEGES
	 */
	public function getGlobalPrivileges($group = null, $notAllPrivileges = false)
	{
		$res = array();

		// Retrieve all privileges
		$privs = array_keys(self::getAllGlobalPrivileges($group));

		// Check all privileges for this user
		foreach($privs AS $priv)
		{
			if($this->checkGlobalPrivilege($priv))
			{
				$res[] = $priv;
			}
		}

		// Return USAGE if user has no privileges
		if(count($res) == 0)
		{
			return array(
				'USAGE',
			);
		}
		elseif(count($res) == 1 && $res[0] == 'GRANT')
		{
			return array(
				'USAGE',
				'GRANT',
			);
		}

		if($group || $notAllPrivileges)
		{
			// Return result if we are only looking for a group
			return $res;
		}
		else
		{
			// Remove GRANT privilege from privs array
			$resWithoutGrant = array_diff($res, array('GRANT'));

			// Compare privilege count
			if(count($resWithoutGrant) == count($privs) - 1)
			{
				// User has ALL PRIVILEGES
				$userPrivs = array(
					'ALL PRIVILEGES',
				);
				// Also check GRANT privilege
				if(array_search('GRANT', $res) !== false)
				{
					$userPrivs[] = 'GRANT';
				}
				return $userPrivs;
			}
			else
			{
				// User doesn't have ALL PRIVILEGES
				return $res;
			}
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

	/**
	 * @see		ActiveRecord::getInsertSql()
	 */
	protected function getInsertSql()
	{
		$privileges = $this->getGlobalPrivileges();
		$canGrant = array_search('GRANT', $privileges);

		return 'GRANT ' . implode(', ', array_diff($privileges, array('GRANT'))) . "\n"
			. "\tON *.*\n"
			. "\tTO " . self::$db->quoteValue($this->User) . '@' . self::$db->quoteValue($this->Host)
			. ($this->plainPassword !== null ? "\n\tIDENTIFIED BY " . self::$db->quoteValue($this->plainPassword) : '')
			. ($canGrant ? "\n\tWITH GRANT OPTION" : '') . ';';
	}

	/**
	 * @see		ActiveRecord::getUpdateSql()
	 */
	protected function getUpdateSql()
	{
		// Rename user or not
		if($this->originalAttributes['User'] != $this->User || $this->originalAttributes['Host'] != $this->Host)
		{
			$sql = array(
				'RENAME USER ' . self::$db->quoteValue($this->originalAttributes['User']) . '@' . self::$db->quoteValue($this->originalAttributes['Host'])
					. ' TO ' . self::$db->quoteValue($this->User) . '@' . self::$db->quoteValue($this->Host) . ';',
			);
		}
		else
		{
			$sql = array();
		}

		// Revoke global privileges
		$sql[] = 'REVOKE ALL PRIVILEGES' . "\n"
			. "\tON *.*\n"
			. "\tFROM " . self::$db->quoteValue($this->User) . '@' . self::$db->quoteValue($this->Host) . ';';

		// Revoke global grant option
		$sql[] = 'REVOKE GRANT OPTION' . "\n"
			. "\tON *.*\n"
			. "\tFROM " . self::$db->quoteValue($this->User) . '@' . self::$db->quoteValue($this->Host) . ';';

		// Grant new privileges
		$sql[] = $this->getInsertSql();

		return $sql;
	}

	/**
	 * @see		ActiveRecord::getDeleteSql()
	 */
	protected function getDeleteSql()
	{
		return 'DROP USER ' . self::$db->quoteValue($this->User) . '@' . self::$db->quoteValue($this->Host) . ';';
	}

	public function getDbConnection()
	{
		return self::$db;
	}

}