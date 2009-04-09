<?php

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