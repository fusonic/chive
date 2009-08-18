<div id="dropRoutinesDialog" title="<?php echo Yii::t('database', 'dropRoutines'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToDropRoutines'); ?>
	<ul></ul>
</div>

<div class="list">
	<div class="buttonContainer">
		<div class="left">
			<?php $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaRoutines.addProcedure()">
				<com:Icon name="add" size="16" />
				<span><?php echo Yii::t('database', 'addProcedure'); ?></span>
			</a>
			<a href="javascript:void(0)" class="icon button" onclick="schemaRoutines.addFunction()">
				<com:Icon name="add" size="16" />
				<span><?php echo Yii::t('database', 'addFunction'); ?></span>
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
				<th colspan="3"><?php echo $sort->link('ROUTINE_NAME', Yii::t('database', 'routine')); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if($routineCount < 1) { ?>
				<tr>
					<td class="noEntries" colspan="4">
						<?php echo Yii::t('database', 'noRoutines'); ?>
					</td>
				</tr>
			<?php } ?>
			<?php foreach($schema->routines AS $routine) { ?>
				<tr id="routines_<?php echo $routine->ROUTINE_NAME; ?>">
					<td>
						<input type="checkbox" name="routines[]" value="<?php echo $routine->ROUTINE_NAME; ?>" />
					</td>
					<td>
						<a href="#views/<?php echo $routine->ROUTINE_NAME; ?>/structure" class="icon">
							<?php if($routine->ROUTINE_TYPE == 'PROCEDURE') { ?>
								<com:Icon name="procedure" size="16" text="database.procedure" />
							<?php } else { ?>
								<com:Icon name="function" size="16" text="database.function" />
							<?php } ?>
							<?php echo $routine->ROUTINE_NAME; ?>
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
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="3"><?php echo Yii::t('database', 'amountRoutines', array($routineCount, '{amount} '=> $routineCount)); ?></th>
			</tr>
		</tfoot>
	</table>

	<div class="buttonContainer">
		<div class="left withSelected">
			<span class="icon">
				<com:Icon name="arrow_turn_090" size="16" />
				<span><?php echo Yii::t('core', 'withSelected'); ?></span>
			</span>
			<a href="javascript:void(0)" onclick="schemaRoutines.dropRoutines()" class="icon button">
				<com:Icon name="delete" size="16" />
				<span><?php echo Yii::t('database', 'drop'); ?></span>
			</a>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaRoutines.addProcedure()">
				<com:Icon name="add" size="16" />
				<span><?php echo Yii::t('database', 'addProcedure'); ?></span>
			</a>
			<a href="javascript:void(0)" class="icon button" onclick="schemaRoutines.addFunction()">
				<com:Icon name="add" size="16" />
				<span><?php echo Yii::t('database', 'addFunction'); ?></span>
			</a>
		</div>
	</div>

</div>

<script type="text/javascript">
setTimeout(function() {
	schemaRoutines.setupDialogs();
}, 500);
</script>