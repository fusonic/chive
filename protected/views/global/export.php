<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
 * 
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */
 if($model->view == 'form') { ?>

	 echo CHtml::form('', 'post', array('id' => 'Export')); ?>
		 if($model->selectedObjects) { ?>
		<div style="float: left; width: 250px">
			 if($model->objects) { ?>
				 echo CHtml::dropDownList('Export[objects][]', $model->selectedObjects, $model->objects, array('size' => 20, 'multiple' => true, 'style' => 'width: 100%')); ?>
			 } ?>
		</div>
		 } ?>
		<div style=" if($model->selectedObjects) { echo "margin-left: 260px;"; } ?> width: 600px">
			 $firstExporter = current(array_keys($model->exporters)); ?>
			<div id="exporterType">
				<fieldset>
					<legend> echo Yii::t('core', 'type'); ?></legend>
					 echo CHtml::radioButtonList('Export[exporter]', $firstExporter, $model->exporters, array('separator' => ' &nbsp; ')); ?>
				</fieldset>
			</div>
			<div id="exporterSettings">
				 foreach($model->exporterInstances AS $exporter) { ?>
					<div id="exporterSettings_ echo get_class($exporter); ?>">
						 echo $exporter->getSettingsView(); ?>
					</div>
				 } ?>
			</div>
			<div>
				<a href="javascript:void(0)" onclick="globalExport.view()" class="icon button">
					<com:Icon size="16" name="search" text="export.show" />
					<span> echo Yii::t('export', 'show'); ?></span>
				</a>
				<a href="javascript:void(0)" onclick="globalExport.save()" class="icon button">
					<com:Icon size="16" name="save" text="export.download" />
					<span> echo Yii::t('export', 'download'); ?></span>
				</a>
				 if(function_exists('gzencode')) { ?>
					<a href="javascript:void(0)" onclick="globalExport.save('gzip')" class="icon button">
						<com:Icon size="16" name="save" text="export.download" />
						<span>Gzip</span>
					</a>
				 } ?>
				 if(function_exists('bzcompress')) { ?>
					<a href="javascript:void(0)" onclick="globalExport.save('bzip2')" class="icon button">
						<com:Icon size="16" name="save" text="export.download" />
						<span>Bzip2</span>
					</a>
				 } ?>

				 echo CHtml::hiddenField('Export[action]', ''); ?>
				 echo CHtml::hiddenField('Export[compression]', ''); ?>
				 echo CHtml::hiddenField('Export[rows]', json_encode($model->getRows())); ?>
			</div>
		</div>
	 echo CHtml::endForm(); ?>

	<script type="text/javascript">
	setTimeout(function() {
		globalExport.setup();
		 if(@ini_get('xdebug.profiler_enable')) { ?>
			Notification.add('warning', lang.get('core', 'warning'), lang.get('message', 'xDebugExportWarning'));
		 } ?>
	}, 500);
	</script>

 } else { ?>

	
	// If we use the Yii textarea htmlspecialchars is called with the charset parameter > htmlspecialchars returns an empty string.
	echo CHtml::tag('textarea', array('style' => 'height: 500px', 'wrap' => 'off'), htmlspecialchars($model->result, ENT_QUOTES));
	?>

 } ?>