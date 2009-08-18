<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
 * 
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */


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