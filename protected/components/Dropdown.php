<?php

class Dropdown extends CWidget
{
	public $htmlOptions;

	public $items=array();

	public function run() {

		$items = array();
		foreach($this->items AS $item) {
			$items[] = $item;
		}

		$this->render('dropdown', array(
			'items'=>$items,
			'htmlOptions'=>$this->htmlOptions,
		));
	}

}