<?php $this->widget('InputField', array('row' => $row, 'column'=>$column, 'htmlOptions' => array(
	'id' => $id = 'input_' . StringUtil::getRandom(10),
	'autocomplete' => 'off'
))); ?>

<script type="text/javascript">

	$('#<?php echo $id; ?>').select().focus();

	function save() {

		$.post('<?php echo BASEURL; ?>/row/update',
		{
			schema: 	'<?php echo $this->schema; ?>',
			table: 		'<?php echo $this->table; ?>',
			column: 	'<?php echo $column->name; ?>',
			value:		$('#<?php echo $id; ?>').val(),
			attributes:	JSON.stringify(<?php echo ArrayUtil::toJavaScriptObject($attributes); ?>)
		}, 
		function(response) {
			
			editing = false;
			responseObj = JSON.parse(response);
			
			if(responseObj.data.error) {
				$('#<?php echo $id; ?>').parent().html(<?php echo json_encode($oldValue); ?>);
				AjaxResponse.handle(response);
				return false;
			}

			$('#<?php echo $id; ?>').parent().html(responseObj.data.value);
			
			if(responseObj.data.isPrimary) 
			{
				keyData[rowIndex].<?php echo $column->name; ?> = responseObj.data.value;
			}

			AjaxResponse.handle(response);
			
		});
	}

	function reset() {
		$('#<?php echo $id; ?>').parent().html(<?php echo json_encode($oldValue); ?>); 
		editing = false;
	}

	function setNull() 
	{
		$.post('<?php echo BASEURL; ?>/row/update',
		{
			schema: 	'<?php echo $this->schema; ?>',
			table: 		'<?php echo $this->table; ?>',
			column: 	'<?php echo $column->name; ?>',
			isNull:		'true',
			attributes:	JSON.stringify(<?php echo ArrayUtil::toJavaScriptObject($attributes); ?>)
		}, function(response) {
			
			editing = false;
			
			responseObj = JSON.parse(response);
			$('#<?php echo $id; ?>').parent().html(responseObj.data.value);

			if(responseObj.data.isPrimary) 
			{
				keyData[rowIndex].<?php echo $column->name; ?> = responseObj.data.value;
			}

			AjaxResponse.handle(response);
			
		});
	}
	
</script>

<div class="buttonContainer" style="width: 100%; padding-top: 5px;">
	<input type="button" name="save" class="button icon save" value="<?php echo Yii::t('core', 'save'); ?>" onclick="save();" />
	<input type="button" name="cancel" class="button icon cancel" value="<?php echo Yii::t('core', 'cancel'); ?>" onclick="reset();" />
	<?php if($column->allowNull) { ?>
	<?php echo Yii::t('core', 'or'); ?>
	<input type="button" name="null" class="button icon null" value="Set NULL" onclick="setNull();" />
<?php } ?>
</div>