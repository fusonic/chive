<?php $id = StringUtil::getRandom(10); ?>

<?php echo CHtml::form(Yii::app()->createUrl('row/update'), 'POST', array('id' => 'form_' . $id)); ?>

<div style="padding: 2px 10px;">

	<?php echo CHtml::hiddenField('schema', $this->schema); ?>
	<?php echo CHtml::hiddenField('table', $this->table); ?>
	<?php echo CHtml::hiddenField('column', $column->name); ?>
	<?php echo CHtml::hiddenField('attributes', CJSON::encode($attributes)); ?>
	<?php echo CHtml::hiddenField('isNull', 0, array('id' => 'isNull')); ?>

	<?php $this->widget('InputField', array('row' => $row, 'column'=>$column, 'htmlOptions' => array(
		'id' => 'input_' . $id,
		'autocomplete' => 'off',
		'name' => 'value',
	))); ?>

	<script type="text/javascript">

		$('#form_<?php echo $id; ?>').ajaxForm({
			success:	function(response) {

							editing = false;

							if(response.data.error) {
								reset();
								AjaxResponse.handle(response);
								return false;
							}

							
							$('#input_<?php echo $id; ?>').parent().parent().parent().html(response.data.visibleValue);
							keyData[rowIndex] = response.data.identifier;
							AjaxResponse.handle(response);

						}
		});

		function reset() {
			$('#form_<?php echo $id; ?>').parent().html(<?php echo CJSON::encode($oldValue); ?>);
			editing = false;
		}

		$('#input_<?php echo $id; ?>').select().focus();
		editing = '<?php echo $id; ?>';

	</script>

	<div class="buttonContainer" style="white-space: nowrap">
		<a href="javascript:void(0);" onclick="$('#form_<?php echo $id; ?>').submit();" class="icon button primary">
			<?php echo Html::icon('save', 16, false, 'core.save'); ?>
			<span><?php echo Yii::t('core', 'save'); ?></span>
		</a>
		<a href="javascript:void(0);" onclick="reset();" class="icon button">
			<?php echo Html::icon('delete', 16, false, 'core.cancel'); ?>
			<span><?php echo Yii::t('core', 'cancel'); ?></span>
		</a>
		<?php if($column->allowNull) { ?>
			<?php echo Yii::t('core', 'or'); ?>
			<a href="javascript:void(0);" onclick="$('#isNull').val(1); $('#form_<?php echo $id; ?>').submit();" class="icon button">
				<?php echo Html::icon('null', 16, false, 'core.null'); ?>
				<span><?php echo Yii::t('core', 'setNull'); ?></span>
			</a>
		<?php } ?>
	</div>

</div>

<?php echo CHtml::endForm(); ?>