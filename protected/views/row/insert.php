<div class="form">
<?php echo CHtml::form(); ?>

<?php echo $formBody; ?>

<div class="buttons">
	<input type="hidden" name="insertAndReturn" value="0" id="insertAndReturn" />
	<a href="javascript:void(0);" onclick="$('form').submit();" class="icon button primary">
		<com:Icon name="add" size="16" text="core.insert" />
		<span><?php echo Yii::t('core', 'insert'); ?></span>
	</a>
	<a href="javascript:void(0);" onclick="$('#insertAndReturn').attr('value', 1); $('form').submit();" class="icon button">
		<com:Icon name="arrow_return" size="16" text="core.insertAndReturnToThisPage" />
		<span><?php echo Yii::t('core', 'insertAndReturnToThisPage'); ?></span>
	</a>
</div>

<script type="text/javascript">
	$('form').ajaxForm({
		success: function(responseText) {
			JSON.parse(responseText);
			AjaxResponse.handle(responseText);
		}
	});
</script>


<?php echo CHtml::endForm(); ?>