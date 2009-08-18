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
<div id="dropRoutinesDialog" title=" echo Yii::t('database', 'dropRoutines'); ?>" style="display: none">
	 echo Yii::t('database', 'doYouReallyWantToDropRoutines'); ?>
	<ul></ul>
</div>

<div class="list">
	<div class="buttonContainer">
		<div class="left">
			 $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaRoutines.addProcedure()">
				<com:Icon name="add" size="16" />
				<span> echo Yii::t('database', 'addProcedure'); ?></span>
			</a>
			<a href="javascript:void(0)" class="icon button" onclick="schemaRoutines.addFunction()">
				<com:Icon name="add" size="16" />
				<span> echo Yii::t('database', 'addFunction'); ?></span>
			</a>
		</div>
	</div>

	<table class="list addCheckboxes selectable" id="routines">
		<colgroup>
			<col class="checkbox" />
			<col />
			<col class="action" />
			<col class="action" />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="3"> echo $sort->link('ROUTINE_NAME', Yii::t('database', 'routine')); ?></th>
			</tr>
		</thead>
		<tbody>
			 if($routineCount < 1) { ?>
				<tr>
					<td class="noEntries" colspan="4">
						 echo Yii::t('database', 'noRoutines'); ?>
					</td>
				</tr>
			 } ?>
			 foreach($schema->routines AS $routine) { ?>
				<tr id="routines_ echo $routine->ROUTINE_NAME; ?>">
					<td>
						<input type="checkbox" name="routines[]" value=" echo $routine->ROUTINE_NAME; ?>" />
					</td>
					<td>
						<a href="#views/ echo $routine->ROUTINE_NAME; ?>/structure" class="icon">
							 if($routine->ROUTINE_TYPE == 'PROCEDURE') { ?>
								<com:Icon name="procedure" size="16" text="database.procedure" />
							 } else { ?>
								<com:Icon name="function" size="16" text="database.function" />
							 } ?>
							 echo $routine->ROUTINE_NAME; ?>
						</a>
					</td>
					<td>
						<a href="javascript:void(0);" onclick="schemaRoutines.editRoutine($(this).closest('tr').attr('id').substr(9))" class="icon">
							<com:Icon name="edit" size="16" text="core.edit" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0);" onclick="schemaRoutines.dropRoutine($(this).closest('tr').attr('id').substr(9))" class="icon">
							<com:Icon name="delete" size="16" text="database.drop" />
						</a>
					</td>
				</tr>
			 } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="3"> echo Yii::t('database', 'amountRoutines', array($routineCount, '{amount} '=> $routineCount)); ?></th>
			</tr>
		</tfoot>
	</table>

	<div class="buttonContainer">
		<div class="left withSelected">
			<span class="icon">
				<com:Icon name="arrow_turn_090" size="16" />
				<span> echo Yii::t('core', 'withSelected'); ?></span>
			</span>
			<a href="javascript:void(0)" onclick="schemaRoutines.dropRoutines()" class="icon button">
				<com:Icon name="delete" size="16" />
				<span> echo Yii::t('database', 'drop'); ?></span>
			</a>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaRoutines.addProcedure()">
				<com:Icon name="add" size="16" />
				<span> echo Yii::t('database', 'addProcedure'); ?></span>
			</a>
			<a href="javascript:void(0)" class="icon button" onclick="schemaRoutines.addFunction()">
				<com:Icon name="add" size="16" />
				<span> echo Yii::t('database', 'addFunction'); ?></span>
			</a>
		</div>
	</div>

</div>

<script type="text/javascript">
setTimeout(function() {
	schemaRoutines.setupDialogs();
}, 500);
</script>