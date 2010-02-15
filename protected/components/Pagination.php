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


class Pagination extends CPagination
{

	public $pageSizeVar = 'pageSize';
	
	public $postVars;
	
	private $_currentPage;

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
	
	/**
	 * @param boolean whether to recalculate the current page based on the page size and item count.
	 * @return integer the zero-based index of the current page. Defaults to 0.
	 */
	public function getCurrentPage($recalculate=true)
	{
		if($this->_currentPage===null || $recalculate)
		{
			if(isset($_REQUEST[$this->pageVar]))
			{
				$this->_currentPage=(int)$_REQUEST[$this->pageVar]-1;
				$pageCount=$this->getPageCount();
				if($this->_currentPage>=$pageCount)
					$this->_currentPage=$pageCount-1;
				if($this->_currentPage<0)
					$this->_currentPage=0;
			}
			else
				$this->_currentPage=0;
		}
		
		return $this->_currentPage;
	}

	/**
	 * @param integer the zero-based index of the current page.
	 */
	public function setCurrentPage($value)
	{
		$this->_currentPage=$value;
		$_REQUEST[$this->pageVar]=$value+1;
	}

}