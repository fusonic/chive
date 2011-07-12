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


class SqlSplitter
{

	public $delimiter = ';';

	private $string;
	private $queries = array();
	private $queryLength;
	
	private $position = 0;
	
	public $ignoreLastQuery = false;
	
	public function __construct($_string = false)
	{
		if($_string)
		{
			$this->string = $_string;
		}
	}
	
	public function getPosition()
	{
		return $this->position;
	}

	public function split()
	{
		$state = 0;
		$delimiter = $this->delimiter;
		$delimiterLength = strlen($delimiter);

		/*
		 * states:
		 *
		 *     0 initial state
		 *
		 * > 100 strings
		 *
		 *   	100 string active
		 *
		 * > 200 comments
		 *
		 * 		200 single-line comments
		 * 		210 multi-line comments
		 */

		$chars = strlen($this->string);
		$start = 0;
		$length = 0;
		$lastQuote = 0;
		$prevChar = null;

		for($i = 0; $i <= $chars; $i++)
		{

			if($i < $chars)
			{
				$char = $this->string{$i};
			}
			else
			{
				$char = null;
			}
			if($i < $chars - 1)
			{
				$nextChar = $this->string{$i+1};
			}
			else
			{
				$nextChar = null;
			}

			/*
			 * Comments
			 */
			
			// Only look for comments when not in a string
			if($state != 100)
			{
				if($i > 1 && $this->string{$i-2} . $this->string{$i-1} . $char == '-- ')
					$state = 200;
	
				if($prevChar . $char == '/*')
					$state = 210;
	
				if($state == 210 && $prevChar . $char == '*/')
				{
					$state = 0;
					#$start = $i;
				}
	
				if($state == 200 && $char == "\n")
				{
					$state = 0;
					#$start = $i;
				}
				
			}
			
			// Only look for strings when not in a comment
			if($char == '\'' && $state < 200)
			{
				#var_dump($state);

				// STRING start
				if($state == 0)
				{
					$state = 100;
					$lastQuote = $i+1;
				}

				elseif($state == 100)
				{

					$stringPart = substr($this->string, $lastQuote, $i-$lastQuote);

					// No backslash in string, skip testing
					if(!strpos($stringPart, '\\'))
					{
						$state = 0;
					}

					else
					{

						$backSlashCount = 0;
						for($j = strlen($stringPart)-1; $j >= 0; $j--)
						{
							if($stringPart{$j} == '\\')
								$backSlashCount++;
							else
								break;

						}

						if($backSlashCount % 2 == 0)
							$state = 0;
					}

				}

			}

			if($state == 0 &&
				(
					($char == $delimiter{0} &&
						(strlen($delimiter) == 1 ||
						$nextChar == $delimiter{1}))
					|| ($i == $chars && !$this->ignoreLastQuery)
				)
			)
			{
				
				$query = trim(substr($this->string, $start, $i-$start));
				#echo "found query: " . $query . "<br/>";

				if($query) 
				{
					$this->queries[] = $query;
					$this->queryLength[] = $i - $start + strlen($delimiter);
					
					$this->position = $i;
					
				}

				$start = $i+1;

				if($delimiterLength == 2 && $nextChar == $delimiter{1})
					$start++;

			}

			#echo $i . "\t" . '<b>' . $char . "</b>" . "\t" . (string)$state . '<br/>';

			$prevChar = $char;

		}

	}
	
	public function getQueries($_string = false)
	{
		if($_string)
		{
			$this->string = $_string;
			$this->position = 0;
			$this->startPositions = array();
			$this->queries = array();
		}
		
		if(!$this->queries)
			$this->split();

		return $this->queries;
	}

	public function getQueryLength($_i)
	{
		if(isset($this->queryLength[$_i]))
		{
			return $this->queryLength[$_i];
		}
		else
		{
			return 0;
		}
			
	}
	
}

?>