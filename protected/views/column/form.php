<?php CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3); ?>
<script type="text/javascript">
var isPrimary<?php echo CHtml::$idPrefix; ?> = <?php echo json_encode($column->getIsPartOfPrimaryKey()); ?>;
</script>

<?php if($isSubmitted && !$column->isNewRecord) { ?>
	<script type="text/javascript">
	var idPrefix = '<?php echo CHtml::$idPrefix; ?>';
	var row = $('#' + idPrefix).closest("tr").prev();
	row.attr('id', 'columns_<?php echo $column->COLUMN_NAME; ?>');
	row.children('td:eq(1)').html('<?php echo $column->COLUMN_NAME; ?>');
	row.children('td:eq(2)').html(<?php echo json_encode($column->COLUMN_TYPE); ?>);
	row.children('td:eq(3)').html('<?php echo ($column->COLLATION_NAME ? '<dfn class="collation" title="' . Collation::getDefinition($column->COLLATION_NAME) . '">' . $column->COLLATION_NAME . '</dfn>' : ''); ?>');
	row.children('td:eq(4)').html('<?php echo Yii::t('core', ($column->isNullable ? 'yes' : 'no')); ?>');
	row.children('td:eq(5)').html('<?php echo (!is_null($column->COLUMN_DEFAULT) ? $column->COLUMN_DEFAULT : ($column->isNullable ? '<span class="null">NULL</span>' : '')); ?>');
	row.children('td:eq(6)').html('<?php echo $column->EXTRA; ?>');
	$('#' + idPrefix).parent().slideUp(500, function() {
		$('#' + idPrefix).parents("tr").remove();
	});
	Notification.add('success', '<?php echo Yii::t('message', 'successEditColumn', array('{col}' => $column->COLUMN_NAME)); ?>', null, <?php echo json_encode($sql); ?>);
	</script>
<?php } ?>

<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		<?php echo Yii::t('database', ($column->isNewRecord ? 'addColumn' : 'editColumn')); ?>
	</h1>
	<?php echo CHtml::errorSummary($column, false); ?>
	<table class="form" style="float: left; margin-right: 20px">
		<colgroup>
			<col class="col1"/>
			<col class="col2" />
			<col class="col3" />
		</colgroup>
		<tbody>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($column,'COLUMN_NAME'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeTextField($column, 'COLUMN_NAME'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($column, 'dataType'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeDropDownList($column, 'dataType', Column::getDataTypes()); ?>
				</td>
			</tr>
			<tr id="<?php echo CHtml::$idPrefix; ?>settingSize">
				<td>
					<?php echo CHtml::activeLabel($column, 'size'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeTextField($column, 'size'); ?>
				</td>
			</tr>
			<tr id="<?php echo CHtml::$idPrefix; ?>settingScale">
				<td>
					<?php echo CHtml::activeLabel($column, 'scale'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeTextField($column, 'scale'); ?>
				</td>
			</tr>
			<tr id="<?php echo CHtml::$idPrefix; ?>settingValues">
				<td>
					<?php echo CHtml::activeLabel($column, 'values'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeTextArea($column, 'values'); ?>
					<div class="small">
						<?php echo Yii::t('core', 'enterOneValuePerLine'); ?>
					</div>
				</td>
			</tr>
			<tr id="<?php echo CHtml::$idPrefix; ?>settingCollation">
				<td>
					<?php echo CHtml::activeLabel($column, 'COLLATION_NAME'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeDropDownList($column, 'COLLATION_NAME', CHtml::listData($collations, 'COLLATION_NAME', 'COLLATION_NAME', 'collationGroup')); ?>
				</td>
			</tr>
			<tr id="<?php echo CHtml::$idPrefix; ?>settingDefault">
				<td>
					<?php echo CHtml::activeLabel($column, 'COLUMN_DEFAULT'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeTextField($column, 'COLUMN_DEFAULT'); ?>
					<div class="small" id="<?php echo CHtml::$idPrefix; ?>settingDefaultNullHint">
						<?php echo Yii::t('core', 'leaveEmptyForNull'); ?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($column,'COLUMN_COMMENT'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeTextField($column, 'COLUMN_COMMENT'); ?>
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
					<?php echo Yii::t('core', 'options'); ?>
				</td>
				<td>
					<?php echo CHtml::activeCheckBox($column, 'isNullable'); ?>
					<?php echo CHtml::activeLabel($column, 'isNullable'); ?>
				</td>
				<td>
					<?php echo CHtml::activeCheckBox($column, 'autoIncrement'); ?>
					<?php echo CHtml::activeLabel($column, 'autoIncrement'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo Yii::t('database', 'attribute'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeRadioButton($column, 'attribute', array('value' => '', 'id' => CHtml::$idPrefix . 'Column_attribute_')); ?>
					<?php echo CHtml::label(Yii::t('database', 'noAttribute'), 'Column_attribute_', array('style' => 'font-style: italic')); ?>
				</td>
			</tr>
			<tr>
				<td />
				<td>
					<?php echo CHtml::activeRadioButton($column, 'attribute', array('value' => 'unsigned', 'id' => CHtml::$idPrefix . 'Column_attribute_unsigned')); ?>
					<?php echo CHtml::label(Yii::t('database', 'unsigned'), 'Column_attribute_unsigned'); ?>
				</td>
				<td>
					<?php echo CHtml::activeRadioButton($column, 'attribute', array('value' => 'unsigned zerofill', 'id' => CHtml::$idPrefix . 'Column_attribute_unsignedzerofill')); ?>
					<?php echo CHtml::label(Yii::t('database', 'unsignedZerofill'), 'Column_attribute_unsignedzerofill'); ?>
				</td>
			</tr>
			<?php if($column->isNewRecord) { ?>
				<tr id="<?php echo CHtml::$idPrefix; ?>settingSize">
					<td>
						<?php echo Yii::t('database', 'createIndex'); ?>
					</td>
					<td>
						<?php echo CHtml::checkBox('createIndexPrimary'); ?>
						<?php echo CHtml::label(Yii::t('database', 'primaryKey'), 'createIndexPrimary', array('disabled' => $table->getHasPrimaryKey())); ?>
					</td>
					<td>
						<?php echo CHtml::checkBox('createIndex'); ?>
						<?php echo CHtml::label(Yii::t('database', 'index'), 'createIndex'); ?>
					</td>
				</tr>
				<tr id="<?php echo CHtml::$idPrefix; ?>settingScale">
					<td />
					<td>
						<?php echo CHtml::checkBox('createIndexUnique'); ?>
						<?php echo CHtml::label(Yii::t('database', 'uniqueKey'), 'createIndexUnique'); ?>
					</td>
					<td>
						<?php echo CHtml::checkBox('createIndexFulltext'); ?>
						<?php echo CHtml::label(Yii::t('database', 'fulltextIndex'), 'createIndexFulltext'); ?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<div style="clear: left; padding-top: 5px">
		<?php echo CHtml::submitButton(Yii::t('action', ($column->isNewRecord ? 'create' : 'save')), array('class'=>'icon save')); ?>
		<?php echo CHtml::button(Yii::t('action', 'cancel'), array('class'=>'icon delete', 'onclick'=>'$(this.form).slideUp(500, function() { $(this).parents("tr").remove(); })')); ?>
	</div>
</form>

<script type="text/javascript">
columnForm.create('<?php echo CHtml::$idPrefix; ?>');
</script>