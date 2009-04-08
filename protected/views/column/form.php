<?php CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3); ?>
<span id="<%= $helperId %>" />

<?php if($isSubmitted && !$column->isNewRecord): ?>
	<script type="text/javascript">
	/*
	$("#<%= $helperId %>").parents("tr").prev().effect("highlight", {}, 2000);
	$("#<%= $helperId %>").parents("tr").prev().find("td dfn.collation").html("<%= $database->DEFAULT_COLLATION_NAME %>");
	$("#<%= $helperId %>").parents("tr").prev().find("td dfn.collation").attr("title", "<%= $database->collation->definition %>");
	$("#<%= $helperId %>").parent().slideUp(500, function() {
		$("#<%= $helperId %>").parents("tr").remove();
	});
	*/
	</script>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function() {
	var idPrefix = '<?php echo CHtml::$idPrefix; ?>';
	var types = {
		numeric: ['bit', 'tinyint', 'bool', 'smallint', 'mediumint', 'int', 'bigint', 'float', 'double', 'decimal'],
		strings: ['char', 'varchar', 'tinytext', 'text', 'mediumtext', 'longtext', 'tinyblob', 'blob', 'mediumblob', 'longblob', 'binary', 'varbinary']
	};
	$('#' + idPrefix + 'Column_DATA_TYPE').change(function() {
		var type = $(this).val();
		var isNumeric = $.inArray(type, types.numeric) > -1;
		var isString = $.inArray(type, types.strings) > -1;

		// Hide all datatype fieldsets
		$('#' + idPrefix + 'dataTypeSet fieldset.datatypeSetting').hide();

		// Show datatype fieldsets
		if(isString)
		{
			$('#' + idPrefix + 'dataTypeSet fieldset.stringSetting').show();
		}
		if(isNumeric)
		{
			$('#' + idPrefix + 'dataTypeSet fieldset.numericSetting').show();
		}
		$('#' + idPrefix + 'dataTypeSet fieldset.' + type + 'Setting').show();

	});
	$('#<?php echo CHtml::$idPrefix; ?>Column_DATA_TYPE').change();
});
</script>

<?php echo CHtml::form('', 'post'); ?>
	<h1>
		<?php echo Yii::t('database', ($column->isNewRecord ? 'addColumn' : 'editColumn')); ?>
	</h1>
	<?php echo CHtml::errorSummary($column, false); ?>
	<div style="float: left; width: 200px">
		<fieldset>
			<legend><?php echo CHtml::activeLabel($column,'COLUMN_NAME'); ?></legend>
			<?php echo CHtml::activeTextField($column, 'COLUMN_NAME'); ?>
		</fieldset>
		<fieldset id="<?php echo CHtml::$idPrefix; ?>dataTypeSet">
			<legend><?php echo CHtml::activeLabel($column,'DATA_TYPE'); ?></legend>
			<?php echo CHtml::activeDropDownList($column, 'DATA_TYPE', Column::getDataTypes()); ?>
			<fieldset class="datatypeSetting stringSetting">
				<legend><?php echo CHtml::activeLabel($column,'COLLATION_NAME'); ?></legend>
				<?php echo CHtml::activeDropDownList($column, 'COLLATION_NAME', CHtml::listData($collations, 'COLLATION_NAME', 'COLLATION_NAME', 'collationGroup'), array('onchange'=>'dataTypeChanged()')); ?>
			</fieldset>
			<fieldset class="datatypeSetting numericSetting charSetting varcharSetting">
				<legend><?php echo CHtml::activeLabel($column,'precision'); ?></legend>
				<?php echo CHtml::activeTextField($column, 'precision'); ?>
			</fieldset>
		</fieldset>
	</div>
	<div style="margin-left: 200px">
		<fieldset>
			<legend><?php echo Yii::t('core', 'options'); ?></legend>
			<?php echo CHtml::activeCheckBox($column, 'isNullable'); ?>
			<?php echo CHtml::activeLabel($column, 'isNullable'); ?>
			<?php echo CHtml::activeCheckBox($column, 'autoIncrement'); ?>
			<?php echo CHtml::activeLabel($column, 'autoIncrement'); ?>
		</fieldset>
		<fieldset>
			<legend><?php echo CHtml::activeLabel($column,'COLUMN_COMMENT'); ?></legend>
			<?php echo CHtml::activeTextField($column, 'COLUMN_COMMENT'); ?>
		</fieldset>
	</div>
	<div style="clear: left; padding-top: 5px">
		<?php echo CHtml::submitButton(Yii::t('action', ($column->isNewRecord ? 'create' : 'save')), array('class'=>'icon save')); ?>
		<?php echo CHtml::button(Yii::t('action', 'cancel'), array('class'=>'icon delete', 'onclick'=>'$(this.form).slideUp(500, function() { $(this).parents("tr").remove(); })')); ?>
	</div>
</form>