<?php

class Sql {

	private $query;

	public $comments;
	public $originalQuery;

	public $hasLimit = false;
	public $limit = null;
	public $offset = 0;

	public function __construct($_sql) {

		$this->query = $this->originalQuery = $_sql;

		self::stripComments();
		self::analyze();

	}


	/*
	 * Strips all comments out
	 */
	public function stripComments() {

		$comments = array();

		// Inline comments
		preg_match_all('/(#|--\40)(.*)($|\n)/i', $this->query, $single);
		$comments = $single[0];

		// Other comments
		preg_match_all('/\/\*(.+)\*\//is', $this->query, $multi);
		$comments = array_merge($comments, $multi[0]);

		// Strip them
		$this->query = str_replace($comments, '', $this->query);

	}

	public function analyze() {


		/*
		 * LIMIT
		 */
		preg_match('/limit\s+(\d+),?\s+?(\d+)?/ims', $this->query, $limit);
		if(isset($limit[1]))
		{
			$this->hasLimit = true;
			if(isset($limit[2]))
			{
				$this->offset = (int)$limit[1];
				$this->limit = (int)$limit[2];
			} else
			{
				$this->limit = (int)$limit[1];
			}

		}

	}

	/*
	 * MANIPULATION
	 */

	public function applyCalculateFoundRows() {

		preg_match('/select\s+(.*)\s+from/i', $this->query, $select);

		if(isset($select[1]))
		{
			$this->query = str_replace($select[1], 'SQL_CALC_FOUND_ROWS ' . $select[1], $this->query);
		}


	}

	public function applyLimit($limit, $offset=0, $_applyToOriginal = false) {

		$this->query .= "\n\t" . 'LIMIT ' . $offset . ', ' . $limit;

		if($_applyToOriginal)
			$this->originalQuery .= "\n\t" . 'LIMIT ' . $offset . ', ' . $limit;

	}

	public function applySort($_sql, $_applyToOriginal = false) {

		$_sql = "\n\t" . trim($_sql);

		preg_match('/\s+?limit\s+(\d+),?\s+?(\d+)?/ims', $this->query, $limit);
		$this->query = str_replace($limit[0], $_sql . $limit[0], $this->query);

		if($_applyToOriginal)
			$this->originalQuery = str_replace($limit[0], $_sql .  $limit[0], $this->originalQuery);

	}


	public function getDatabase() {

		preg_match_all('/use (.+)($|;)/i', $this->query, $db);

		if(isset($db[1]))
		{
			$database = $db[1][count($db[1])-1];
			return trim(str_replace(';', '', $database));
		}
		else
			return null;

	}

	private function stripEmptyLines(&$_string)
	{
		$_string = preg_replace('/\n\s*\n/', "\n", $_string);
	}

	/*
	 * Returns parsed query
	 */
	public function getQuery()
	{
		self::stripEmptyLines($this->query);
		return $this->query;
	}

	public function getOriginalQuery()
	{
		self::stripEmptyLines($this->originalQuery);
		return $this->originalQuery;
	}


}

?>