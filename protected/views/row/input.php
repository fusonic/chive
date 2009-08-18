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
 $id = StringUtil::getRandom(10); ?>

 echo CHtml::form(BASEURL . '/row/update', 'POST', array('id' => 'form_' . $id)); ?>

<div style="padding: 2px 10px;">

	 echo CHtml::hiddenField('schema', $this->schema); ?>
	 echo CHtml::hiddenField('table', $this->table); ?>
	 echo CHtml::hiddenField('column', $column->name); ?>
	 echo CHtml::hiddenField('attributes', json_encode($attributes)); ?>
	 echo CHtml::hiddenField('isNull', 0, array('id' => 'isNull')); ?>

	 $this->widget('InputField', array('row' => $row, 'column'=>$column, 'htmlOptions' => array(
		'id' => 'input_' . $id,
		'autocomplete' => 'off',
		'name' => 'value',
	))); ?>
	
	<script type="text/javascript">

		$('#form_<? echo $id; ?>').ajaxForm({
			success:	function(response) {

							editing = false;
							responseObj = JSON.parse(response);
							
							if(responseObj.data.error) {
								reset();
								AjaxResponse.handle(response);
								return false;
							}
				
							$('#input_ echo $id; ?>').parent().parent().html(responseObj.data.visibleValue);
							keyData[rowIndex] = responseObj.data.identifier;
							AjaxResponse.handle(response);
							
						}
		});

		function reset() {
			$('#form_ echo $id; ?>').parent().html( echo json_encode($oldValue); ?>); 
			editing = false;
		}
	
		$('#input_ echo $id; ?>').select().focus();
		editing = ' echo $id; ?>';

	</script>
	
	<div class="buttonContainer" style="width: 300px;">
	
		<a href="javascript:void(0);" onclick="$('#form_ echo $id; ?>').submit();" class="icon button primary">
			<com:Icon name="save" size="16" text="core.save" />
			<span> echo Yii::t('core', 'save'); ?></span>
		</a>
		<a href="javascript:void(0);" onclick="reset();" class="icon button">
			<com:Icon name="delete" size="16" text="core.cancel" />
			<span> echo Yii::t('core', 'cancel'); ?></span>
		</a>
		 if($column->allowNull) { ?>
			 echo Yii::t('core', 'or'); ?>
			<a href="javascript:void(0);" onclick="$('#isNull').val(1); $('#form_ echo $id; ?>').submit();" class="icon button">
				<com:Icon name="null" size="16" text="core.null" />
				<span> echo Yii::t('core', 'setNull'); ?></span>
			</a>
		 } ?>
	</div>
	
</div>

 echo CHtml::endForm(); ?>