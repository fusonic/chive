<?php if($model->view == 'form') { ?>

<?php echo CHtml::form($model->formTarget, 'post', array('id' => 'Import')); ?>
	<fieldset>
		<legend><?php echo Yii::t('core', 'importFile'); ?></legend>
		<?php echo CHtml::fileField('file', '', array()); ?>&nbsp;
		(<?php echo Yii::t('core', 'maximum'); ?>: <?php echo ConfigUtil::getMaxUploadSize(true); ?>)
		<br />
		<br />
		<?php echo Yii::t('core', 'characterSet', array(1)); ?><br />
		<?php echo CHtml::activeDropDownList($model, 'fromCharacterSet', CHtml::listData($model->characterSets, 'name', 'name')); ?>
		<br />
		<br />
		<b><?php echo Yii::t('core', 'notice'); ?></b><br />
		<?php echo Yii::t('core', 'compressionWillBeAutomaticallyDetected'); ?>
	</fieldset>
	<fieldset>
		<legend><?php echo Yii::t('core', 'settings'); ?></legend>
		<?php echo CHtml::activeCheckBox($model, 'partialImport'); ?>
		<?php echo CHtml::activeLabel($model, 'partialImport');?>
	</fieldset>
	<div class="buttons">
		<a href="javascript:void(0);" onclick="$('#Import').submit();" class="icon button"> <?php echo Html::icon('import'); ?>
			<span><?php echo Yii::t('core', 'import'); ?></span> 
		</a> 
		<input type="hidden" name="Import" value="true" />
	</div>
<?php echo CHtml::endForm(); ?>

<script type="text/javascript">
	setTimeout(function() {
		globalImport.setup();
	}, 500);
</script>

<?php } elseif($model->view == 'submit') { ?>

	<div id="progressbar" style="height: 20px;"></div><br/>
	
	<script type="text/javascript">

			chive.loadingIndicator = false;
	
			$("#progressbar").progressbar({
				value: 0
			});
	
			doRequest(0, 0);
			
			function doRequest(_position, _executedQueries)
			{
				$.getJSON("<?php echo $model->formTarget; ?>", {
					position: 				_position, 
					file:					'<?php echo $model->file; ?>',
					fileSize:				'<?php echo $model->fileSize; ?>',
					totalExecutedQueries:	_executedQueries
				},
				function(response)
				{
					AjaxResponse.handle(response);
	
					if(response.data.finished  || response.data.error)
					{
						return;
					}				
	
					doRequest(response.data.position, response.data.totalExecutedQueries);
					$('#progressbar').progressbar('value', response.data.position / <?php echo $model->fileSize; ?> * 100); 
				});
			}
	
	</script>


<?php } ?>