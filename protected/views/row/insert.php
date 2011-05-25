<div class="form">
<?php echo CHtml::form(Yii::app()->createUrl('schema/' . $this->schema . '/tables/' . $this->table . '/insert')); ?>

<?php echo $formBody; ?>

<div class="buttons">
	<input type="hidden" name="insertAndReturn" value="0" id="insertAndReturn" />
	<a href="javascript:void(0);" onclick="$('form').submit();" class="icon button primary">
		<?php echo Html::icon('add', 16, false, 'core.insert'); ?>
		<span><?php echo Yii::t('core', 'insert'); ?></span>
	</a>
	<a href="javascript:void(0);" onclick="$('#insertAndReturn').attr('value', 1); $('form').submit();" class="icon button">
		<?php echo Html::icon('arrow_turn_090', 16, false, 'core.insertAndReturnToThisPage'); ?>
		<span><?php echo Yii::t('core', 'insertAndReturnToThisPage'); ?></span>
	</a>
</div>

<script type="text/javascript">
	$('form').ajaxForm({
		success: function(response) {
			AjaxResponse.handle(response);
		}
	});
</script>

<input type="submit" name="submitButton" style="display: none;" />

<?php echo CHtml::endForm(); ?>