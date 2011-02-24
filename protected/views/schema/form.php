<?php CHtml::generateRandomIdPrefix(); ?>
<?php if(!$schema->isNewRecord && $isSubmitted) { ?>
	<script type="text/javascript">
	var idPrefix = '<?php echo CHtml::$idPrefix; ?>';
	var row = $('#' + idPrefix).closest("tr").prev();
	row.find("td dfn.collation").html("<?php echo $schema->DEFAULT_COLLATION_NAME; ?>").attr("title", "<?php echo Collation::getDefinition($schema->DEFAULT_COLLATION_NAME); ?>");
	$('#' + idPrefix).parent().slideUp(500, function() {
		$('#' + idPrefix).parents("tr").remove();
	});
	Notification.add('success', '<?php echo Yii::t('core', 'successEditSchema', array('{schema}' => $schema->SCHEMA_NAME)); ?>', null, <?php echo CJSON::encode($sql); ?>);
	</script>
<?php } ?>

<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		<?php echo Yii::t('core', ($schema->isNewRecord ? 'addSchema' : 'editSchema')); ?>
	</h1>
	<?php echo CHtml::errorSummary($schema, false); ?>
	<table class="form" style="float: left; margin-right: 20px">
		<colgroup>
			<col class="col1"/>
			<col class="col2" />
			<col class="col3" />
		</colgroup>
		<tbody>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($schema,'SCHEMA_NAME'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeTextField($schema, 'SCHEMA_NAME', ($schema->isNewRecord ? array() : array('disabled' =>  true))); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($schema,'DEFAULT_COLLATION_NAME'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeDropDownList($schema, 'DEFAULT_COLLATION_NAME', CHtml::listData($collations, 'COLLATION_NAME', 'COLLATION_NAME', 'collationGroup')); ?>
				</td>
			</tr>
		</tbody>
	</table>
	<?php echo Html::submitFormArea(); ?>
</form>