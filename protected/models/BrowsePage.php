<?php

/**
 * BrowsePage model class
 * BrowsePage is the structure for displaying the "browse" page
 * (i.e. when executing a sql statement)
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
	private $isEditable = null;

	private $lastResultSetQuery;
	
	private $originalQueries = array();
	private $executedQueries = array();

	/*
	 * Properties
	 */
	public $query = '';
	public $db;
	public $schema;
	public $table;
	public $route;
	public $formTarget = '';

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

				$sqlQuery = new SqlQuery($query);
				$type = $sqlQuery->getType();

				// SELECT
				if($type == "select")
				{

					// Pagination
					$pages = new Pagination();
					$pageSize = $pages->setupPageSize('pageSize', 'schema.table.browse');
					$pages->route = $this->route;
					
					// Sorting
					$sort = new Sort($this->db);
					$sort->multiSort = false;
					$sort->route = $this->route;

					$sqlQuery->applyCalculateFoundRows();

					$offset = ($_REQUEST['page'] ? $_REQUEST['page'] : 1) * $pageSize - $pageSize;
					$sqlQuery->applyLimit($pageSize, $offset, true);
					
					/*
					 * Old version
					if($limit)
					{
						$offset = ($_REQUEST['page'] ? $_REQUEST['page'] : 1) * $pageSize - $pageSize;
						$sqlQuery->applyLimit($pageSize, $offset, true);
						
						
					}
					else
					{
						$pages->setPageSize($limit['Length']);
						$pageSize = $limit['Length'];
						Yii::app()->user->settings->set('pageSize', $limit['Length'], 'schema.table.browse');
					}
					*/
					
					// Apply sort
					$order = $sqlQuery->getOrder();
					
					if(!$order)
					{
						$sqlQuery->applySort($sort->getOrder(), true);
					}
					

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
						$response->addNotification('success', Yii::t('message', 'successChangeDatabase', array('{name}' => $name)));
					}
				}
				elseif($type == "create")
				{
					$response->reload = true;

					//$name = $sqlQuery->getTable();
				}
				elseif($type == "drop")
				{
					$response->reload = true;
				}

				$this->executedQueries[] = $sqlQuery->getQuery();
				$this->originalQueries[] = $sqlQuery->getOriginalQuery();
				

				$pages->postVars = array(
					'query' => $sqlQuery->getOriginalQuery()
				);
				
				// Prepare query for execution
				$cmd = $this->db->createCommand($sqlQuery->getQuery());
				$cmd->prepare();

				if($this->hasResultSet = $sqlQuery->returnsResultSet() !== false)
				{

					try
					{
						// Fetch data
						$data = $cmd->queryAll();

						if($type == 'select')
						{
							$total = (int)$this->db->createCommand('SELECT FOUND_ROWS()')->queryScalar();
							$pages->setItemCount($total);
							
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
						$response->addNotification('error', Yii::t('message', 'executingQueryFailed'), $ex->getText(), $ex->getSql());
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

						$response->addNotification('success', Yii::t('message', 'successExecuteQuery'), Yii::t('message', 'affectedRowsQueryTime', array($result,  '{rows}'=>$result, '{time}'=>$time)), $sqlQuery->getQuery());


					}
					catch(CDbException $ex)
					{
						$dbException = new DbException($cmd);
						$response->addNotification('error', Yii::t('message', 'sqlErrorOccured', array('{errno}'=>$dbException->getCode(), '{errmsg}'=>$dbException->getText())));
					}

				}

				$i++;

			}

			if($profiling)
			{
				$cmd = $this->db->createCommand('select
						state,
						SUM(duration) as total,
						count(*)
					FROM information_schema.profiling
					GROUP BY state
					ORDER by total desc');

				$cmd->prepare();
				$profileData = $cmd->queryAll();

				
				if(count($profileData))
				{
					$test = '<table>';

					foreach($profileData AS $item)
					{
						$test .= '<tr>';

						$i = 0;
						foreach($item AS $value)
						{
							$test .= '<td style="padding: 2px; min-width: 80px;">' . ($i == 0 ? '<b>' . ucfirst($value) . '</b>' : $value)  . '</td>';
							$i++;
						}


						$test .= '</tr>';
					}

					$test .= '</table>';

					$response->addNotification('info', 'Profling results (sorted by execution time)', $test, null);
				}

			}

		}
		
		// Assign local variables to class properties
		$this->pagination = $pages;
		$this->sort = $sort;
		$this->queryType = $type;
		$this->columns = $columns;
		$this->data = $data;
		$this->response = $response;
		
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

	public function getIsEditable()
	{
		if($this->isEditable === null)
		{
			$this->isEditable = false;	
				
			$parser = new Sql_Parser();
			
			try 
			{
				$query = $parser->parse($this->query);
				
				if($query['Command'] == "select" &&
						count($query['TableNames']) == 1 &&
						!$query['ColumnAliases'][0] &&
						!$query['Joins'][0]
					)
				{
					$this->isEditable = true;
				}
			}
			catch (Exception $ex)
			{
				var_dump($ex);
				$this->isEditable = false;
			}
			
		}
		
		return $this->isEditable;
			
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

}
