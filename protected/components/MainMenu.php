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

/**
 * MainMenu is a widget displaying main menu items.
 *
 * The menu items are displayed as an HTML list. One of the items
 * may be set as active, which could add an "active" CSS class to the rendered item.
 *
 * To use this widget, specify the "items" property with an array of
 * the menu items to be displayed. Each item should be an array with
 * the following elements:
 * - visible: boolean, whether this item is visible;
 * - label: string, label of this menu item. Make sure you HTML-encode it if needed;
 * - url: string|array, the URL that this item leads to. Use a string to
 *   represent a static URL, while an array for constructing a dynamic one.
 * - pattern: array, optional. This is used to determine if the item is active.
 *   The first element refers to the route of the request, while the rest
 *   name-value pairs representing the GET parameters to be matched with.
 *   When the route does not contain the action part, it is treated
 *   as a controller ID and will match all actions of the controller.
 *   If pattern is not given, the url array will be used instead.
 */
class MainMenu extends CWidget
{
	public $items=array();

	public function run()
	{

		$items=array();
		$controller=$this->controller;
		$action=$controller->action;
		foreach($this->items as $item)
		{
			if(isset($item['visible']) && !$item['visible'])
				continue;
			$item2=array();
			$item2['label']=$item['label'];
			if(is_array($item['url']))
				$item2['url']=$controller->createUrl($item['url'][0]);
			else
				$item2['url']=$item['url'];
			$pattern=isset($item['pattern'])?$item['pattern']:$item['url'];

			$item2['htmlOptions'] = isset($item['htmlOptions']) ? $item['htmlOptions'] : array();

			if($active = $this->isActive($pattern,$controller->uniqueID,$action->id))
				$item2['htmlOptions']['class'] = "active";

			if(isset($item['icon']))
			{
				$item2['icon']=$item['icon'];

				if($item2['htmlOptions']['class'])
				{
					$item2['htmlOptions']['class'].=' icon';
				}
				else
				{
					$item2['htmlOptions']['class']=' icon';
				}

			}
			$item2['icon'] = isset($item['icon']) ? $item['icon'] : null;
			$item2['htmlOptions']['href'] = $item2['url'];

			$items[]=$item2;
		}
		$this->render('mainMenu',array('items'=>$items));
	}

	protected function isActive($pattern,$controllerID,$actionID)
	{
		if(!is_array($pattern) || !isset($pattern[0]))
			return false;

		$pattern[0]=trim($pattern[0],'/');
		if(strpos($pattern[0],'/')!==false)
			$matched=$pattern[0]===$controllerID.'/'.$actionID;
		else
			$matched=$pattern[0]===$controllerID;

		if($matched && count($pattern)>1)
		{
			foreach(array_splice($pattern,1) as $name=>$value)
			{
				if(!isset($_GET[$name]) || $_GET[$name]!=$value)
					return false;
			}
			return true;
		}
		else
			return $matched;
	}
}