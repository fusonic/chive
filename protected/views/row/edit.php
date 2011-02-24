<script type="text/javascript">
	$(document).ready(function() {
		$('#attributes_<?php echo CHtml::$idPrefix; ?>').val('<?php echo CJSON::encode($attributes); ?>');
	});
</script>

<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<input type="hidden" name="attributes" value="" id="attributes_<?php echo CHtml::$idPrefix; ?>" />
	<input type="hidden" name="schema" value="<?php echo $this->schema; ?>" />
	<input type="hidden" name="table" value="<?php echo $this->table; ?>" />
	<h1>
		<?php echo Yii::t('core', ($row->isNewRecord ? 'insertRow' : 'editRow')); ?>
	</h1>
	<?php echo $formBody; ?>
	<?php echo Html::submitFormArea(); ?>
<?php echo CHtml::endForm(); ?>
