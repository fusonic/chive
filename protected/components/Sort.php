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
class Sort extends CSort
{

	private $_db;
	private $_directions;
	private static $generateJs = true;
	
	public $postVars = array();
	

	/**
	 * Constructor.
	 * @param string the class name of data models that need to be sorted.
	 * This should be a child class of {@link CActiveRecord}.
	 */
	public function __construct($_db)
	{
		$this->_db = $_db;
	}

	/**
	 * @see 
	 */
	public function getOrder()
	{

		$directions=$this->getDirections();
		
		if(empty($directions))
			$order=$this->defaultOrder;
		else
		{
			$order=array();
			$schema = $this->_db->getSchema();
			foreach($directions as $attribute=>$direction)
			{
				$attribute=$schema->quoteColumnName($attribute);
				$order[$attribute] = strtoupper($direction);
			}
			
		}
		
		return $order;

	}

	/**
	 * Generates a hyperlink that can be clicked to cause sorting.
	 * @param string the attribute name. This must be the actual attribute name, not alias.
	 * If it is an attribute of a related AR object, the name should be prefixed with
	 * the relation name (e.g. 'author.name', where 'author' is the relation name).
	 * @param string the link label. If null, the label will be determined according
	 * to the attribute (see {@link CActiveRecord::getAttributeLabel}).
	 * @param array additional HTML attributes for the hyperlink tag
	 * @return string the generated hyperlink
	 */
	public function link($attribute,$label=null,$htmlOptions=array())
	{
		$directions=$this->getDirections();
		if(isset($directions[$attribute]))
		{
			$direction= $directions[$attribute] == 'asc' ? 'desc' : 'asc';
			unset($directions[$attribute]);
		}
		else
			$direction = 'asc';
			
		if($this->multiSort)
			$directions=array_merge(array($attribute=>$direction),$directions);
		else
			$directions=array($attribute=>$direction);

		if($label===null)
			$label = $attribute;

		$url=$this->createUrl(Yii::app()->getController(),$directions);
		
		if($this->postVars)
		{
			if(self::$generateJs)
			{
				$data = json_encode($this->postVars);
				
				$script = '
					function setSort(_field, _direction) {
					
						var data = ' . $data . ';
						data.'.$this->sortVar.' = _field + "." + _direction; 
					
						$.post("'.BASEURL . '/' . $this->route .'", data, function(responseText) {
							$("div.ui-layout-center").html(responseText);
							init();
						});
					
					}
				';
				
				Yii::app()->getClientScript()->registerScript('Sort_setSort', $script);
				
				self::$generateJs = false;
			}
			
			return CHtml::link($label, 'javascript:void(0)', array(
				'onclick' => 'setSort("' . $attribute . '", "' . $direction . '");',
			));
			
		}
		else
		{
			return $this->createLink($attribute,$label,$url,$htmlOptions);
		}
		

	}

	/**
	 * Returns the currently requested sort information.
	 * @return array sort directions indexed by attribute names.
	 * The sort direction is true if the corresponding attribute should be
	 * sorted in descending order.
	 */
	public function getDirections()
	{
		if($this->_directions===null)
		{
			$this->_directions=array();
			if(isset($_REQUEST[$this->sortVar]))
			{
				$attributes=explode($this->separators[0],$_REQUEST[$this->sortVar]);
				foreach($attributes as $attribute)
				{
					if(($pos=strrpos($attribute,$this->separators[1]))!==false)
					{
						$direction = substr($attribute,$pos+1);
						
						if($direction != 'desc' && $direction != 'asc')
						{
							$direction = 'asc';
						}
						else
							$attribute=substr($attribute,0,$pos);
					}
					else
						$order = 'asc';

					if(($this->validateAttribute($attribute))!==false) {
						$this->_directions[$attribute]=$direction;
					}
				}
				if(!$this->multiSort)
				{
					foreach($this->_directions as $attribute=>$direction)
						return $this->_directions=array($attribute=>$direction);
				}
			}
		}
		
		return $this->_directions;
	}

	/**
	 * Creates a URL that can lead to generating sorted data.
	 * @param CController the controller that will be used to create the URL.
	 * @param array the sort directions indexed by attribute names.
	 * The sort direction is true if the corresponding attribute should be
	 * sorted in descending order.
	 * @return string the URL for sorting
	 */
	public function createUrl($controller,$directions)
	{
		$sorts=array();
		foreach($directions as $attribute=>$direction)
		{
			if(is_array($this->attributes) && isset($this->attributes[$attribute]))
				$attribute=$this->attributes[$attribute];
			$sorts[]= $attribute.$this->separators[1].$direction;
		}
		$params=$_REQUEST;
		$params[$this->sortVar]=implode($this->separators[0],$sorts);
		return $controller->createUrl($this->route,$params);
	}

	/**
	 * Validates an attribute that is requested to be sorted.
	 * The validation is based on {@link attributes} and {@link CActiveRecord::attributeNames}.
	 * False will be returned if the attribute is not allowed to be sorted.
	 * If the attribute is aliased via {@link attributes}, the original
	 * attribute name will be returned.
	 * @param string the attribute name (could be an alias) that the user requests to sort on
	 * @return string the real attribute name. False if the attribute cannot be sorted
	 */
	protected function validateAttribute($attribute)
	{
		return true;
	}

	/**
	 * Creates a hyperlink based on the given label and URL.
	 * You may override this method to customize the link generation.
	 * @param string the name of the attribute that this link is for
	 * @param string the label of the hyperlink
	 * @param string the URL
	 * @param array additional HTML options
	 * @return string the generated hyperlink
	 */
	protected function createLink($attribute,$label,$url,$htmlOptions)
	{
		return CHtml::link($label,$url,$htmlOptions);
	}
}