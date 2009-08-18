<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
 * 
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */


class SqlSplitter
{

	public $delimiter = ';';

	private $string;
	private $queries = array();

	public function __construct($_string)
	{
		$this->string = $_string;
	}

	private function split()
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

		for($i = 0; $i <= $chars; $i++)
		{

			$char = $this->string{$i};
			$nextChar = $this->string{$i+1};

			/*
			 * Comments
			 */
			if($this->string{$i-2} . $this->string{$i-1} . $char == '-- ')
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

			if($char == '\'' && $state < 210)
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
					|| $i == $chars
				)
			)
			{
				$query = trim(substr($this->string, $start, $i-$start));

				if($query)
					$this->queries[] = $query;

				$start = $i+1;

				if($delimiterLength && $nextChar == $delimiter{1})
					$start++;

			}

			#echo $i . "\t" . '<b>' . $char . "</b>" . "\t" . (string)$state . '<br/>';

			$prevChar = $char;

		}

	}

	public function getQueries()
	{
		if(!$this->queries)
			$this->split();

		return $this->queries;
	}

}

?>