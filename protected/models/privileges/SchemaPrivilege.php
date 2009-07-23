<?php

class SchemaPrivilege extends CActiveRecord
{

	public static $db;

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
		return 'db';
	}

	/**
	 * @see		CActiveRecord::primaryKey()
	 */
	public function primaryKey() {
		return array(
			'Host',
			'Db',
			'User',
		);
	}

	/**
	 * @see		CActiveRecord::safeAttributes()
	 */
	public function safeAttributes()
	{
		return array(
			'Host',
			'User',
			'Db',
			'Privileges',
		);
	}

	/**
	 * @see		CActiveRecord::relations()
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
			'Db' => Yii::t('database', 'schema'),
		);
	}

	private function getPrivilegeColumn($priv)
	{
		switch($priv)
		{
			case 'CREATE TEMPORARY TABLES':
				return 'Create_tmp_table_priv';
			default:
				return ucfirst(strtolower(str_replace(' ', '_', $priv))) . '_priv';
		}
	}

	public function checkPrivilege($priv)
	{
		return $this->attributes[$this->getPrivilegeColumn($priv)] == 'Y';
	}

	/**
	 * Returns an array containing all privileges of the user.
	 *
	 * @return	array					privileges
	 */
	public function getPrivileges($group = null)
	{
		$res = array();

		$privs = array_keys(self::getAllPrivileges($group));

		foreach($privs AS $priv)
		{
			if($this->checkPrivilege($priv))
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

	public function setPrivileges($data)
	{
		// Set all privileges to No
		foreach(array_keys(self::getAllPrivileges()) AS $priv)
		{
			$this->{$this->getPrivilegeColumn($priv)} = 'N';
		}

		// Set given privileges to Yes
		foreach(array_keys($data) AS $priv)
		{
			$this->{$this->getPrivilegeColumn($priv)} = 'Y';
		}
	}

	public static function getAllPrivileges($group = null)
	{
		$privs = array(
			'data' => array(
				'SELECT' => null,
				'INSERT' => null,
				'UPDATE' => null,
				'DELETE' => null,
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
				'LOCK TABLES' => null,
				'REFERENCES' => null,
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

}