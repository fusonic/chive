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
 echo CHtml::errorSummary($column, false); ?>
<table class="form" style="float: left; margin-right: 20px">
	<colgroup>
		<col class="col1"/>
		<col class="col2" />
		<col class="col3" />
	</colgroup>
	<tbody>
		<tr>
			<td>
				 echo CHtml::activeLabel($column,'COLUMN_NAME'); ?>
			</td>
			<td colspan="2">
				 echo CHtml::activeTextField($column, 'COLUMN_NAME'); ?>
			</td>
		</tr>
		<tr>
			<td>
				 echo CHtml::activeLabel($column, 'dataType'); ?>
			</td>
			<td colspan="2">
				 echo CHtml::activeDropDownList($column, 'dataType', Column::getDataTypes()); ?>
			</td>
		</tr>
		<tr id=" echo CHtml::$idPrefix; ?>settingSize">
			<td>
				 echo CHtml::activeLabel($column, 'size'); ?>
			</td>
			<td colspan="2">
				 echo CHtml::activeTextField($column, 'size'); ?>
			</td>
		</tr>
		<tr id=" echo CHtml::$idPrefix; ?>settingScale">
			<td>
				 echo CHtml::activeLabel($column, 'scale'); ?>
			</td>
			<td colspan="2">
				 echo CHtml::activeTextField($column, 'scale'); ?>
			</td>
		</tr>
		<tr id=" echo CHtml::$idPrefix; ?>settingValues">
			<td>
				 echo CHtml::activeLabel($column, 'values'); ?>
			</td>
			<td colspan="2">
				 echo CHtml::activeTextArea($column, 'values'); ?>
				<div class="small">
					 echo Yii::t('core', 'enterOneValuePerLine'); ?>
				</div>
			</td>
		</tr>
		<tr id=" echo CHtml::$idPrefix; ?>settingCollation">
			<td>
				 echo CHtml::activeLabel($column, 'COLLATION_NAME'); ?>
			</td>
			<td colspan="2">
				 echo CHtml::activeDropDownList($column, 'COLLATION_NAME', CHtml::listData($collations, 'COLLATION_NAME', 'COLLATION_NAME', 'collationGroup')); ?>
			</td>
		</tr>
		<tr id=" echo CHtml::$idPrefix; ?>settingDefault">
			<td>
				 echo CHtml::activeLabel($column, 'COLUMN_DEFAULT'); ?>
			</td>
			<td colspan="2">
				 echo CHtml::activeTextField($column, 'COLUMN_DEFAULT'); ?>
				<div class="small" id=" echo CHtml::$idPrefix; ?>settingDefaultNullHint">
					 echo Yii::t('core', 'leaveEmptyForNull'); ?>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				 echo CHtml::activeLabel($column,'COLUMN_COMMENT'); ?>
			</td>
			<td colspan="2">
				 echo CHtml::activeTextField($column, 'COLUMN_COMMENT'); ?>
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
				 echo Yii::t('core', 'options'); ?>
			</td>
			<td>
				 echo CHtml::activeCheckBox($column, 'isNullable'); ?>
				 echo CHtml::activeLabel($column, 'isNullable'); ?>
			</td>
			<td>
				 echo CHtml::activeCheckBox($column, 'autoIncrement'); ?>
				 echo CHtml::activeLabel($column, 'autoIncrement'); ?>
			</td>
		</tr>
		<tr>
			<td>
				 echo Yii::t('database', 'attribute'); ?>
			</td>
			<td colspan="2">
				 echo CHtml::activeRadioButton($column, 'attribute', array('value' => '', 'id' => CHtml::$idPrefix . 'Column_attribute_')); ?>
				 echo CHtml::label(Yii::t('database', 'noAttribute'), 'Column_attribute_', array('style' => 'font-style: italic')); ?>
			</td>
		</tr>
		<tr>
			<td />
			<td>
				 echo CHtml::activeRadioButton($column, 'attribute', array('value' => 'unsigned', 'id' => CHtml::$idPrefix . 'Column_attribute_unsigned')); ?>
				 echo CHtml::label(Yii::t('database', 'unsigned'), 'Column_attribute_unsigned'); ?>
			</td>
			<td>
				 echo CHtml::activeRadioButton($column, 'attribute', array('value' => 'unsigned zerofill', 'id' => CHtml::$idPrefix . 'Column_attribute_unsignedzerofill')); ?>
				 echo CHtml::label(Yii::t('database', 'unsignedZerofill'), 'Column_attribute_unsignedzerofill'); ?>
			</td>
		</tr>
		<tr>
			<td />
			<td colspan="2">
				 echo CHtml::activeRadioButton($column, 'attribute', array('value' => 'on update current_timestamp', 'id' => CHtml::$idPrefix . 'Column_attribute_on_update_current_timestamp')); ?>
				 echo CHtml::label(Yii::t('database', 'onUpdateCurrentTimestamp'), 'Column_attribute_on_update_current_timestamp'); ?>
			</td>
		</tr>
		 if($column->isNewRecord) { ?>
			<tr id=" echo CHtml::$idPrefix; ?>settingSize">
				<td>
					 echo Yii::t('database', 'createIndex'); ?>
				</td>
				<td>
					 echo CHtml::checkBox('createIndexPrimary', isset($_POST['createIndexPrimary'])); ?>
					 echo CHtml::label(Yii::t('database', 'primaryKey'), 'createIndexPrimary', array('disabled' => $table->getHasPrimaryKey())); ?>
				</td>
				<td>
					 echo CHtml::checkBox('createIndex', isset($_POST['createIndex'])); ?>
					 echo CHtml::label(Yii::t('database', 'index'), 'createIndex'); ?>
				</td>
			</tr>
			<tr id=" echo CHtml::$idPrefix; ?>settingScale">
				<td />
				<td>
					 echo CHtml::checkBox('createIndexUnique', isset($_POST['createIndexUnique'])); ?>
					 echo CHtml::label(Yii::t('database', 'uniqueKey'), 'createIndexUnique'); ?>
				</td>
				<td>
					 echo CHtml::checkBox('createIndexFulltext', isset($_POST['createIndexFulltext'])); ?>
					 echo CHtml::label(Yii::t('database', 'fulltextIndex'), 'createIndexFulltext'); ?>
				</td>
			</tr>
		 } ?>
	</tbody>
</table>