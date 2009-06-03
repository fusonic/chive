<?php

/**
 * Base controller for this project. Adds Ajax functionality.
 */
class Controller extends CController
{

	private $_db;

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

?>