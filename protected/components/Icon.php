<?php

class Icon extends CWidget
{

	public $htmlOptions;
	public $size = 16;
	public $name;
	public $text = "core.unknown";
	public $disabled = false;

	public function run() {

		list($category, $var) = explode(".", $this->text);

		$classes = "icon icon" . $this->size . ($this->disabled ? " disabled" : "");
		if(isset($this->htmlOptions['class']))
		{
			$this->htmlOptions['class'] .= " " . $classes;
		}
		else
		{
			$this->htmlOptions['class'] = $classes;
		}

		echo CHtml::image(Yii::app()->baseUrl . DIRECTORY_SEPARATOR . Yii::app()->params->iconpack . DIRECTORY_SEPARATOR . $this->size . DIRECTORY_SEPARATOR . $this->name . ".png", Yii::t($category, $var), $this->htmlOptions);

	}

}