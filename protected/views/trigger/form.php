<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		<?php echo Yii::t('core', ($trigger->isNewRecord ? 'addTrigger' : 'editTrigger')); ?>
	</h1>
	<?php echo CHtml::errorSummary($trigger, false); ?>
	<?php $this->widget('AceEditor', array(
			'id' => 'query',
			'htmlOptions' => array('name' => 'query'),
			'value' => $query,
			'height' => 200,
		)); ?>
	<div class="buttonContainer">
		<?php echo Html::submitFormArea(false); ?>
	</div>
</form>