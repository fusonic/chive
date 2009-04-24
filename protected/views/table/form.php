<?php CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3); ?>

<?php if($isSubmitted && !$table->isNewRecord) { ?>
	<script type="text/javascript">
	var idPrefix = '<?php echo CHtml::$idPrefix; ?>';
	var row = $('#' + idPrefix).parents("tr").prev();
	row.attr('id', 'tables_<?php echo $table->TABLE_NAME; ?>');
	row.children('td:eq(1)').children('a').html('<?php echo $table->TABLE_NAME; ?>').attr('href', '#tables/<?php echo $table->TABLE_NAME; ?>/structure');
	row.children('td:eq(2)').children('a').attr('href', '#tables/<?php echo $table->TABLE_NAME; ?>/browse');
	row.children('td:eq(3)').children('a').attr('href', '#tables/<?php echo $table->TABLE_NAME; ?>/structure');
	row.children('td:eq(4)').children('a').attr('href', '#tables/<?php echo $table->TABLE_NAME; ?>/search');
	row.children('td:eq(5)').children('a').attr('href', '#tables/<?php echo $table->TABLE_NAME; ?>/insert');
	row.children('td:eq(10)').html('<?php echo $table->ENGINE; ?>');
	row.children('td:eq(11)').children('dfn').html('<?php echo $table->TABLE_COLLATION; ?>').attr('title', '<?php echo Collation::getDefinition($table->TABLE_COLLATION); ?>');
	$('#' + idPrefix).parent().slideUp(500, function() {
		$('#' + idPrefix).parents("tr").remove();
	});
	Notification.add('success', '<?php echo Yii::t('message', 'successEditTable', array('{table}' => $table->TABLE_NAME)); ?>', null, <?php echo json_encode($sql); ?>);
	</script>
<?php } ?>

<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		<?php echo Yii::t('database', ($table->isNewRecord ? 'addTable' : 'editTable')); ?>
	</h1>
	<?php echo CHtml::errorSummary($table, false); ?>
	<table class="form" style="float: left; margin-right: 20px">
		<colgroup>
			<col class="col1"/>
			<col class="col2" />
			<col class="col3" />
		</colgroup>
		<tbody>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($table, 'TABLE_NAME'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeTextField($table, 'TABLE_NAME'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($table, 'ENGINE'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeDropDownList($table, 'ENGINE', $storageEngines); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($table, 'TABLE_COLLATION'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeDropDownList($table, 'TABLE_COLLATION', CHtml::listData($collations, 'COLLATION_NAME', 'COLLATION_NAME', 'collationGroup')); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($table, 'TABLE_COMMENT'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeTextField($table, 'TABLE_COMMENT'); ?>
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
					<?php echo CHtml::activeLabel($table, 'optionPackKeys'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeDropDownList($table, 'optionPackKeys', StorageEngine::getPackKeyOptions()); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($table, 'optionDelayKeyWrite'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeCheckBox($table, 'optionDelayKeyWrite'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($table, 'optionChecksum'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeCheckBox($table, 'optionChecksum'); ?>
				</td>
			</tr>
		</tbody>
	</table>
	<div style="clear: left; padding-top: 5px">
		<?php echo CHtml::submitButton(Yii::t('action', ($table->isNewRecord ? 'create' : 'save')), array('class'=>'icon save')); ?>
		<?php echo CHtml::button(Yii::t('action', 'cancel'), array('class'=>'icon delete', 'onclick'=>'$(this.form).slideUp(500, function() { $(this).parents("tr").remove(); })')); ?>
	</div>
</form>

<script type="text/javascript">
tableForm.create('<?php echo CHtml::$idPrefix; ?>');
</script>