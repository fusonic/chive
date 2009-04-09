<?php CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3); ?>
<script type="text/javascript">
var idPrefix = '<?php echo CHtml::$idPrefix; ?>';
</script>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/views/column/form.js', CClientScript::POS_END); ?>

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