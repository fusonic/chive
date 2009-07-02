<?php if($model->view == 'form') { ?>

	<?php echo CHtml::form('', 'post', array('id' => 'Export')); ?>
		<div style="float: left; width: 250px">
			<?php if($model->objects) { ?>
				<?php echo CHtml::dropDownList('Export[objects][]', $model->objectKeys, $model->objects, array('size' => 20, 'multiple' => true, 'style' => 'width: 100%')); ?>
			<?php } ?>
		</div>
		<div style="margin-left: 260px">
			<?php $firstExporter = current(array_keys($model->exporters)); ?>
			<div id="exporterType">
				<?php echo Yii::t('core', 'type'); ?>: &nbsp;
				<?php echo CHtml::radioButtonList('Export[exporter]', $firstExporter, $model->exporters, array('separator' => ' &nbsp; ')); ?>
			</div>
			<div id="exporterSettings">
				<?php foreach($model->exporterInstances AS $exporter) { ?>
					<div id="exporterSettings_<?php echo get_class($exporter); ?>">
						<?php echo $exporter->getSettingsView(); ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="buttonContainer">
			<a href="javascript:void(0)" onclick="globalExport.view()" class="icon button">
				<com:Icon size="16" name="search" text="core.view" />
				<span><?php echo Yii::t('core', 'view'); ?></span>
			</a>
			<a href="javascript:void(0)" onclick="globalExport.save()" class="icon button">
				<com:Icon size="16" name="save" text="core.save" />
				<span><?php echo Yii::t('core', 'save'); ?></span>
			</a>

			<?php echo CHtml::hiddenField('Export[action]', ''); ?>
		</div>
	<?php echo CHtml::endForm(); ?>

	<script type="text/javascript">
	setTimeout(function() {
		globalExport.setup();
		<?php if(@ini_get('xdebug.profiler_enable')) { ?>
			Notification.add('warning', lang.get('core', 'warning'), lang.get('message', 'xDebugExportWarning'));
		<?php } ?>
	}, 500);
	</script>

<?php } else { ?>

	<?php echo CHtml::textArea('result', $model->result, array('rows' => 30)); ?>

<?php } ?>