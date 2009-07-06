<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<?php echo CHtml::hiddenField('type', $type); ?>
	<h1>
		<?php echo Yii::t('database', ($routine->isNewRecord ? 'add' : 'edit') . ucfirst($type)); ?>
	</h1>
	<?php echo CHtml::errorSummary($routine, false); ?>
	<com:SqlEditor name="query" value="{$query}" width="95%" height="200px" />
	<div class="buttonContainer">
		<a href="javascript:void(0)" onclick="$('#<?php echo CHtml::$idPrefix; ?>').submit()" class="icon button">
			<com:Icon name="execute" size="16" />
			<span><?php echo Yii::t('action', 'execute'); ?></span>
		</a>
		<a href="javascript:void(0)" onclick="$('#<?php echo CHtml::$idPrefix; ?>').slideUp(500, function() { $(this).parents('tr').remove(); })" class="icon button">
			<com:Icon name="delete" size="16" />
			<span><?php echo Yii::t('action', 'cancel'); ?></span>
		</a>
		<a id="aToggleEditor<?php echo CHtml::$idPrefix;?>" class="icon button" href="javascript:void(0);" onclick="toggleEditor('<?php echo CHtml::$idPrefix;?>query','aToggleEditor<?php echo CHtml::$idPrefix;?>');">
					<?php if( Yii::app()->user->settings->get('sqlEditorOn') == '1') {?>
						<com:Icon size="16" name="square_green" />
					<?php } else { ?>
						<com:Icon size="16" name="square_red" />
					<?php } ?>
					<span><?php echo Yii::t('core', 'toggleEditor'); ?></span>
				</a>
	</div>
</form>