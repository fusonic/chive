<?php echo CHtml::form('', 'post', array('id' => CHtml::ID_PREFIX)); ?>
	<?php echo CHtml::hiddenField('type', $type); ?>
	<h1>
		<?php echo Yii::t('core', ($routine->isNewRecord ? 'add' : 'edit') . ucfirst($type)); ?>
	</h1>
	<?php echo CHtml::errorSummary($routine, false); ?>
	<com:SqlEditor name="query" value="{$query}" width="95%" height="200px" />
	<div class="buttonContainer">
		<?php echo Html::submitFormArea(false); ?>
		<a id="aToggleEditor<?php echo CHtml::ID_PREFIX;?>" class="icon button" href="javascript:void(0);" onclick="toggleEditor('<?php echo CHtml::ID_PREFIX;?>query','aToggleEditor<?php echo CHtml::ID_PREFIX;?>');">
			<?php if( Yii::app()->user->settings->get('sqlEditorOn') == '1') {?>
				<?php echo Html::icon('square_green'); ?>
			<?php } else { ?>
				<?php echo Html::icon('square_red'); ?>
			<?php } ?>
			<span><?php echo Yii::t('core', 'toggleEditor'); ?></span>
		</a>
	</div>
</form>