<script type="text/javascript">
var isPrimary<?php echo CHtml::$idPrefix; ?> = <?php echo CJSON::encode($column->getIsPartOfPrimaryKey()); ?>;
</script>

<?php if(!$column->isNewRecord && $isSubmitted) { ?>
	<script type="text/javascript">
	var idPrefix = '<?php echo CHtml::$idPrefix; ?>';
	var row = $('#' + idPrefix).closest("tr").prev();
	row.attr('id', 'columns_<?php echo $column->COLUMN_NAME; ?>');
	row.children('td:eq(1)').html('<?php echo $column->COLUMN_NAME; ?>');
	row.children('td:eq(2)').html(<?php echo CJSON::encode($column->COLUMN_TYPE); ?>);
	row.children('td:eq(3)').html('<?php echo ($column->COLLATION_NAME ? '<dfn class="collation" title="' . Collation::getDefinition($column->COLLATION_NAME) . '">' . $column->COLLATION_NAME . '</dfn>' : ''); ?>');
	row.children('td:eq(4)').html('<?php echo Yii::t('core', ($column->isNullable ? 'yes' : 'no')); ?>');
	row.children('td:eq(5)').html(<?php echo (!is_null($column->COLUMN_DEFAULT) ? CJSON::encode($column->COLUMN_DEFAULT) : ($column->isNullable ? CJSON::encode('<span class="null">NULL</span>') : '\'\'')); ?>);
	row.children('td:eq(6)').html('<?php echo $column->EXTRA; ?>');
	$('#' + idPrefix).parent().slideUp(500, function() {
		$('#' + idPrefix).parents("tr").remove();
	});
	Notification.add('success', '<?php echo Yii::t('core', 'successEditColumn', array('{col}' => $column->COLUMN_NAME)); ?>', null, <?php echo CJSON::encode($sql); ?>);
	</script>
<?php } ?>

<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		<?php echo Yii::t('core', ($column->isNewRecord ? 'addColumn' : 'editColumn')); ?>
	</h1>
	<?php echo $formBody; ?>
	<?php echo Html::submitFormArea(); ?>
</form>

<script type="text/javascript">
columnForm.create('<?php echo CHtml::$idPrefix; ?>');
</script>