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
class TabMenu extends CWidget
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

			$item2['htmlOptions'] = isset($item['htmlOptions']) ? $item['htmlOptions'] : array();

			$item2['a']['htmlOptions'] = array();
			$item2['a']['htmlOptions'] = $item['link']['htmlOptions'];


			if(isset($item['icon']))
			{
				$item2['icon']=$item['icon'];

				if(isset($item['htmlOptions']['class']))
				{
					$item2['a']['htmlOptions']['class'] .= ' icon';
				}
				else
				{
					$item2['a']['htmlOptions']['class'] = 'icon';
				}
			}

			$item2['icon'] = isset($item['icon']) ? $item['icon'] : null;
			$item2['a']['href'] = $item['link']['url'];

			if($this->isActive($item['link']['url'], $action->id))
			{
				if(isset($item['htmlOptions']['class']))
				{
					$item2['htmlOptions']['class'] .= ' active';
				}
				else
				{
					$item2['htmlOptions']['class'] = 'active';
				}
			}


			$items[]=$item2;
		}

		$this->render('tabMenu',array('items'=>$items));
	}

	protected function isActive($url,$action)
	{
		if(preg_match('/'.$action.'$/i', $url, $res))
		{
			return (bool)$res[0];
		}
		else
		{
			return false;
		}
	}
}