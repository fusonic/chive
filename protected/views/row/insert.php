<div class="form">
<?php echo CHtml::form(); ?>

<?php echo $formBody; ?>

<div class="buttons">
	<a href="javascript:void(0);" onclick="$('form').submit();" class="icon button">
		<com:Icon name="add" size="16" text="core.insert" />
		<span><?php echo Yii::t('core', 'insert'); ?></span>
	</a>
</div>

<?php echo CHtml::endForm(); ?>