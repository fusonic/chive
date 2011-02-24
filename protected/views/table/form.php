<?php if(!$table->isNewRecord && $isSubmitted) { ?>
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
	Notification.add('success', '<?php echo Yii::t('core', 'successEditTable', array('{table}' => $table->TABLE_NAME)); ?>', null, <?php echo CJSON::encode($sql); ?>);

	// Reload sideBar
	sideBar.loadTables(schema);
	</script>
<?php } ?>

<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		<?php echo Yii::t('core', ($table->isNewRecord ? 'addTable' : 'editTable')); ?>
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
					<?php echo CHtml::activeDropDownList($table, 'ENGINE', CHtml::listData($storageEngines, 'Engine', 'Engine')); ?>
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
					<?php echo CHtml::activeLabel($table, 'comment'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeTextField($table, 'comment'); ?>
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
	<?php if($table->isNewRecord) { ?>
		<h1 style="clear: left">
			<?php echo Yii::t('core', 'addFirstColumn'); ?>
		</h1>
		<?php echo $columnForm; ?>
	<?php } ?>
	<?php echo Html::submitFormArea(); ?>
</form>

<script type="text/javascript">
tableForm.create('<?php echo CHtml::$idPrefix; ?>');
<?php if($table->isNewRecord) { ?>
	columnForm.create('<?php echo CHtml::$idPrefix; ?>');
<?php } ?>
</script>