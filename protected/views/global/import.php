<?php if($model->view == 'form') { ?>

	<?php echo CHtml::form($model->formTarget, 'post', array('id' => 'Import')); ?>
		<?php echo CHtml::hiddenField("MAX_FILE_SIZE", ConfigUtil::getMaxUploadSize() - 1000); ?>
		<?php echo CHtml::hiddenField("Import", "true"); ?>
		<fieldset>
			<legend><?php echo Yii::t('core', 'importFile'); ?></legend>
			<?php echo CHtml::fileField('file', '', array()); ?>&nbsp;
			(<?php echo Yii::t('core', 'maximum'); ?>: <?php echo ConfigUtil::getMaxUploadSize(true); ?>)
			<br />
			<br />
			<?php //echo Yii::t('core', 'characterSet', array(1)); ?><br />
			<?php //echo CHtml::activeDropDownList($model, 'fromCharacterSet', CHtml::listData($model->characterSets, 'name', 'name')); ?>
			<b><?php echo Yii::t('core', 'notice'); ?></b><br />
			<?php echo Yii::t('core', 'compressionWillBeAutomaticallyDetected'); ?>
		</fieldset>
		<fieldset>
			<legend><?php echo Yii::t('core', 'settings'); ?></legend>
			<?php echo CHtml::activeCheckBox($model, 'partialImport'); ?>
			<?php echo CHtml::activeLabel($model, 'partialImport');?>
		</fieldset>
		<div class="buttons">
			<a href="javascript:void(0);" onclick="$('#Import').submit();" class="icon button primary"> 
				<?php echo Html::icon('import'); ?>
				<span><?php echo Yii::t('core', 'import'); ?></span> 
			</a> 
		</div>
	<?php echo CHtml::endForm(); ?>
	
	<script type="text/javascript">
		setTimeout(function() {
			globalImport.setup();
		}, 500);
	</script>
	
	<?php if($model->fileUploadError) { ?>
		<script type="text/javascript">
		Notification.add('error', lang.get('core', 'errorUploadingFile'));
		</script>
	<?php } ?>

<?php } elseif($model->view == 'submit') { ?>

	<div id="progressbar" style="height: 20px;"></div><br/>
	
	<table class="list" id="messages" style="display: none;">
		<colgroup>
			<col style="width: 20px;">
			<col>
			<col style="width: 20px;">
		</colgroup>
		<thead>
			<tr>
				<th colspan="3"><?php echo Yii::t('core', 'information'); ?></th>
			</tr>
		</thead>
		<tbody id="messagesContent">
		</tbody>
	</table>
	
	<br/>
	
	<div id="buttonContainer" class="buttons" style="display: none">
		<a href="javascript:void(0);" onclick="AjaxResponse.handle(lastResponse);" class="icon button primary">
			<?php echo Html::icon('success', 16, false, 'core.ok'); ?>
			<span><?php echo Yii::t('core', 'ok'); ?></span>
		</a>
	</div>
	
	<script type="text/javascript">

			var errorCount = 0;
			var lastResponse = "";

			$("#progressbar").progressbar({
				value: 1
			});
	
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

					lastResponse = response;

					if(response.data.error)
					{
						if(!errorCount)
						{
							$('#messages').show();
						}
						
						$.each(response.notifications, function() {

							html = '<tr class="' + (errorCount % 2 == 0 ? 'even' : 'odd') + '">' + 
										'<td>' + 
											'<span class="icon">' +
												'<img class="icon" src="' + iconPath + '/16/' + this.type + '.png" />' +
												
											'</span>' + 
										'</td>' + 
										'<td>' + 
											'<span>' + this.title + '</span>' + 
										'</td>' +
										'<td>' +
											'<img class="icon" src="' + iconPath + '/16/accordion.png" style="cursor: pointer;" onclick="$(this).parent().parent().next().toggle();"/>' +
										'</td>' +
									'</tr>' +
									'<tr style="display: none;"><td colspan="3"><pre class="sql">' + this.code  + '</pre></td></tr>';

							$('#messagesContent').append(html);				
							errorCount++;
							
						});
						
					}
					else if(response.data.finished)
					{
						$('#progressbar').progressbar('value', 100);
						chive.loadingIndicator = true;
						
						if(errorCount == 0)
						{
							AjaxResponse.handle(response);	
						}
						else
						{
							$('#buttonContainer').show();
						}
						
						return false;
					}
	
					doRequest(response.data.position, response.data.totalExecutedQueries);
					$('#progressbar').progressbar('value', response.data.position / <?php echo $model->fileSize; ?> * 100);
					 
				});
			}

			doRequest(0, 0);
	
	</script>


<?php } ?>