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
<script type="text/javascript">
var isPrimary echo CHtml::$idPrefix; ?> =  echo json_encode($column->getIsPartOfPrimaryKey()); ?>;
</script>

 if($isSubmitted && !$column->isNewRecord) { ?>
	<script type="text/javascript">
	var idPrefix = ' echo CHtml::$idPrefix; ?>';
	var row = $('#' + idPrefix).closest("tr").prev();
	row.attr('id', 'columns_ echo $column->COLUMN_NAME; ?>');
	row.children('td:eq(1)').html(' echo $column->COLUMN_NAME; ?>');
	row.children('td:eq(2)').html( echo json_encode($column->COLUMN_TYPE); ?>);
	row.children('td:eq(3)').html(' echo ($column->COLLATION_NAME ? '<dfn class="collation" title="' . Collation::getDefinition($column->COLLATION_NAME) . '">' . $column->COLLATION_NAME . '</dfn>' : ''); ?>');
	row.children('td:eq(4)').html(' echo Yii::t('core', ($column->isNullable ? 'yes' : 'no')); ?>');
	row.children('td:eq(5)').html(' echo (!is_null($column->COLUMN_DEFAULT) ? $column->COLUMN_DEFAULT : ($column->isNullable ? '<span class="null">NULL</span>' : '')); ?>');
	row.children('td:eq(6)').html(' echo $column->EXTRA; ?>');
	$('#' + idPrefix).parent().slideUp(500, function() {
		$('#' + idPrefix).parents("tr").remove();
	});
	Notification.add('success', ' echo Yii::t('message', 'successEditColumn', array('{col}' => $column->COLUMN_NAME)); ?>', null,  echo json_encode($sql); ?>);
	</script>
 } ?>

 echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		 echo Yii::t('database', ($column->isNewRecord ? 'addColumn' : 'editColumn')); ?>
	</h1>
	 echo $formBody; ?>
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
columnForm.create(' echo CHtml::$idPrefix; ?>');
</script>