<?php

class AceEditor extends CInputWidget
{
	public $height = 100;
	public $autogrow = false;

	public function init()
	{
		parent::init();
		list($name, $id) = $this->resolveNameID();

		$config = array(
			"id" => $id,
			"height" => $this->height,
			"autogrow" => $this->autogrow,
		);

		$js = 'window.setTimeout(function() { chive.initAce(' . json_encode($config) . '); }, 1000);';
		Yii::app()->clientScript->registerScript('Yii.AceEditor.' . $this->id, $js, CClientScript::POS_END);
	}

	public function run()
	{
		list($name, $id) = $this->resolveNameID();

		echo CHtml::tag('div', array(
			'id' => $id . '_container',
			'class' => 'editor',
		), false, false);
			echo CHtml::tag('div', array(
				'id' => $id . '_editor',
				'style' => 'height: ' . $this->height . 'px',
			), false, false);
			echo CHtml::closeTag('div');
			echo CHtml::textArea($name, $this->value, array(
				'id' => $id,
				'class' => 'editorTextarea',
			));
		echo CHtml::closeTag('div');
	}
}