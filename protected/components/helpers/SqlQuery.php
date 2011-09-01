<?php

// Import Sql Parser and Compiler
#Yii::import('application.extensions.sqlquery.*');

require_once('sqlquery/SqlParser.php');

class SqlQuery {

	protected $query;

	protected $parsedQuery;
	protected $parsedPMAQuery;
	
	protected $parsedOriginalQuery;
	protected $parsedPMAOriginalQuery;

	protected $comments;
	protected $originalQuery;

	private $resultSetTypes = array(
		'select',
		'show',
		'analyze',
		'repair',
		'check',
		'explain',
	);

	public function __construct($_query) {

		$this->setQuery($_query);
		$this->setOriginalQuery($_query);
		
	}

	/*
	 * Static functions
	 */

	/*
	 * Strips all comments out
	 */
	public static function stripComments($_query) {

		$comments = array();

		// Inline comments
		preg_match_all('/(#|--\40)(.*)($|\n)/i', $_query, $single);
		$comments = $single[0];

		// Other comments
		preg_match_all('/\/\*(.+)\*\//is', $_query, $multi);
		$comments = array_merge($comments, $multi[0]);

		// Strip them
		return str_replace($comments, "\n", $_query);

	}

	/*
	 * MANIPULATION
	 */

	public function applyCalculateFoundRows() {

		$calculateFoundRows = 'SQL_CALC_FOUND_ROWS ';
		
		$newQuery = substr($this->query, 0, strlen("SELECT") + 1 + $this->parsedQuery['position_of_first_select']);
		$newQuery .= $calculateFoundRows;
		$newQuery .= substr($this->query, strlen("SELECT") + 1 + $this->parsedQuery['position_of_first_select']);
		
		$this->setQuery($newQuery);

	}

	public function applyLimit($length, $start=0, $_applyToOriginal = false) {

		$newQuery = $this->parsedQuery['section_before_limit']
						. ' LIMIT ' . $start . ', ' . $length . ' '
						. $this->parsedQuery['section_after_limit'];
						
		$this->setQuery($newQuery);				

		if($_applyToOriginal)
		{
			
			$newOriginalQuery = $this->parsedOriginalQuery['section_before_limit']
					. "\n\t" . 'LIMIT ' . $start . ', ' . $length . ' '
					. $this->parsedOriginalQuery['section_after_limit'];
			
			$this->setOriginalQuery($newOriginalQuery);
		}

	}
	
	private function setQuery($_query)
	{
		$this->query = $_query;
		$this->parsedQuery = SqlParser::parse($_query);
		$this->parsedPMAQuery = SqlParser::parsePMA($_query);
	}
	
	private function setOriginalQuery($_query)
	{
		$this->originalQuery = $_query;
		$this->parsedOriginalQuery = SqlParser::parse($_query);
		$this->parsedPMAOriginalQuery = SqlParser::parsePMA($_query);
	}

	public function applySort($_sorting, $_applyToOriginal = false) {

		$sorting = "";
		$sortingCount = count($_sorting);
		
		foreach($_sorting AS $column => $direction)
		{
			$sorting .= $column . ' ' . $direction;

			if($sortingCount > 1)
				$sorting .= ", ";		
				
			$sortingCount--;
		}

		$query = SqlParser::parse($this->parsedQuery['unsorted_query']);
		
		
		$newQuery = $query['section_before_limit'] 
						.  ' ORDER BY ' . $sorting . ' '
						. $query['limit_clause']
						. $query['section_after_limit'];
						
		$this->setQuery($newQuery);				

		if($_applyToOriginal)
		{
			$query = SqlParser::parse($this->parsedOriginalQuery['unsorted_query']);
		
			$newQuery = $query['section_before_limit']
							. "\n\t" . 'ORDER BY ' . $sorting . ' '
							. "\n\t" . $query['limit_clause']
							. $query['section_after_limit'];
							
			$this->setOriginalQuery($newQuery);				
		}
		
	}


	public function getDatabase() {

		preg_match('/use (\w+)/', $this->query, $res);
		return $res[1];

	}
	
	public function getTable() 
	{
		if($this->parsedQuery != null && is_array($this->parsedQuery['table_ref']) && count($this->parsedQuery['table_ref']) > 0)
		{
			return $this->parsedQuery['table_ref'][0]['table_name'];
		}
		else if(isset($this->parsedQuery['unsorted_query']))
		{
			$maintenanceCommands = array("optimize", "analyze", "repair", "check");
			$pattern = "/[optimize|analyze|repair|check] TABLE `(.*?)`/i";
			$query = $this->parsedQuery['unsorted_query'];

			if(preg_match($pattern, $query, $matches))
			{
				return $matches[1];
			}
		}

		return null;
	}
	
	public function getTables()
	{
		$tables = array();

		if(!isset($this->parsedQuery['table_ref']))
		{
			return $tables;
		}

		foreach($this->parsedQuery['table_ref'] as $table)
		{
			$tables[] = $table['table_name'];
		}
		return $tables;
	}

	private function stripEmptyLines(&$_string)
	{
		$_string = preg_replace('/\n\s*\n/', "\n", $_string);
	}

	/*
	 * Returns the type of the query
	 */
	public function getType()
	{
		return isset($this->parsedQuery['querytype']) ? strtolower($this->parsedQuery['querytype']) : null;
	}

	public function getLimit()
	{
		if($this->parsedQuery['limit_clause'])
		{
			$limit = array();
			
			preg_match('/LIMIT (\d+)(,(\d+))?/im', $this->parsedQuery['limit_clause'], $res);
			if(isset($res[3]))
			{
				$limit['start'] = $res[1];
				$limit['length'] = $res[3]; 
			}
			else
			{
				$limit['start'] = 0;
				$limit['length'] = $res[1];
			}
			
			return $limit;
			
		}
		else
		{
			return false;
		}
		
	}
	
	public function getOrder()
	{
		if($this->parsedQuery['order_by_clause'])
		{
			return $this->parsedQuery['order_by_clause'];
		}
		else
		{
			return false;
		}
	}

	public function returnsResultset()
	{
		return in_array($this->getType(), $this->resultSetTypes);
	}

	public function isUpdatable()
	{
		return $this->returnsResultset() 
			&& isset($this->parsedQuery['queryflags']['select_from'])
			&& isset($this->parsedQuery['queryflags']['select_from']) == 1;	
	}
	
	/*
	 * Returns parsed query
	 */
	public function getQuery()
	{
		return $this->query;
	}

	public function getOriginalQuery()
	{
		return $this->originalQuery;
	}


}