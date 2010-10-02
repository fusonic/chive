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
class DbException extends CDbException {

	private $sql, $number, $text;

	/**
	 * Constructor
	 *
	 * @param	string				the sql statement
	 * @param	int					sql error number
	 * @param	string				sql error text
	 */
	public function __construct($sql = null, $number = null, $text = null)
	{
		if($sql instanceof CDbCommand)
		{
			$this->sql = $sql->getText();
			$errorInfo = $sql->getPdoStatement()->errorInfo();
			$this->number = $errorInfo[1];
			$this->text = $errorInfo[2];
		}
		else
		{
			$this->sql = $sql;
			$this->number = $number;
			$this->text = $text;
		}
		parent::__construct($this->text);
	}

	/**
	 * Returns sql statement.
	 *
	 * @return	string				the sql statement
	 */
	public function getSql()
	{
		return $this->sql;
	}

	/**
	 * Returns sql error number.
	 *
	 * @return	int					sql error number
	 */
	public function getNumber()
	{
		return $this->number;
	}

	/**
	 * Returns sql error text.
	 *
	 * @return	string				sql error text
	 */
	public function getText()
	{
		return $this->text;
	}

}

?>