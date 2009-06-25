<?php $id = StringUtil::getRandom(10); ?>

<?php echo CHtml::form(BASEURL . '/row/update', 'POST', array('id' => 'form_' . $id)); ?>

<div style="padding: 2px 10px;">

	<?php echo CHtml::hiddenField('schema', $this->schema); ?>
	<?php echo CHtml::hiddenField('table', $this->table); ?>
	<?php echo CHtml::hiddenField('column', $column->name); ?>
	<?php echo CHtml::hiddenField('attributes', json_encode($attributes)); ?>
	<?php echo CHtml::hiddenField('isNull', 0, array('id' => 'isNull')); ?>

	<?php $this->widget('InputField', array('row' => $row, 'column'=>$column, 'htmlOptions' => array(
		'id' => 'input_' . $id,
		'autocomplete' => 'off',
		'name' => 'value',
	))); ?>
	
	<script type="text/javascript">

		$('#form_<? echo $id; ?>').ajaxForm({
			success:	function(response) {

							editing = false;
							responseObj = JSON.parse(response);
							
							if(responseObj.data.error) {
								$('#input_<?php echo $id; ?>').parent().parent().html(<?php echo json_encode($oldValue); ?>);
								AjaxResponse.handle(response);
								return false;
							}
				
							$('#input_<?php echo $id; ?>').parent().parent().html(responseObj.data.visibleValue);
							
							if(responseObj.data.isIdentifier) 
							{
								keyData[rowIndex] = response.data.identifier;
							}

							AjaxResponse.handle(response);
							
						}
		});

		function reset() {
			$('#form_<?php echo $id; ?>').parent().html(<?php echo json_encode($oldValue); ?>); 
			editing = false;
		}
	
		$('#input_<?php echo $id; ?>').select().focus();
		editing = true;

	</script>
	
	<div class="buttonContainer" style="width: 300px;">
	
		<a href="javascript:void(0);" onclick="$('#form_<?php echo $id; ?>').submit();" class="icon button primary">
			<com:Icon name="save" size="16" text="core.save" />
			<span><?php echo Yii::t('core', 'save'); ?></span>
		</a>
		<a href="javascript:void(0);" onclick="reset();" class="icon button">
			<com:Icon name="delete" size="16" text="core.cancel" />
			<span><?php echo Yii::t('core', 'cancel'); ?></span>
		</a>
		<?php if($column->allowNull) { ?>
			<?php echo Yii::t('core', 'or'); ?>
			<a href="javascript:void(0);" onclick="$('#isNull').val(1); $('#form_<?php echo $id; ?>').submit();" class="icon button">
				<com:Icon name="null" size="16" text="core.null" />
				<span><?php echo Yii::t('core', 'setNull'); ?></span>
			</a>
		<?php } ?>
	</div>
	
</div>

<?php echo CHtml::endForm(); ?>