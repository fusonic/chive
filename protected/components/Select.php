<?php

class Select extends CWidget
{
	public $htmlOptions;

	public $items=array();

	public function run() {

		$items = array();

		foreach($this->items AS $item) {
			$items[] = $item;
		}

		$this->render('select', array(
			'items'=>$items,
			'htmlOptions'=>$this->htmlOptions,
		));
	}

}