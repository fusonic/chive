<?php

/**
 * Base controller for this project.
 *
 * Adds the following functionality:
 * * Ajax URLs (Pagination, Sorting, ...)
 * * Database connection
 */
class Controller extends CController
{

	protected $db;
	protected $request;

	/**
	 * Connects to the specified schema and assigns it to all models which need it.
	 *
	 * @param	$schema				schema
	 * @return	CDbConnection
	 */
	protected function connectDb($schema)
	{
		// Assign request
		$this->request = Yii::app()->getRequest();

		// Check parameter
		if(is_null($schema))
		{
			$this->db = null;
			return null;
		}

		// Connect to database
		$this->db = new CDbConnection('mysql:host=' . Yii::app()->user->host . ';dbname=information_schema',
			utf8_decode(Yii::app()->user->name),
			utf8_decode(Yii::app()->user->password));
		$this->db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES \'utf8\'');
		$this->db->charset='utf8';
		$this->db->active = true;
		$this->db->createCommand('USE ' . $this->db->quoteTableName($schema))->execute();

		// Assign to all models which need it
		ActiveRecord::$db =
		Routine::$db =
		Row::$db =
		Trigger::$db =
		View::$db = $this->db;

		// Return connection
		return $this->db;
	}

	/**
	 * @see		CController::filters()
	 */
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	/**
	 * @see		CController::accessRules()
	 */
	public function accessRules()
	{
		return array(
			array('deny',
				'users' => array('?'),
			),
		);
	}

	/**
	 * @see CController::createUrl()
	 */
	public function createUrl($route, $params = array(), $ampersand = '&')
	{
		if($route{0} == '#')
		{
			if(($query = CUrlManager::createPathInfo($params, '=', $ampersand)) !== '')
			{
				return $route . '?' . $query;
			}
			else
			{
				return $route;
			}
		}
		else
		{
			return parent::createUrl($route, $params, $ampersand);
		}
	}

}
