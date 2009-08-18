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


switch($type) {

	case 'single':
		echo CHtml::activeTextField($row, $column->name, array_merge((array)$htmlOptions, array('maxlength'=>$column->size, 'class'=>'text')));
		break;

	case 'number':
		echo CHtml::activeTextField($row, $column->name, array_merge((array)$htmlOptions, array('maxlength'=>$column->precision)));
		break;

	case 'select':
		echo CHtml::activeDropDownList($row, $column->name, $this->getEnumValues(), $htmlOptions);
		break;
		
	case 'select-multiple':
		echo CHtml::activeListBox($row, $column->name, $this->getEnumValues(), array_merge((array)$htmlOptions, array('multiple'=>'multiple')));
		break;

	case 'text':
		echo CHtml::activeTextArea($row, $column->name, array_merge((array)$htmlOptions, array('style'=>'min-width: 500px; min-height: 100px;')));
		break;

	case 'file':
		echo '<script type="text/javascript">
			$(document).ready(function() {
				$("# echo CHtml::$idPrefix; ?>").submit(function() {
					alert("ok1");
					
				});
			});
			</script>';
		echo CHtml::activeFileField($row, $column->name, $htmlOptions);
		break;	
		
	case 'checkbox':
		echo CHtml::activeCheckBox($row, $column->name, $htmlOptions);
		break;	
		
	case 'date':
			echo CHtml::activeTextField($row, $column->name, $htmlOptions);
			echo '<script type="text/javascript">
					$(document).ready(function() {
						$("#' . $htmlOptions['id'] . '").datepicker({showOn: "button", dateFormat: "yy-mm-dd", buttonImage: "' . ICONPATH . '/16/calendar.png' . '", buttonImageOnly: true, buttonText: "' . Yii::t('core', 'showCalendar') . '"});
					});
					</script>';
		break;
		
	case 'datetime':
			echo CHtml::activeTextField($row, $column->name, $htmlOptions);
			echo '<script type="text/javascript">
					$(document).ready(function() {
						now = new Date();
						$("#' . $htmlOptions['id'] . '").datepicker({showOn: "button", dateFormat: "yy-mm-dd " + now.getHours() + ":" + now.getMinutes() + ":00", buttonImage: "' . ICONPATH . '/16/calendar.png' . '", buttonImageOnly: true, buttonText: "' . Yii::t('core', 'showCalendar') . '"});
					});
					</script>';
		break;

 } ?>
