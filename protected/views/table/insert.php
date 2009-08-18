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
<div class="form">
 echo CHtml::form(); ?>

 echo CHtml::errorSummary($row); ?>

<table class="list">
	<colgroup>
		<col style="width: 100px;" />
		<col class="type" />
		<col style="width: 100px;" />
		<col class="checkbox" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th> echo Yii::t('database', 'field'); ?></th>
			<th> echo Yii::t('database', 'type'); ?></th>
			<th> echo Yii::t('database', 'function'); ?></th>
			<th> echo Yii::t('database', 'null'); ?></th>
			<th> echo Yii::t('core', 'value'); ?></th>
		</tr>
	</thead>
	<tbody>
		 foreach($row->getMetaData()->tableSchema->columns AS $column) { ?>
			<tr>
				<td style="font-weight: bold;"> echo $column->name; ?></td>
				<td> echo $column->dbType; ?></td>
				<td> echo CHtml::dropDownList($column->name . '[function]','',$functions); ?></td>
				<td class="center">
					 echo ($column->allowNull ? CHtml::checkBox($column->name.'[null]', true) : ''); ?>
				</td>
				<td>
					 $this->widget('InputField', array('row' => $row, 'column'=>$column, 'htmlOptions'=>array(
						'onfocus' => '$("#'.$column->name.'_null").attr("checked", "").change();',
					))); ?>
				</td>
			</tr>
		 } ?>
	</tbody>
</table>

<div class="buttons">
	<a href="javascript:void(0);" onclick="$('form').submit();" class="icon button">
		<com:Icon name="add" size="16" text="core.insert" />
		<span> echo Yii::t('core', 'insert'); ?></span>
	</a>
</div>

 echo CHtml::endForm(); ?>