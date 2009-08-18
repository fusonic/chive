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
 echo CHtml::form('', 'post', array('enctype'=>'multipart/form-data')); ?>

 echo CHtml::fileField('file', ''); ?>

 echo CHtml::submitButton(Yii::t('core', 'import')); ?>

 echo CHtml::endForm(); ?>


 if($file) { ?>
	a file has been uploaded
 } ?>



<!---
<script type="text/javascript">

var FeaturesDemoHandlers = {
		swfUploadLoaded : function () {
			console.log('ok');
		},
		fileDialogStart : function () {
			console.log('fileDialogStart');
		},

		fileQueued : function (file) {
			console.log('file queued');
			swfu.startUpload();
		},

		fileDialogComplete : function (numFilesSelected, numFilesQueued) {
			console.log('file dialog complete');
		},

		uploadStart : function (file) {
			console.log('upload started');
		},

		uploadProgress : function (file, bytesLoaded, totalBytes) {

			console.log(file + bytesLoaded + totalBytes);
			/*
			try {
				var percent = Math.ceil((bytesLoaded / file.size) * 100);
				if (percent < 10) {
					percent = "  " + percent;
				} else if (percent < 100) {
					percent = " " + percent;
				}

				FeaturesDemo.selQueue.value = file.id;
				var queueString = file.id + ":" + percent + "%:" + file.name;
				FeaturesDemo.selQueue.options[FeaturesDemo.selQueue.selectedIndex].text = queueString;


				FeaturesDemo.selEventsFile.options[FeaturesDemo.selEventsFile.options.length] = new Option("Upload Progress: " + bytesLoaded, "");
			} catch (ex) {
				this.debug(ex);
			}
			*/
		},

		uploadSuccess : function (file, serverData, receivedResponse) {
			console.log('upload finished successfully');

			console.log(file);
			console.log(serverData);
			console.log(receivedResponse);
		},

		uploadError : function (file, errorCode, message) {

			console.log('upload error' + message);

		},

		uploadComplete : function (file) {
			console.log('upload complete');
		},

		// This custom debug method sends all debug messages to the Firebug console.  If debug is enabled it then sends the debug messages
		// to the built in debug console.  Only JavaScript message are sent to the Firebug console when debug is disabled (SWFUpload won't send the messages
		// when debug is disabled).
		debug : function (message) {
			console.log(message);
			try {
				if (window.console && typeof(window.console.error) === "function" && typeof(window.console.log) === "function") {
					if (typeof(message) === "object" && typeof(message.name) === "string" && typeof(message.message) === "string") {
						window.console.error(message);
					} else {
						window.console.log(message);
					}
				}
			} catch (ex) {
			}
			try {
				if (this.settings.debug) {
					this.debugMessage(message);
				}
			} catch (ex1) {
			}
		}
	};

</script>

 echo CHtml::form(null, null, array('enctype'=>'multipart/form-data')); ?>

<span id="testbutton">testasdf</span>

 $this->widget('application.extensions.SWFUpload.SWFUpload', array('settings' => array(
	'debug' => true,
	'upload_url' => Yii::app()->baseUrl . '/schema/' . $this->schema . '/import/upload',
	'button_placeholder_id' => 'testbutton',
	'button_text' => 'testbutton',
	'button_width' => 270,
	'button_height' => 30,
	'button_image_url' => 'http://www.google.at/intl/de_at/images/logo.gif',
	'debug_handler' => '{FeaturesDemoHandlers.debug}',
	'swfupload_loaded_handler' => '{FeaturesDemoHandlers.swfUploadLoaded}',
	'file_dialog_start_handler' => '{FeaturesDemoHandlers.fileDialogStart}',
	'button_cursor' => 'HAND',
	'file_queued_handler' => '{FeaturesDemoHandlers.fileQueued}',
	'upload_start_handler' => '{FeaturesDemoHandlers.uploadStart}',
	'upload_success_handler' => '{FeaturesDemoHandlers.uploadSuccess}',
	'upload_error_handler' => '{FeaturesDemoHandlers.uploadSuccess}',
	'upload_complete_handler' => '{FeaturesDemoHandlers.uploadSuccess}',
))); ?>

 echo CHtml::endForm(); ?>

--->