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
 echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	 echo CHtml::hiddenField('type', $type); ?>
	<h1>
		 echo Yii::t('database', ($trigger->isNewRecord ? 'addTrigger' : 'editTrigger')); ?>
	</h1>
	 echo CHtml::errorSummary($trigger, false); ?>
	<com:SqlEditor name="query" value="{$query}" width="95%" height="200px" />
	<div class="buttonContainer">
		<a href="javascript:void(0)" onclick="$('# echo CHtml::$idPrefix; ?>').submit()" class="icon button">
			<com:Icon name="execute" size="16" />
			<span> echo Yii::t('action', 'execute'); ?></span>
		</a>
		<a href="javascript:void(0)" onclick="$('# echo CHtml::$idPrefix; ?>').slideUp(500, function() { $(this).parents('tr').remove(); })" class="icon button">
			<com:Icon name="delete" size="16" />
			<span> echo Yii::t('action', 'cancel'); ?></span>
		</a>
		<a id="aToggleEditor echo CHtml::$idPrefix;?>" class="icon button" href="javascript:void(0);" onclick="toggleEditor(' echo CHtml::$idPrefix;?>query','aToggleEditor echo CHtml::$idPrefix;?>');">
					 if( Yii::app()->user->settings->get('sqlEditorOn') == '1') {?>
						<com:Icon size="16" name="square_green" />
					 } else { ?>
						<com:Icon size="16" name="square_red" />
					 } ?>
					<span> echo Yii::t('core', 'toggleEditor'); ?></span>
				</a>
	</div>
</form>