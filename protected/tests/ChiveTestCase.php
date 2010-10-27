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


class ChiveTestCase extends CTestCase
{
	
	const DB_HOST = 'localhost';
	const DB_USER = 'root';
	const DB_PASSWORD = '';
	const DB_NAME = 'chive_fixed';

	protected function executeSqlFile($file)
	{
		echo exec('mysql -h' . ChiveTestCase::DB_HOST . ' -u' . ChiveTestCase::DB_USER . (ChiveTestCase::DB_PASSWORD ? ' -p' . ChiveTestCase::DB_PASSWORD : '') . ' --default-character-set=utf8 <"sql/' . $file . '"');
	}
	
	protected function createDbConnection($dbName)
	{
		$db = new CDbConnection('mysql:host=' . ChiveTestCase::DB_HOST . ';dbname=' . $dbName, ChiveTestCase::DB_USER, ChiveTestCase::DB_PASSWORD);
		$db->emulatePrepare = true;
		$db->charset = 'utf8';
		$db->active = true;
		return $db;
	}

}
