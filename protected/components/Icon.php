<?php

class Icon extends CWidget
{

	public $htmlOptions;
	public $size = 16;
	public $name;
	public $text = "core.unknown";
	public $disabled = false;
	public $title;

	public function run()
	{
		echo $this->getCode();
	}

	public function getCode()
	{
		if(strpos($this->text, 'plain:') === 0)
		{
			$text = substr($this->text, 6);
		}
		else
		{
			list($textCategory, $textVar) = explode('.', $this->text);
			$text = Yii::t($textCategory, $textVar);
		}

		$classes = "icon icon" . $this->size . " icon_" . $this->name . ($this->disabled ? " disabled" : "");
		if(isset($this->htmlOptions['class']))
		{
			$this->htmlOptions['class'] .= " " . $classes;
		}
		else
		{
			$this->htmlOptions['class'] = $classes;
		}

		if(!$this->title)
		{
			$this->title = $this->text;
		}
		if(strpos($this->title, 'plain:') === 0)
		{
			$title = substr($this->title, 6);
		}
		else
		{
			list($titleCategory, $titleVar) = explode('.', $this->title);
			$title = Yii::t($titleCategory, $titleVar);
		}

		$this->htmlOptions += array(
			'class' => $classes,
			'title' => $title,
			'width' => $this->size,
			'height' => $this->size,
		);

		return CHtml::image(ICONPATH . '/' . $this->size . DIRECTORY_SEPARATOR . $this->name . '.png', $text, $this->htmlOptions);
	}

}