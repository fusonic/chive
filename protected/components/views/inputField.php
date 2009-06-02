<?php

switch($type) {

	case 'single':
		echo CHtml::activeTextField($row, $column->name, array_merge((array)$htmlOptions, array('maxlength'=>$column->size, 'size'=>$column->size, 'class'=>'text')));
		break;

	case 'number':
		echo CHtml::activeTextField($row, $column->name, array_merge((array)$htmlOptions, array('maxlength'=>$column->precision, 'size'=>$column->precision)));
		break;

	case 'select':
		echo CHtml::activeDropDownList($row, $column->name, $this->getEnumValues(), $htmlOptions);
		break;
		
	case 'select-multiple':
		echo CHtml::activeDropDownList($row,$column->name, $this->getEnumValues());
		break;

	case 'text':
		echo CHtml::activeTextArea($row, $column->name, $htmlOptions);
		break;

	case 'date':
			echo CHtml::activeTextField($row, $column->name, $htmlOptions);
			echo '<script type="text/javascript">
					$(document).ready(function() {
						$("#' . $htmlOptions['id'] . '").datepicker({showOn: "button", dateFormat: "yy-mm-dd"});
					});
					</script>';
		break;

 } ?>
