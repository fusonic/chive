<?php

/**
 * Exception to use if database errors occur.
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
		$this->sql = $sql;
		$this->number = $number;
		$this->text = $text;
		parent::__construct();
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