<?php

class Pagination extends CPagination
{

	public $pageSizeVar = 'pageSize';

	/**
	 * Creates the URL suitable for pagination.
	 * This method is mainly called by pagers when creating URLs used to
	 * perform pagination. The default implementation is to call
	 * the controller's createUrl method with the page information.
	 * You may override this method if your URL scheme is not the same as
	 * the one supported by the controller's createUrl method.
	 * @param CController the controller that will create the actual URL
	 * @param integer the page that the URL should point to. This is a zero-based index.
	 * @return string the created URL
	 */
	public function createPageUrl($controller, $page, $pageSize = null)
	{
		$params=($this->route==='' || $this->route{0} === '#')?array_intersect_assoc($_GET, $_REQUEST):array();
		if($page>0) // page 0 is the default
			$params[$this->pageVar]=$page+1;
		else
			unset($params[$this->pageVar]);
		if($pageSize > 0)
		{
			$params[$this->pageSizeVar] = $pageSize;
		}
		return $controller->createUrl($this->route,$params);
	}

	/**
	 * Setup the page size using a page size setting.
	 *
	 * @param	string				name of the page size setting
	 * @param	string				scope of the page size setting
	 * @return	int					the current page size
	 */
	public function setupPageSize($pageSizeSettingName, $pageSizeSettingScope)
	{
		if(isset($_REQUEST[$this->pageSizeVar]))
		{
			$this->setPageSize((int)$_REQUEST[$this->pageSizeVar]);
			Yii::app()->user->settings->set($pageSizeSettingName, (int)$_REQUEST[$this->pageSizeVar], $pageSizeSettingScope);
		}
		else
		{
			$this->setPageSize(Yii::app()->user->settings->get($pageSizeSettingName, $pageSizeSettingScope));
		}
		return $this->getPageSize();
	}

}