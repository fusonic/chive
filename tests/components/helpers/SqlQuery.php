<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
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
class SqlQueryTest extends TestCase
{

	private $db;

	protected function setUp()
	{
		$this->executeSqlFile('components/helpers/SqlQuery_setup.sql');
		$this->db = new CDbConnection('mysql:host='.DB_HOST.';dbname=sqltest', DB_USER, DB_PASSWORD);
		$this->db->charset='utf8';
		$this->db->active = true;
	}

	public function testSplit()
	{

		$sql = file_get_contents('components/helpers/SqlQuery.sql', null, null, 0, 1000000);

		$splitter = new SqlSplitter($sql);
		$queries = $splitter->getQueries();

		// Unset last query
		unset($queries[count($queries)-1]);

		foreach($queries AS $query)
		{

			$cmd = $this->db->createCommand($query);

			try
			{

				//$cmd->execute();

			}
			catch (Exception $ex)
			{

				$this->fail($ex->getMessage());
				#$this->fail(strrev($query));

			}
		}
		/*
		for($i = 0; $i < 5000; $i++)
		{

			$this->db->createCommand('DROP DATABASE testing_' . $i)->execute();

		}
		*/

		/*
		 * Test 2char delimiter
		 */
		
		$sql = file_get_contents('components/helpers/SqlSplitter.sql');
		
		$splitter = new SqlSplitter($sql);
		$splitter->delimiter = '//';
		
		$queries = $splitter->getQueries();
		
		$this->assertEquals(2, count($queries));

	}

}

?>
