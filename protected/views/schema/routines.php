<div id="dropRoutinesDialog" title="<?php echo Yii::t('core', 'dropRoutines'); ?>" style="display: none">
	<?php echo Yii::t('core', 'doYouReallyWantToDropRoutines'); ?>
	<ul></ul>
</div>

<div class="list">
	<div class="buttonContainer">
		<div class="left">
			<?php $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaRoutines.addProcedure()">
				<?php echo Html::icon('add'); ?>
				<span><?php echo Yii::t('core', 'addProcedure'); ?></span>
			</a>
			<a href="javascript:void(0)" class="icon button" onclick="schemaRoutines.addFunction()">
				<?php echo Html::icon('add'); ?>
				<span><?php echo Yii::t('core', 'addFunction'); ?></span>
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
				<th colspan="3"><?php echo $sort->link('ROUTINE_NAME', Yii::t('core', 'routine')); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if($routineCount < 1) { ?>
				<tr>
					<td class="noEntries" colspan="4">
						<?php echo Yii::t('core', 'noRoutines'); ?>
					</td>
				</tr>
			<?php } ?>
			<?php foreach($schema->routines AS $routine) { ?>
				<tr id="routines_<?php echo $routine->ROUTINE_NAME; ?>">
					<td>
						<input type="checkbox" name="routines[]" value="<?php echo $routine->ROUTINE_NAME; ?>" />
					</td>
					<td>
						<span class="icon">
							<?php if($routine->ROUTINE_TYPE == 'PROCEDURE') { ?>
								<?php echo Html::icon('procedure', 16, false, 'core.procedure'); ?>
							<?php } else { ?>
								<?php echo Html::icon('function', 16, false, 'core.function'); ?>
							<?php } ?>
							<?php echo $routine->ROUTINE_NAME; ?>
						</span>
					</td>
					<td>
						<a href="javascript:void(0);" onclick="schemaRoutines.editRoutine($(this).closest('tr').attr('id').substr(9))" class="icon">
							<?php echo Html::icon('edit', 16, false, 'core.edit'); ?>
						</a>
					</td>
					<td>
						<a href="javascript:void(0);" onclick="schemaRoutines.dropRoutine($(this).closest('tr').attr('id').substr(9))" class="icon">
							<?php echo Html::icon('delete', 16, false, 'core.drop'); ?>
						</a>
					</td>
				</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="3"><?php echo Yii::t('core', 'amountRoutines', array($routineCount, '{amount} '=> $routineCount)); ?></th>
			</tr>
		</tfoot>
	</table>

	<div class="buttonContainer">
		<div class="left withSelected">
			<span class="icon">
				<?php echo Html::icon('arrow_turn_090'); ?>
				<span><?php echo Yii::t('core', 'withSelected'); ?></span>
			</span>
			<a href="javascript:void(0)" onclick="schemaRoutines.dropRoutines()" class="icon button">
				<?php echo Html::icon('delete'); ?>
				<span><?php echo Yii::t('core', 'drop'); ?></span>
			</a>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaRoutines.addProcedure()">
				<?php echo Html::icon('add'); ?>
				<span><?php echo Yii::t('core', 'addProcedure'); ?></span>
			</a>
			<a href="javascript:void(0)" class="icon button" onclick="schemaRoutines.addFunction()">
				<?php echo Html::icon('add'); ?>
				<span><?php echo Yii::t('core', 'addFunction'); ?></span>
			</a>
		</div>
	</div>

</div>

<script type="text/javascript">
setTimeout(function() {
	schemaRoutines.setupDialogs();
}, 500);
</script>