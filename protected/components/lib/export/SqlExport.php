<?php

class SqlExport implements IExport
{

	public static $db;

	public function exportSchema($schema) {

		return $this->fetch('SHOW CREATE SCHEMA :schema', array(
			':schema' => $schema,
		));

	}

	public function exportTable($table) {

		return $this->fetch('SHOW CREATE TABLE :tale', array(
			':table' => $schema,
		));

	}

	public function exportData($table) {

		$data = $this->fetch('SELECT * FROM :table');

		foreach($data AS $row)
		{

		}

	}

	private function fetch($command, $params)
	{

		$cmd = self::$db->createCommand($_cmd);
		$cmd->bindParams($params);

		return $cmd->queryAll();

	}

}

?>