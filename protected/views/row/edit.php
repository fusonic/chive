<script type="text/javascript">
	$(document).ready(function() {
		$('#attributes_<?php echo CHtml::$idPrefix; ?>').val('<?php echo json_encode($attributes); ?>');
	});
</script>
<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<input type="hidden" name="attributes" value="" id="attributes_<?php echo CHtml::$idPrefix; ?>" />
	<input type="hidden" name="schema" value="<?php echo $this->schema; ?>" />
	<input type="hidden" name="table" value="<?php echo $this->table; ?>" />
	<h1>
		<?php echo Yii::t('database', ($row->isNewRecord ? 'insertRow' : 'editRow')); ?>
	</h1>
	<?php echo $formBody; ?>
	<div class="buttonContainer">
		<a href="javascript:void(0)" onclick="$('#<?php echo CHtml::$idPrefix; ?>').submit();" class="icon button">
			<com:Icon name="save" size="16" />
			<span><?php echo Yii::t('action', 'save'); ?></span>
		</a>
		<a href="javascript:void(0)" onclick="$('#<?php echo CHtml::$idPrefix; ?>').slideUp(500, function() { $(this).parents('tr').remove(); })" class="icon button">
			<com:Icon name="delete" size="16" />
			<span><?php echo Yii::t('action', 'cancel'); ?></span>
		</a>
	</div>
<?php echo CHtml::endForm(); ?>
