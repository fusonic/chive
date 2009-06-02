<?php $this->widget('InputField', array('row' => $row, 'column'=>$column, 'htmlOptions' => array(
	'id' => $id = 'input_' . StringUtil::getRandom(10),
	'autocomplete' => 'off'
))); ?>

<script type="text/javascript">
	$('#<?php echo $id; ?>').focus().select();
</script>

<div class="buttonContainer" style="width: 100%; padding-top: 5px;">
	<input type="button" name="save" class="button icon save" value="<?php echo Yii::t('core', 'save'); ?>" onclick="console.log(); $.post('<?php echo BASEURL; ?>/row/update',
		{
			schema: 	'<?php echo $this->schema; ?>',
			table: 		'<?php echo $this->table; ?>',
			column: 	'<?php echo $column->name; ?>',
			value:		$('#<?php echo $id; ?>').val(),
			attributes:	JSON.stringify(<?php echo ArrayUtil::toJavaScriptObject($attributes); ?>)
		}, function(response) {
			
			editing = false;
			responseObj = JSON.parse(response);
			
			if(responseObj.data.error) {
				$('#<?php echo $id; ?>').parent().html('<?php echo $oldValue; ?>');
				AjaxResponse.handle(response);
				return false;
			}
			
			$('#<?php echo $id; ?>').parent().html(responseObj.data.value);
			
			if(responseObj.data.isPrimary) 
			{
				keyData[rowIndex].<?php echo $column->name; ?> = responseObj.data.value;
			}

			AjaxResponse.handle(response);
			
		});" />
	<input type="button" name="cancel" class="button icon cancel" value="<?php echo Yii::t('core', 'cancel'); ?>" onclick="$(this).parent().parent().html('<?php echo $oldValue; ?>'); editing = false;" />
	<?php if($column->allowNull) { ?>
	<?php echo Yii::t('core', 'or'); ?>
	<input type="button" name="null" class="button icon null" value="Set NULL" onclick="$.post('<?php echo BASEURL; ?>/row/update',
		{
			schema: 	'<?php echo $this->schema; ?>',
			table: 		'<?php echo $this->table; ?>',
			column: 	'<?php echo $column->name; ?>',
			null:		'true',
			attributes:	JSON.stringify(<?php echo ArrayUtil::toJavaScriptObject($attributes); ?>)
		}, function(response) {
			
			editing = false;
			
			responseObj = JSON.parse(response);
			$('#<?php echo $id; ?>').parent().html(responseObj.data.value);
			
			/*
			if(responseObj.data.key) 
			{
				console.log('OK');
				keyData[rowIndex] = responseObj.data.key;
			}
			*/
			AjaxResponse.handle(response);
			
		});" />
<?php } ?>
</div>