<?php

class UserPrivilegesManager
{

	private $host, $user;
	private $global = array(), $schema = array(), $table = array(), $column = array();

	public function __construct($host, $user)
	{

		$this->host = $host;
		$this->user = $user;

		// Load settings
		$this->loadPrivileges();

	}

	public function loadPrivileges()
	{
		Yii::app()->db->setActive(true);

		$cmdGlobal = Yii::app()->db->createCommand('SELECT DISTINCT PRIVILEGE_TYPE FROM information_schema.USER_PRIVILEGES');
		$global = $cmdGlobal->queryAll();
		$this->global = array();
		foreach($global AS $priv)
		{
			$this->global[] = $priv['PRIVILEGE_TYPE'];
		}

		$cmdSchema = Yii::app()->db->createCommand('SELECT DISTINCT TABLE_SCHEMA, PRIVILEGE_TYPE FROM information_schema.SCHEMA_PRIVILEGES');
		$schema = $cmdSchema->queryAll();
		$this->schema = array();
		foreach($schema AS $priv)
		{
			if(!isset($this->schema[$priv['TABLE_SCHEMA']]))
			{
				$this->schema[$priv['TABLE_SCHEMA']] = array();
			}
			$this->schema[$priv['TABLE_SCHEMA']][] = $priv['PRIVILEGE_TYPE'];
		}

		$cmdTable = Yii::app()->db->createCommand('SELECT DISTINCT TABLE_NAME, TABLE_SCHEMA, PRIVILEGE_TYPE FROM information_schema.TABLE_PRIVILEGES');
		$table = $cmdTable->queryAll();
		$this->table = array();
		foreach($table AS $priv)
		{

			if(!isset($this->table[$priv['TABLE_SCHEMA'] . '.' . $priv['TABLE_NAME']]))
			{
				$this->table[$priv['TABLE_SCHEMA'] . '.' . $priv['TABLE_NAME']] = array();
			}
			$this->table[$priv['TABLE_SCHEMA'] . '.' . $priv['TABLE_NAME']][] = $priv['PRIVILEGE_TYPE'];
		}

		$cmdColumn = Yii::app()->db->createCommand('SELECT DISTINCT COLUMN_NAME, TABLE_NAME, TABLE_SCHEMA, PRIVILEGE_TYPE FROM information_schema.COLUMN_PRIVILEGES');
		$column = $cmdColumn->queryAll();
		$this->column = array();
		foreach($column AS $priv)
		{
			if(!isset($this->column[$priv['TABLE_SCHEMA'] . '.' . $priv['TABLE_NAME'] . '.' . $priv['COLUMN_NAME']]))
			{
				$this->column[$priv['TABLE_SCHEMA']] . '.' . $priv['TABLE_NAME'] . '.' . $priv['COLUMN_NAME'] = array();
			}
			$this->column[$priv['TABLE_SCHEMA'] . '.' . $priv['TABLE_NAME'] . '.' . $priv['COLUMN_NAME']][] = $priv['PRIVILEGE_TYPE'];
		}
	}

	private function escape($value)
	{
		$value = str_replace('.', '\.', $value);
		if(strstr($value, '.'))
		{
			return $value;
		}
		else
		{
			$value = preg_replace('/([^\\\])_/', '$1.', $value);
			$value = preg_replace('/([^\\\])%/', '$1.*', $value);
			$value = str_replace(array('\_', '\%'), array('_', '%'), $value);
			return $value;
		}
	}

	public function checkGlobal($priv)
	{
		return in_array($priv, $this->global);
	}

	public function checkSchema($schema, $priv)
	{
		#if($this->checkGlobal($priv))
		#{
		#	return true;
		#}
		foreach($this->schema AS $scope => $privs)
		{
			$escaped = $this->escape($scope);
			if(preg_match('/^' . $escaped . '$/', $schema))
			{
				return in_array($priv, $privs);
			}
		}
		return false;
	}

	public function checkTable($schema, $table, $priv)
	{
		#if($this->checkSchema($schema, $priv))
		#{
		#	return true;
		#}
		if(isset($this->table[$schema . '.' . $table]))
		{
			return in_array($priv, $this->table[$schema . '.' . $table]);
		}
		else
		{
			return false;
		}
	}

	public function checkColumn($schema, $table, $column, $priv)
	{
		#if($this->checkTable($schema, $table, $priv))
		#{
		#	return true;
		#}
		if(isset($this->column[$schema . '.' . $table . '.' . $column]))
		{
			return in_array($priv, $this->column[$schema . '.' . $table . '.' . $column]);
		}
		else
		{
			return false;
		}
	}

}

?>