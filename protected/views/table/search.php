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
<h2> Yii::t('core', 'search'); ?></h2>

<div class="form">
 echo CHtml::form('', 'post', array('id' => 'searchForm')); ?>

<table class="list">
	<colgroup>
		<col style="width: 100px; font-weight: bold;" />
		<col class="type" />
		<col class="operator" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th> echo Yii::t('database', 'field'); ?></th>
			<th> echo Yii::t('database', 'type'); ?></th>
			<th> echo Yii::t('database', 'operator'); ?></th>
			<th> echo Yii::t('core', 'value'); ?></th>
		</tr>
	</thead>
	<tbody>
		 $tabIndex = 10; ?>
		 foreach($row->getMetaData()->tableSchema->columns AS $column) { ?>
			<tr>
				<td> echo $column->name; ?></td>
				<td> echo $column->dbType; ?></td>
				<td> echo CHtml::dropDownList('operator['.$column->name.']','', $operators); ?></td>
				<td>
					 #echo CHtml::activeTextField($row, $column->name, array('class'=>'text', 'tabIndex'=>$tabIndex)); ?>
					 echo CHtml::textField('Row[' . $column->name . ']', '', array('class'=>'text', 'tabIndex'=>$tabIndex)); ?>
				</td>
			</tr>
			 $tabIndex++; ?>
		 } ?>
	</tbody>
</table>

<div class="buttons">
	<a href="javascript:void(0);" onclick="$('form').submit();" class="icon button">
		<com:Icon name="search" size="16" text="core.insert" />
		<span> echo Yii::t('core', 'search'); ?></span>
	</a>
</div>

<script type="text/javascript">
$('#searchForm').ajaxForm({
	success: function(responseText, statusText) {
		AjaxResponse.handle(responseText);
		$('div.ui-layout-center').html(responseText);
		init();
	}
});

$('table.list input:first').focus();

</script>

<input type="submit" name="submit" style="display: none;" />

 echo CHtml::endForm(); ?>