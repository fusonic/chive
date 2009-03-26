<?php

class DatabaseTreeView extends CTreeView
{
	public $items=array();

	public function init()
	{

		// Add ordering
		$criteria = new CDbCriteria;
		$criteria->order = "SCHEMA_NAME ASC";

		$databases = Database::model()->findAll($criteria);

		$dbSeperator = "_";
		$items = $root = $children = $items = array();

		foreach($databases AS $database) {

			// Find prefix in name
			if($position = strpos($database->getName(), $dbSeperator)) {
				$prefix = substr($database->getName(), 0, $position);
				$root[$prefix] = $prefix;
				$children[$prefix][] = substr($database->getName(), $position+1);
			}
			else
				$root[] = $database->getName();

		}

		$i = 0;
		foreach($root AS $key=>$item) {

			$childs = array();

			if(count($children[$item]) > 1) {

				foreach($children[$item] AS $child) {

					$childs[] = array(
						'text' => CHtml::link($child, DatabaseController::createUrl("/database/show/" . $item . $dbSeperator . $child)),
					);

				}
			} else {

				$name = $item . $dbSeperator . $children[$item][0];

				$items[] = array(
					'text' => CHtml::link($name, DatabaseController::createUrl("/database/show/" . $name)),
				);

				$i++;

				continue;

			}

			$items[] = array(
				'text' => (count($childs) == 0 ? CHtml::link($item, DatabaseController::createUrl("database/show/" . $item)) : $item),
				'expanded' => $i == 0 ? true : false,
				'children' => $childs,
			);

			$i++;

		}

		$this->data = $items;
		parent::init();

	}
}