<?php if($isSubmitted) { ?>
	<script type="text/javascript">
	var idPrefix = '<?php echo CHtml::$idPrefix; ?>';
	var row = $('#' + idPrefix).closest("tr").prev();
	<?php if($foreignKey->isNewRecord) { ?>
		row.find('img.icon_relation').addClass('disabled');
	<?php } else { ?>
		row.find('img.icon_relation').removeClass('disabled');
	<?php } ?>
	$('#' + idPrefix).parent().slideUp(500, function() {
		$('#' + idPrefix).parents("tr").remove();
	});
	Notification.add('success', '<?php echo Yii::t('message', 'successEditRelation', array('{col}' => $foreignKey->COLUMN_NAME)); ?>', null, <?php echo json_encode($sql); ?>);
	</script>
<?php } ?>

<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		<?php echo Yii::t('database', 'editRelation'); ?>
	</h1>
	<?php echo CHtml::errorSummary($foreignKey, false); ?>
	<table class="form">
		<colgroup>
			<col class="col1"/>
			<col class="col2" />
			<col class="col3" />
		</colgroup>
		<tbody>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($foreignKey, 'references'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeDropDownList($foreignKey, 'references', $columns); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($foreignKey, 'onDelete'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeDropDownList($foreignKey, 'onDelete', $onActions); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($foreignKey, 'onUpdate'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeDropDownList($foreignKey, 'onUpdate', $onActions); ?>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="buttonContainer">
		<a href="javascript:void(0)" onclick="$('#<?php echo CHtml::$idPrefix; ?>').submit()" class="icon button">
			<com:Icon name="save" size="16" />
			<span><?php echo Yii::t('action', 'save'); ?></span>
		</a>
		<a href="javascript:void(0)" onclick="$('#<?php echo CHtml::$idPrefix; ?>').slideUp(500, function() { $(this).parents('tr').remove(); })" class="icon button">
			<com:Icon name="delete" size="16" />
			<span><?php echo Yii::t('action', 'cancel'); ?></span>
		</a>
	</div>
</form>

<script type="text/javascript">
foreignKeyForm.create('<?php echo CHtml::$idPrefix; ?>');
</script>