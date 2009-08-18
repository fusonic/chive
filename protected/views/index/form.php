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
 CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3); ?>

 echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		 echo Yii::t('database', ($index->isNewRecord ? 'addIndex' : 'editIndex')); ?>
	</h1>
	 echo CHtml::errorSummary($index, false); ?>
	<table class="form">
		<colgroup>
			<col class="col1"/>
			<col class="col2" />
			<col class="col3" />
		</colgroup>
		<tbody>
			<tr>
				<td>
					 echo CHtml::activeLabel($index,'INDEX_NAME'); ?>
				</td>
				<td colspan="2">
					 echo CHtml::activeTextField($index, 'INDEX_NAME', ($index->getType() == 'PRIMARY' && !$index->getIsNewRecord() ? array('readonly' => true) : '')); ?>
				</td>
			</tr>
			<tr>
				<td>
					 echo CHtml::activeLabel($index, 'type'); ?>
				</td>
				<td colspan="2">
					 echo CHtml::activeDropDownList($index, 'type', $indexTypes, ($index->getIsNewRecord() ? array() : array('disabled' => true))); ?>
				</td>
			</tr>
			<tr>
				<td>
					 echo Yii::t('database', 'columns'); ?>
				</td>
				<td colspan="2">
					<table class="formList" id=" echo CHtml::$idPrefix; ?>columns">
						<colgroup>
							<col />
							<col style="width: 50px" />
							<col class="action" />
							<col class="action" />
						</colgroup>
						<thead>
							<tr>
								<th> echo Yii::t('core', 'name'); ?></th>
								<th colspan="2"> echo Yii::t('core', 'size'); ?></th>
							</tr>
						</thead>
						<tbody class="noItems">
							<tr>
								<td colspan="3">
									 echo Yii::t('database', 'noColumnsAddedYet'); ?>
								</td>
							</tr>
						</tbody>
						<tbody class="content">
							 foreach($index->columns AS $column) { ?>
								<tr>
									<td>
										<input type="hidden" name="columns[]" value=" echo $column->COLUMN_NAME; ?>" />
										 echo $column->COLUMN_NAME; ?>
									</td>
									<td>
										 echo CHtml::textField('keyLengths[' . $column->COLUMN_NAME . ']', $column->SUB_PART, array('class' => 'indexSize')); ?>
									</td>
									<td>
										<a href="javascript:void(0)" class="icon">
											<com:Icon name="arrow_move" size="16" text="core.move" />
										</a>
									</td>
									<td>
										<a href="javascript:void(0)" onclick="indexForm.removeColumn(' echo CHtml::$idPrefix; ?>', this)" class="icon">
											<com:Icon name="delete" size="16" text="core.remove" />
										</a>
									</td>
								</tr>
							 } ?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="3">
									 echo CHtml::dropDownList('addColumn', null, $addColumnData); ?>
								</th>
							</tr>
						</tfoot>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
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
indexForm.create(' echo CHtml::$idPrefix; ?>');
</script>