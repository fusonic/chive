<?php if($model->view == 'form') { ?>

	<?php echo CHtml::form('', 'post', array('id' => 'Export')); ?>
		<?php if($model->selectedObjects) { ?>
		<div style="float: left; width: 250px">
			<?php if($model->objects) { ?>
				<?php echo CHtml::dropDownList('Export[objects][]', $model->selectedObjects, $model->objects, array('size' => 20, 'multiple' => true, 'style' => 'width: 100%')); ?>
			<?php } ?>
		</div>
		<?php } ?>
		<div style="<?php if($model->selectedObjects) { echo "margin-left: 260px;"; } ?> width: 600px">
			<?php $firstExporter = current(array_keys($model->exporters)); ?>
			<div id="exporterType">
				<fieldset>
					<legend><?php echo Yii::t('core', 'type'); ?></legend>
					<?php echo CHtml::radioButtonList('Export[exporter]', $firstExporter, $model->exporters, array('separator' => ' &nbsp; ')); ?>
				</fieldset>
			</div>
			<div id="exporterSettings">
				<?php foreach($model->exporterInstances AS $exporter) { ?>
					<div id="exporterSettings_<?php echo get_class($exporter); ?>">
						<?php echo $exporter->getSettingsView(); ?>
					</div>
				<?php } ?>
			</div>
			<div>
				<a href="javascript:void(0)" onclick="globalExport.view()" class="icon button primary">
					<?php echo Html::icon('search', 16, false, 'export.show'); ?>
					<span><?php echo Yii::t('core', 'show'); ?></span>
				</a>
				<a href="javascript:void(0)" onclick="globalExport.save()" class="icon button">
					<?php echo Html::icon('save', 16, false, 'export.download'); ?>
					<span><?php echo Yii::t('core', 'download'); ?></span>
				</a>
				<?php if(function_exists('gzencode')) { ?>
					<a href="javascript:void(0)" onclick="globalExport.save('gzip')" class="icon button">
						<?php echo Html::icon('save', 16, false, 'export.download'); ?>
						<span>Gzip</span>
					</a>
				<?php } ?>
				<?php if(function_exists('bzcompress')) { ?>
					<a href="javascript:void(0)" onclick="globalExport.save('bzip2')" class="icon button">
						<?php echo Html::icon('save', 16, false, 'export.download'); ?>
						<span>Bzip2</span>
					</a>
				<?php } ?>

				<?php echo CHtml::hiddenField('Export[action]', ''); ?>
				<?php echo CHtml::hiddenField('Export[compression]', ''); ?>
				<?php echo CHtml::hiddenField('Export[rows]', CJSON::encode($model->getRows())); ?>
			</div>
		</div>
	<?php echo CHtml::endForm(); ?>

	<script type="text/javascript">
	setTimeout(function() {
		globalExport.setup();
		<?php if(@ini_get('xdebug.profiler_enable')) { ?>
			Notification.add('warning', lang.get('core', 'warning'), lang.get('core', 'xDebugExportWarning'));
		<?php } ?>
	}, 500);
	</script>

<?php } else { ?>

	<?php
	// If we use the Yii textarea htmlspecialchars is called with the charset parameter > htmlspecialchars returns an empty string.
	echo CHtml::tag('textarea', array('style' => 'height: 500px', 'wrap' => 'off'), htmlspecialchars($model->result, ENT_QUOTES));
	?>

<?php } ?>