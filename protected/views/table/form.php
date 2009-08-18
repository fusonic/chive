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
 if($isSubmitted && !$table->isNewRecord) { ?>
	<script type="text/javascript">
	var idPrefix = ' echo CHtml::$idPrefix; ?>';
	var row = $('#' + idPrefix).parents("tr").prev();
	row.attr('id', 'tables_ echo $table->TABLE_NAME; ?>');
	row.children('td:eq(1)').children('a').html(' echo $table->TABLE_NAME; ?>').attr('href', '#tables/ echo $table->TABLE_NAME; ?>/structure');
	row.children('td:eq(2)').children('a').attr('href', '#tables/ echo $table->TABLE_NAME; ?>/browse');
	row.children('td:eq(3)').children('a').attr('href', '#tables/ echo $table->TABLE_NAME; ?>/structure');
	row.children('td:eq(4)').children('a').attr('href', '#tables/ echo $table->TABLE_NAME; ?>/search');
	row.children('td:eq(5)').children('a').attr('href', '#tables/ echo $table->TABLE_NAME; ?>/insert');
	row.children('td:eq(10)').html(' echo $table->ENGINE; ?>');
	row.children('td:eq(11)').children('dfn').html(' echo $table->TABLE_COLLATION; ?>').attr('title', ' echo Collation::getDefinition($table->TABLE_COLLATION); ?>');
	$('#' + idPrefix).parent().slideUp(500, function() {
		$('#' + idPrefix).parents("tr").remove();
	});
	Notification.add('success', ' echo Yii::t('message', 'successEditTable', array('{table}' => $table->TABLE_NAME)); ?>', null,  echo json_encode($sql); ?>);

	// Reload sideBar
	sideBar.loadTables(schema);
	</script>
 } ?>

 echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		 echo Yii::t('database', ($table->isNewRecord ? 'addTable' : 'editTable')); ?>
	</h1>
	 echo CHtml::errorSummary($table, false); ?>
	<table class="form" style="float: left; margin-right: 20px">
		<colgroup>
			<col class="col1"/>
			<col class="col2" />
			<col class="col3" />
		</colgroup>
		<tbody>
			<tr>
				<td>
					 echo CHtml::activeLabel($table, 'TABLE_NAME'); ?>
				</td>
				<td colspan="2">
					 echo CHtml::activeTextField($table, 'TABLE_NAME'); ?>
				</td>
			</tr>
			<tr>
				<td>
					 echo CHtml::activeLabel($table, 'ENGINE'); ?>
				</td>
				<td colspan="2">
					 echo CHtml::activeDropDownList($table, 'ENGINE', CHtml::listData($storageEngines, 'Engine', 'Engine')); ?>
				</td>
			</tr>
			<tr>
				<td>
					 echo CHtml::activeLabel($table, 'TABLE_COLLATION'); ?>
				</td>
				<td colspan="2">
					 echo CHtml::activeDropDownList($table, 'TABLE_COLLATION', CHtml::listData($collations, 'COLLATION_NAME', 'COLLATION_NAME', 'collationGroup')); ?>
				</td>
			</tr>
			<tr>
				<td>
					 echo CHtml::activeLabel($table, 'comment'); ?>
				</td>
				<td colspan="2">
					 echo CHtml::activeTextField($table, 'comment'); ?>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="form">
		<colgroup>
			<col class="col1"/>
			<col class="col2" />
			<col class="col3" />
		</colgroup>
		<tbody>
			<tr>
				<td>
					 echo CHtml::activeLabel($table, 'optionPackKeys'); ?>
				</td>
				<td colspan="2">
					 echo CHtml::activeDropDownList($table, 'optionPackKeys', StorageEngine::getPackKeyOptions()); ?>
				</td>
			</tr>
			<tr>
				<td>
					 echo CHtml::activeLabel($table, 'optionDelayKeyWrite'); ?>
				</td>
				<td colspan="2">
					 echo CHtml::activeCheckBox($table, 'optionDelayKeyWrite'); ?>
				</td>
			</tr>
			<tr>
				<td>
					 echo CHtml::activeLabel($table, 'optionChecksum'); ?>
				</td>
				<td colspan="2">
					 echo CHtml::activeCheckBox($table, 'optionChecksum'); ?>
				</td>
			</tr>
		</tbody>
	</table>
	 if($table->isNewRecord) { ?>
		<h1 style="clear: left">
			 echo Yii::t('database', 'addFirstColumn'); ?>
		</h1>
		 echo $columnForm; ?>
	 } ?>
	<div class="buttonContainer">
		<a href="javascript:void(0)" onclick="$('# echo CHtml::$idPrefix; ?>').submit()" class="icon button">
			<com:Icon name="save" size="16" />
			<span> echo Yii::t('action', 'save'); ?></span>
		</a>
		<a href="javascript:void(0)" onclick="$('# echo CHtml::$idPrefix; ?>').slideUp(500, function() { $(this).parents('tr').remove(); })" class="icon button">
			<com:Icon name="delete" size="16" />
			<span> echo Yii::t('action', 'cancel'); ?></span>
		</a>
	</div>
</form>

<script type="text/javascript">
tableForm.create(' echo CHtml::$idPrefix; ?>');
 if($columnForm) { ?>
	columnForm.create(' echo CHtml::$idPrefix; ?>');
 } ?>
</script>