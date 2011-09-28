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

class BrowsePage extends CModel
{

	/*
	 * Private properties
	 */
	private $pagination;
	private $hasResultSet;
	private $queryType;
	private $columns;
	private $sort;
	private $data;
	private $response;
	private $isUpdatable = null;
	private $start = 0;
	private $pageSize = 0;
	private $total = 0;
	private $_table;
	
	private $lastResultSetQuery;
	
	private $originalQueries = array();
	private $executedQueries = array();
	
	private $nonEditableSchemas = array(
		'information_schema'
		#'mysql'
	);

	/*
	 * Properties
	 */
	public $query = '';
	public $db;
	public $schema = null;
	public $table = null;
	public $tables = array();
	public $route;
	public $formTarget = '';
	public $singleTableSelect = true;
	
	/*
	 * Settings
	 */
	public $showInput = true;
	public $execute = true;


	public function __construct()
	{
		$this->query = Yii::app()->getRequest()->getParam('query');
	}

	public function attributeNames()
	{
		return array();
	}

	public function safeAttributes()
	{
		return array();
	}
	
	public function run()
	{
		$response = new AjaxResponse();
		
		$profiling = Yii::app()->user->settings->get('profiling');

		try
		{
			$sqlQuery = new SqlQuery($this->query);
		}
		catch(SQPException $ex)
		{
			$response->addNotification('error', 
				Yii::t('core', 'errorExecuteQuery'), 
				$ex->getMessage());
				
			$this->response = $response;
			return;
		}
			
		if(!$this->query)
		{
			$this->query = $this->getDefaultQuery();
			$queries = (array)$this->query;
		}
		else
		{
			if($profiling)
			{
				$cmd = $this->db->createCommand('FLUSH STATUS');
				$cmd->execute();

				$cmd = $this->db->createCommand('SET PROFILING = 1');
				
				$cmd->execute();
			}

			$splitter = new SqlSplitter($this->query);
			$queries = $splitter->getQueries();
		}
		
		if($this->execute)
		{
			$queryCount = count($queries);

			$i = 1;
			foreach($queries AS $query)
			{
				try
				{
					$sqlQuery = new SqlQuery($query);
				}
				catch(SQPException $ex)
				{
					$response->addNotification('error', 
						Yii::t('core', 'errorExecuteQuery'), 
						$ex->getMessage());
					break;
				}
				
				$type = $sqlQuery->getType();

				$this->table = $sqlQuery->getTable();
				
				$this->tables = $sqlQuery->getTables();

				$this->singleTableSelect = count($this->tables) == 1;
				
				// SELECT
				if($type == "select")
				{

					// Pagination
					$pages = new Pagination();
					$pages->route = $this->route;
					$pageSize = $pages->setupPageSize('pageSize', 'schema.table.browse');
					
					// Sorting
					$sort = new Sort($this->db);
					$sort->multiSort = false;
					$sort->route = $this->route;
					
					$sqlQuery->applyCalculateFoundRows();
					
					$limit = $sqlQuery->getLimit();
					$order = $sqlQuery->getOrder();
					
					// Apply sort
					if($sort->getOrder())
					{
						$sqlQuery->applySort($sort->getOrder(), true);
					}
					
					if(isset($_REQUEST['page']))
					{
						$offset = $_REQUEST['page'] * $pageSize - $pageSize;
						$sqlQuery->applyLimit($pageSize, $offset, true);
					}
					
					// Set pagesize from query limit
					if($limit && !isset($_REQUEST[$pages->pageSizeVar]))
					{
						$_REQUEST[$pages->pageSizeVar] = $limit['length'];
						$pageSize = $pages->setupPageSize('pageSize', 'schema.table.browse');
						$offset = $limit['start'];
					}
					// Apply standard limit
					elseif(!$limit)
					{
						$offset = 0;
						$sqlQuery->applyLimit($pageSize, $offset, true);
					}
					
					// New pagesize has been set, apply new pagesize
					elseif(isset($_REQUEST[$pages->pageSizeVar]))
					{
						$pageSize = $pages->setupPageSize('pageSize', 'schema.table.browse');
						$offset = 0;
						$sqlQuery->applyLimit($pageSize, $offset, true);
					}

					$this->start = (int)$offset;
					
				}
				
				// OTHER
				elseif($type == "insert" || $type == "update" || $type == "delete")
				{
					#predie("insert / update / delete statement");
					$response->refresh = true;
					
				}
				elseif($type == "show")
				{
					// show create table etc.

				}
				elseif($type == "explain")
				{
					
				}
				
				elseif($type == "analyze" || $type == "optimize" || $type == "repair" || $type == "check")
				{
					// Table functions
				}
				elseif($type == "use")
				{
					$name = $sqlQuery->getDatabase();
					if($queryCount == 1 && $name && $this->schema != $name)
					{
						$response->redirectUrl = Yii::app()->baseUrl . '/schema/' . $name . '#sql';
						$response->addNotification('success', Yii::t('core', 'successChangeDatabase', array('{name}' => $name)));
					}
				}
				elseif($type == "create")
				{
					$response->reload = true;

				}
				elseif($type == "drop")
				{
					$response->reload = true;
				}
				
				$this->executedQueries[] = $sqlQuery->getQuery();
				$this->originalQueries[] = $sqlQuery->getOriginalQuery();

				if($type == "select")
				{
					$pages->postVars = 
					$sort->postVars = array(
						'query' => $sqlQuery->getOriginalQuery()
					);
				}
				
				// Prepare query for execution
				$cmd = $this->db->createCommand($sqlQuery->getQuery());
				$cmd->prepare();

				if($this->hasResultSet = $sqlQuery->returnsResultSet() !== false)
				{

					try
					{
						// Fetch data
						$start = microtime(true);
						$data = $cmd->queryAll();
						$time = round(microtime(true) - $start, 6);
						
						SqlUtil::FixTable($data);
						
						if($type == 'select')
						{
							$total = (int)$this->db->createCommand('SELECT FOUND_ROWS()')->queryScalar();
							$pages->setItemCount($total);
							$this->total = $total;
							
							$keyData = array();
						}

						$columns = array();

						// Fetch column headers
						if(isset($data[0]))
						{
							$columns = array_keys($data[0]);
						}

						$isSent = true;
						
						$this->lastResultSetQuery = $sqlQuery->getOriginalQuery();

					}
					catch (CDbException $ex)
					{
						$ex = new DbException($cmd);
						$response->addNotification('error', Yii::t('core', 'errorExecuteQuery'), $ex->getText(), $ex->getSql());
					}


				}
				else
				{
					try
					{
						// Measure time
						$start = microtime(true);
						
						$result = $cmd->execute();
						$time = round(microtime(true) - $start, 6);

						$response->addNotification('success', 
						Yii::t('core', 'successExecuteQuery'), 
						Yii::t('core', 'affectedRowsQueryTime', 
							array($result,  '{rows}'=>$result, '{time}'=>$time)), 
							$sqlQuery->getQuery());


					}
					catch(CDbException $ex)
					{
						$dbException = new DbException($cmd);
						$response->addNotification('error', Yii::t('core', 'errorExecuteQuery'), Yii::t('core', 'sqlErrorOccured', array('{errno}'=>$dbException->getNumber(), '{errmsg}'=>$dbException->getText())));
					}

				}

				$i++;

			}

			if($profiling)
			{
				$cmd = $this->db->createCommand('select
						state,
						SUM(duration) as total,
						COUNT(*) AS count
					FROM information_schema.profiling
					GROUP BY state
					ORDER by total desc');

				$cmd->prepare();
				
				$profileData = $cmd->queryAll();
				
				
				if(count($profileData))
				{
					$results = '<table class="profiling">';

					foreach($profileData AS $item)
					{
						$results .= '<tr>';
						
						$results .= '<td class="state">' . ucfirst($item['state']) . '</td>';
						$results .= '<td class="time">' . $item['total'] . '</td>';
						$results .= '<td class="count">(' . $item['count'] . ')</td>';

						$results .= '</tr>';
					}

					$results .= '</table>';

					$response->addNotification('info', Yii::t('core', 'profilingResultsSortedByExecutionTime'), $results, null);
				}
			}
			else if (isset($total) && isset($time))
			{
				$response->addNotification('success',
						Yii::t('core', 'successExecuteQuery'),
						Yii::t('core', 'foundRowsQueryTime',
							array(null,  '{rows}'=>$total, '{time}'=> $time)),
							$sqlQuery->getQuery());

			}
		}
		
		// Assign local variables to class properties
		if(isset($pages))
		{
			$this->pagination = $pages;
		}
		if(isset($sort))
		{
			$this->sort = $sort;
		}
		if(isset($type))
		{
			$this->queryType = $type;
		}
		if(isset($columns))
		{
			$this->columns = $columns;
			foreach($this->columns as $column)
			{
				if($this->getColumn($column) == null)
				{
					$this->singleTableSelect = false;
					break;
				}
			}
		
		}
		if(isset($data))
		{
			$this->data = $data;
		}
		if(isset($response))
		{
			$this->response = $response;
		}
	}

	public function hasResultSet()
	{
		return $this->hasResultSet;
	}

	public function getPagination()
	{
		return $this->pagination;
	}

	public function getSort()
	{
		return $this->sort;
	}

	public function getTable()
	{
		return $this->db->getSchema($this->schema)->getTable($this->table);
	}
	
	public function getTables()
	{
		$tables = array();
		
		foreach($this->tables as $table)
		{
			$tables[] = $this->db->getSchema($this->schema)->getTable($table);	
		}
		
		return $tables;
	}
	
	public function getColumn($key)
	{
		$tables = $this->getTables();
		foreach($tables as $table)
		{
			foreach($table->columns as $index => $column)
			{
				if($key == $index)
				{
					return $column;
				}
			}
		}
		
		return null;
	}

	public function getQueryType()
	{
		return $this->queryType;
	}

	public function getColumns()
	{
		return $this->columns;
	}

	public function getData()
	{
		return $this->data;
	}

	public function getResponse()
	{
		return $this->response;
	}
	
	public function getStart()
	{
		return $this->start;
	}
	
	public function getTotal()
	{
		return $this->total;
	}
	
	public function getKeyData()
	{
		$keyData = array();
		
		$i = 0;
		foreach($this->getData() as $row)
		{
			foreach($row as $key => $value)
			{
				if($this->getIsUpdatable() 
					&& (!$this->hasPrimaryKey() || $this->isPrimaryKey($key))
					&& DataType::getInputType($this->getTable()->columns[$key]->dbType) != "file") 
				{
					$keyData[$i][$key] = is_null($value) ? null : $value;
				}
			}
			$i++;
		}
		
		return $keyData;
	}
	
	private function isPrimaryKey($key)
	{
		return in_array($key, (array)$this->getTable()->primaryKey);
	}
	
	public function hasPrimaryKey()
	{
		return $this->getTable()->primaryKey !== null;
	}
		
	public function getIsUpdatable()
	{
		if($this->isUpdatable === null)
		{
			$this->isUpdatable = false;	

			$table = $this->loadTable();
			if(in_array($this->schema, $this->nonEditableSchemas) || ($table == null || !$table->getIsUpdatable()))
			{
				$this->isUpdatable = false;
				return $this->isUpdatable;
			}

			$query = new SqlQuery($this->lastResultSetQuery);
			$this->isUpdatable = $query->isUpdatable();
			
		}
		
		return $this->isUpdatable;
			
	}

	public function getExecutedQueries()
	{
		return implode(";\n\n", $this->executedQueries);
	}

	public function getOriginalQueries()
	{
		if($this->originalQueries)
			return implode(";\n\n", $this->originalQueries);
		else
			return $this->query;
	}
	
	/*
	 * Private functions
	 */
	private function getDefaultQuery()
	{
		return 'SELECT * FROM ' . $this->db->quoteTableName($this->table) .
			"\n\t" . 'WHERE 1';
	}
	
	/**
	 * Loads the current table.
	 *
	 * @return	Table
	 */
	public function loadTable()
	{
		if(is_null($this->_table) && !is_null($this->table))
		{
			$pk = array(
				'TABLE_SCHEMA' => $this->schema,
				'TABLE_NAME' => $this->table,
			);
			
			$this->_table = Table::model()->findByPk($pk);
			
			if($this->_table->TABLE_TYPE == "VIEW")
			{
				$this->_table = View::model()->findByPk($pk);	
			}
			
			$this->_table->columns = Column::model()->findAllByAttributes($pk);

			if(is_null($this->_table))
			{
				throw new CHttpException(500, 'The requested table does not exist.');
			}
		}
		
		return $this->_table;
	}

}