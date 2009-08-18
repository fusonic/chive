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
	$(document).ready(function() {
		$('#attributes_ echo CHtml::$idPrefix; ?>').val(' echo json_encode($attributes); ?>');
	});
</script>
 echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<input type="hidden" name="attributes" value="" id="attributes_ echo CHtml::$idPrefix; ?>" />
	<input type="hidden" name="schema" value=" echo $this->schema; ?>" />
	<input type="hidden" name="table" value=" echo $this->table; ?>" />
	<h1>
		 echo Yii::t('database', ($row->isNewRecord ? 'insertRow' : 'editRow')); ?>
	</h1>
	 echo $formBody; ?>
	<div class="buttonContainer">
		<a href="javascript:void(0)" onclick="$('# echo CHtml::$idPrefix; ?>').submit();" class="icon button">
			<com:Icon name="save" size="16" />
			<span> echo Yii::t('action', 'save'); ?></span>
		</a>
		<a href="javascript:void(0)" onclick="$('# echo CHtml::$idPrefix; ?>').slideUp(500, function() { $(this).parents('tr').remove(); })" class="icon button">
			<com:Icon name="delete" size="16" />
			<span> echo Yii::t('action', 'cancel'); ?></span>
		</a>
	</div>
 echo CHtml::endForm(); ?>
