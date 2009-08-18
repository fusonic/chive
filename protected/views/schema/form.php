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
 if($isSubmitted && !$schema->isNewRecord): ?>
	<script type="text/javascript">
	var idPrefix = ' echo CHtml::$idPrefix; ?>';
	var row = $('#' + idPrefix).closest("tr").prev();
	row.find("td dfn.collation").html(" echo $schema->DEFAULT_COLLATION_NAME; ?>").attr("title", " echo Collation::getDefinition($schema->DEFAULT_COLLATION_NAME); ?>");
	$('#' + idPrefix).parent().slideUp(500, function() {
		$('#' + idPrefix).parents("tr").remove();
	});
	Notification.add('success', ' echo Yii::t('message', 'successEditSchema', array('{schema}' => $schema->SCHEMA_NAME)); ?>', null,  echo json_encode($sql); ?>);
	</script>
 endif; ?>

 echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		 echo Yii::t('database', ($schema->isNewRecord ? 'addSchema' : 'editSchema')); ?>
	</h1>
	 echo CHtml::errorSummary($schema, false); ?>
	<table class="form" style="float: left; margin-right: 20px">
		<colgroup>
			<col class="col1"/>
			<col class="col2" />
			<col class="col3" />
		</colgroup>
		<tbody>
			<tr>
				<td>
					 echo CHtml::activeLabel($schema,'SCHEMA_NAME'); ?>
				</td>
				<td colspan="2">
					 echo CHtml::activeTextField($schema, 'SCHEMA_NAME', ($schema->isNewRecord ? array() : array('disabled' =>  true))); ?>
				</td>
			</tr>
			<tr>
				<td>
					 echo CHtml::activeLabel($schema,'DEFAULT_COLLATION_NAME'); ?>
				</td>
				<td colspan="2">
					 echo CHtml::activeDropDownList($schema, 'DEFAULT_COLLATION_NAME', CHtml::listData($collations, 'COLLATION_NAME', 'COLLATION_NAME', 'collationGroup')); ?>
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