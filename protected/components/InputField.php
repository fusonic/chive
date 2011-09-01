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


class InputField extends CWidget
{

	public $column;
	public $row;
	public $htmlOptions;
	public $value;
	public $id;
	
	private $fixedHtmlOptions = array(
		'number' =>				array(),
		'select' =>				array(),
		'file' =>				array(),
		'select' =>				array(),
		'date' =>				array(),
		'datetime' =>			array(),
		'select-multiple' => 	array('multiple'=>'multiple'),
		'text' => 				array('style'=>'min-width: 500px; min-height: 100px;'),
		'single' => 			array('class'=>'text'),
	);
	
	public function run()
	{

		$type = DataType::getInputType($this->column->dbType);		
		$this->htmlOptions += $this->fixedHtmlOptions[$type];
		$column = $this->column->name;
		
		$name = isset($this->htmlOptions['name']) ? $this->htmlOptions['name'] : 'Row[' . $column . ']';
		
		switch($type) {
	
			case 'number':
				echo CHtml::activeTextField($this->row, $column, $this->htmlOptions);
				break;
		
			case 'select':
				echo CHtml::activeDropDownList($this->row, $column, $this->getEnumValues(), $this->htmlOptions);
				break;
				
			case 'select-multiple':
				#echo CHtml::activeListBox($this->row, $column, $this->getSetValues(), $this->htmlOptions);
				echo CHtml::listBox($name, $this->row->getAttributeAsArray($column), $this->getSetValues(),  $this->htmlOptions);
				break;
		
			case 'text':
				echo CHtml::activeTextArea($this->row, $column, $this->htmlOptions);
				break;
		
			case 'file':
				echo '<script type="text/javascript">
					$(document).ready(function() {
						$("# echo CHtml::$idPrefix; ?>").submit(function() {
							alert("ok1");
							
						});
					});
					</script>';
				echo CHtml::activeFileField($this->row, $column, $this->htmlOptions);
				break;	
				
			case 'date':
				$this->SetDateTimeHtmlOptions($column);
				echo CHtml::activeTextField($this->row, $column, $this->htmlOptions);
				echo '<script type="text/javascript">
						$(document).ready(function() {
							$("#' . $this->htmlOptions['id'] . '").datepicker({showOn: "button", dateFormat: "yy-mm-dd", buttonImage: "' . ICONPATH . '/16/calendar.png' . '", buttonImageOnly: true, buttonText: "' . Yii::t('core', 'showCalendar') . '"});
						});
						</script>';
				break;
				
			case 'datetime':
				$this->SetDateTimeHtmlOptions($column);
				echo CHtml::activeTextField($this->row, $column, $this->htmlOptions);
				echo '<script type="text/javascript">
						$(document).ready(function() {
							now = new Date();
							$("#' . $this->htmlOptions['id'] . '").datepicker({showOn: "button", dateFormat: "yy-mm-dd " + now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds(), buttonImage: "' . ICONPATH . '/16/calendar.png' . '", buttonImageOnly: true, buttonText: "' . Yii::t('core', 'showCalendar') . '"});
						});
						</script>';
				break;
				
			default:
				echo CHtml::activeTextField($this->row, $column, $this->htmlOptions);
				break;
		}
		
	}
	
	private function SetDateTimeHtmlOptions($column)
	{
		$this->htmlOptions += array("id" => mt_rand(1000, 10000) . "_" . $column);
	}

	public function getEnumValues()
	{
		$type = preg_match('/\(\'(.+)\'\)/i', $this->column->dbType, $res);
		$values = explode('\',\'', $res[1]);

		$return = array();
		foreach($values AS $value)
		{
			$return[$value] = $value;
		}

		return $return;
	}
	
	public function getSetValues() 
	{
		$type = preg_match('/\(\'(.+)\'\)/i', $this->column->dbType, $res);
		$values = explode('\',\'', $res[1]);

		$return = array();
		foreach($values AS $value)
		{
			$return[$value] = $value;
		}

		return $return;
	}


}