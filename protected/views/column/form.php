<?php CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3); ?>
<script type="text/javascript">
var idPrefix = '<?php echo CHtml::$idPrefix; ?>';
</script>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/views/column/form.js', CClientScript::POS_END); ?>

<?php if($isSubmitted && !$column->isNewRecord): ?>
	<script type="text/javascript">
	var row = $('#' + idPrefix).parents("tr").prev();
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
<?php endif; ?>

<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		<?php echo Yii::t('database', ($column->isNewRecord ? 'addColumn' : 'editColumn')); ?>
	</h1>
	<?php echo CHtml::errorSummary($column, false); ?>
	<div style="float: left; width: 200px">
		<fieldset>
			<legend><?php echo CHtml::activeLabel($column,'COLUMN_NAME'); ?></legend>
			<?php echo CHtml::activeTextField($column, 'COLUMN_NAME', ($column->isNewRecord ? array() : array('disabled' =>  true))); ?>
		</fieldset>
		<fieldset id="<?php echo CHtml::$idPrefix; ?>dataTypeSet">
			<legend><?php echo CHtml::activeLabel($column,'dataType'); ?></legend>
			<?php echo CHtml::activeDropDownList($column, 'dataType', Column::getDataTypes()); ?>
			<fieldset class="datatypeSetting char varchar binary varbinary blob text bit tinyint smallint mediumint int bigint float double decimal year">
				<legend><?php echo CHtml::activeLabel($column,'size'); ?></legend>
				<?php echo CHtml::activeTextField($column, 'size'); ?>
			</fieldset>
			<fieldset class="datatypeSetting float double decimal">
				<legend><?php echo CHtml::activeLabel($column,'scale'); ?></legend>
				<?php echo CHtml::activeTextField($column, 'scale'); ?>
			</fieldset>
			<fieldset class="datatypeSetting enum set">
				<legend><?php echo CHtml::activeLabel($column,'values'); ?></legend>
				<?php echo CHtml::activeTextArea($column, 'values'); ?>
			</fieldset>
			<fieldset class="datatypeSetting char varchar tinytext smalltext text mediumtext longtext enum set">
				<legend><?php echo CHtml::activeLabel($column,'COLLATION_NAME'); ?></legend>
				<?php echo CHtml::activeDropDownList($column, 'COLLATION_NAME', CHtml::listData($collations, 'COLLATION_NAME', 'COLLATION_NAME', 'collationGroup')); ?>
			</fieldset>
			<fieldset class="datatypeSetting all">
				<legend><?php echo CHtml::activeLabel($column,'COLUMN_DEFAULT'); ?></legend>
				<?php echo CHtml::activeTextField($column, 'COLUMN_DEFAULT'); ?>
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