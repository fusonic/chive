<?php
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

		//$this->render('databaseTableTree',array('items'=>$items));
	}
}