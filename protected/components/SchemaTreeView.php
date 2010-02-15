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


class SchemaTreeView extends CTreeView
{
	public $items=array();

	public function init()
	{

		// Add ordering
		$criteria = new CDbCriteria;
		$criteria->order = "SCHEMA_NAME ASC";

		$schemata = Schema::model()->findAll($criteria);

		$dbSeperator = "_";
		$items = $root = $children = $items = array();

		foreach($schemata AS $schema) {

			// Find prefix in name
			if($position = strpos($schema->SCHEMA_NAME, $dbSeperator)) {
				$prefix = substr($schema->SCHEMA_NAME, 0, $position);
				$root[$prefix] = $prefix;
				$children[$prefix][] = substr($schema->SCHEMA_NAME, $position+1);
			}
			else
				$root[] = $schema->SCHEMA_NAME;

		}

		$i = 0;
		foreach($root AS $key=>$item) {

			$childs = array();
			$childrenCount = count($children[$item]);

			if($childrenCount > 1)
			{

				foreach($children[$item] AS $child) {

					$childs[] = array(
						'text' => CHtml::link($child, SchemaController::createUrl("/schema/" . $item . $dbSeperator . $child)),
					);

				}
			}
			else
			{

				if($childrenCount == 1)
					$name = $item . $dbSeperator . $children[$item][0];
				else
					$name = $item;

				$items[] = array(
					'text' => CHtml::link($name, SchemaController::createUrl("/schema/" . $name)),
				);

				$i++;
				continue;

			}

			$items[] = array(
				'text' => ($childrenCount == 0 ? CHtml::link($item, SchemaController::createUrl("/schema/" . $item)) : $item),
				'expanded' => $i == 0 ? true : false,
				'children' => $childs,
			);

			$i++;

		}

		$this->data = $items;
		parent::init();

	}
}