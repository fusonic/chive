<?php

switch($this->getType()) {

	case 'shorttext':
		echo CHtml::activeTextField($row, $column->name, array_merge((array)$htmlOptions, array('maxlength'=>$column->size, 'size'=>$column->size, 'class'=>'text')));
		break;

	case 'int':
		echo CHtml::activeTextField($row, $column->name, array('maxlength'=>$column->precision, 'size'=>$column->precision));
		break;

	case 'enum':
		echo CHtml::activeDropDownList($row,$column->name, $this->getEnumValues());
		break;

	case 'text':
		echo CHtml::activeTextArea($row, $column->name, $htmlOptions);
		break;

	case 'datetime':
		echo '<div id="test"></div><script type="text/javascript">$("#test").datepicker();</script>';
		break;

 } ?>