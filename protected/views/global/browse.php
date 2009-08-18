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

<div id="deleteRowDialog" title=" echo Yii::t('message', 'deleteRows'); ?>" style="display: none">
	 echo Yii::t('message', 'doYouReallyWantToDeleteSelectedRows'); ?>
</div>

 if($model->showInput) { ?>

	 echo CHtml::form(BASEURL . '/' . $model->formTarget, 'post', array('id' => 'queryForm')); ?>
	<table style="width: 100%;">
		<tr>
			<td style="width: 80%;">
				 $this->widget("SqlEditor", array(
				    'id' => 'query',
					'autogrow' => true,
				   	'htmlOptions' => array('name' => 'query'),
					'value' => $model->getOriginalQueries(),
					)); ?>
				 /*<textarea name="query" style="width: 99%; height: 90px;" id="query"> echo $model->getOriginalQueries(); ?></textarea> */ ?>
				<div class="buttons">
					<a href="javascript:void(0);" onclick="$('#queryForm').submit();" class="icon button">
						<com:Icon size="16" name="execute" text="core.execute" />
						<span> echo Yii::t('core', 'execute'); ?></span>
					</a>
				</div>
			</td>
			<td style="vertical-align: top; padding: 2px 5px;">
				<a class="icon button" href="javascript:void(0);" onclick="Bookmark.add(' echo $model->schema; ?>', (editAreaLoader ? editAreaLoader.getValue('query') : $('#query').val()));">
					<com:Icon size="16" name="bookmark_add" />
					<span> echo Yii::t('core', 'bookmark'); ?></span>
				</a>
				<br/><br/>
				<a class="icon button" href="javascript:void(0);" onclick="Profiling.toggle();">
					 if( Yii::app()->user->settings->get('profiling')) {?>
						<com:Icon size="16" name="square_green" text="core.on" htmlOptions={array('id'=>'profiling_indicator')} />
					 } else { ?>
						<com:Icon size="16" name="square_red" text="core.off" htmlOptions={array('id'=>'profiling_indicator')} />
					 } ?>
					<span> echo Yii::t('database', 'profiling'); ?></span>
				</a>
				<br/><br/>
				<a class="icon button" href="javascript:void(0);" onclick="$.post(baseUrl + '/ajaxSettings/toggle', {
						name: 'showFullColumnContent',
						scope: 'schema.table.browse',
						object: ' echo $model->schema; ?>. echo $model->table; ?>'
					}, function() {
						refresh();
					});">
					 if( Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $model->schema . '.' .  $model->table)) {?>
						<com:Icon size="16" name="square_green" />
					 } else { ?>
						<com:Icon size="16" name="square_red" />
					 } ?>
					<span> echo Yii::t('core', 'showFullColumnContent'); ?></span>
				</a>
				<br/><br/>
				<a id="aToggleEditor" class="icon button" href="javascript:void(0);" onclick="toggleEditor('query','aToggleEditor');">
					 if( Yii::app()->user->settings->get('sqlEditorOn') == '1') {?>
						<com:Icon size="16" name="square_green" />
					 } else { ?>
						<com:Icon size="16" name="square_red" />
					 } ?>
					<span> echo Yii::t('core', 'toggleEditor'); ?></span>
				</a>
			</td>
		</tr>
	</table>

	 echo CHtml::endForm(); ?>

	<script type="text/javascript">
		$('#queryForm').ajaxForm({
			success: 	function(responseText)
			{
				AjaxResponse.handle(responseText);
				$('div.ui-layout-center').html(responseText);
				init();
			}
		});
	</script>

 } ?>

 if($model->hasResultSet() && $model->getData()) { ?>

	<div class="list">
		<div class="buttonContainer">
			 $this->widget('LinkPager',array('pages'=>$model->getPagination())); ?>
		</div>

		 $i = 0; ?>
		<table class="list  if($model->getIsUpdatable()) { ?>addCheckboxes editable } ?>" style="width: auto;" id="browse">
			<colgroup>
				<col class="checkbox" />
				 if($type == 'select') { ?>
					<col class="action" />
					<col class="action" />
					<col class="action" />
				 } ?>
				 foreach ($model->getColumns() AS $column) { ?>
					 echo '<col />'; ?>
				 } ?>
			</colgroup>
			<thead>
				<tr>
					 if($model->getQueryType() == 'select') { ?>
						<th><input type="checkbox" /></th>
						<th></th>
						<th></th>
						<th></th>
					 } ?>
					 foreach ($model->getColumns ()AS $column) { ?>
						<th> echo ($model->getQueryType() == 'select' ? $model->getSort()->link($column) : $column); ?></th>
					 } ?>
				</tr>
			</thead>
			<tbody>
				 foreach($model->getData() AS $row) { ?>
					<tr>
						 if($model->getQueryType() == 'select') { ?>
							<td>
								<input type="checkbox" name="browse[]" value="row_ echo $i; ?>" />
							</td>
							<td class="action">
								<a href="javascript:void(0);" class="icon" onclick="globalBrowse.deleteRow( echo $i; ?>);">
									<com:Icon name="delete" size="16" text="core.delete" />
								</a>
							</td>
							<td class="action">
								<a href="javascript:void(0);" class="icon" onclick="globalBrowse.editRow( echo $i; ?>);">
									<com:Icon name="edit" size="16" text="core.edit" />
								</a>
							</td>
							<td class="action">
								<a href="javascript:void(0);" class="icon" onclick="globalBrowse.insertAsNewRow( echo $i; ?>);">
									<com:Icon name="insert" size="16" text="core.insert" />
								</a>
							</td>
						 } ?>
						 foreach($row AS $key=>$value) { ?>
							<td class=" echo $key; ?>">
								 if(DataType::getInputType($model->getTable()->columns[$key]->dbType) == "file" && $value) { ?>
									<a href="javascript:void(0);" class="icon" onclick="download(' echo BASEURL; ?>/row/download', {key: JSON.stringify(keyData[ echo $i; ?>]), column: ' echo $column; ?>', table: ' echo $model->table; ?>', schema: ' echo $model->schema; ?>'})">
										<com:Icon name="save" text="core.download" size="16" />
										 echo Yii::t('core', 'download'); ?>
									</a>
								 } elseif($model->table !== null) { ?>
									<span> echo is_null($value) ? '<span class="null">NULL</span>' : (Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $model->schema . '.' .  $model->table) ? htmlspecialchars($value) : StringUtil::cutText(htmlspecialchars($value), 100)); ?></span>
								 } else { ?>
									<span> echo is_null($value) ? '<span class="null">NULL</span>' : (Yii::app()->user->settings->get('showFullColumnContent', 'schema.browse', $model->schema) ? htmlspecialchars($value) : StringUtil::cutText(htmlspecialchars($value), 100)); ?></span>
								 } ?>
							</td>
							 if($model->getIsUpdatable() && (in_array($key, (array)$model->getTable()->primaryKey) || $model->getTable()->primaryKey === null)) { ?>
								 $keyData[$i][$key] = is_null($value) ? null : $value; ?>
							 } ?>
						 } ?>
					</tr>
					 $i++; ?>
				 } ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan=" echo 4 + count($row); ?>">
						 echo Yii::t('database', 'showingRowsOfRows', array('{start}' => $model->getStart(), '{end}' => $model->getStart() + $model->getPagination()->getPagesize(), '{total}' => $model->getTotal())); ?>
					</th>
				</tr>
			</tfoot>
		</table>

	<div class="buttonContainer">
		 if ($model->getQueryType() == 'select') { ?>
			<div class="withSelected left">
				<span class="icon">
					<com:Icon name="arrow_turn_090" size="16" />
					<span> echo Yii::t('core', 'withSelected'); ?></span>
				</span>
				<a class="icon button" href="javascript:void(0)" onclick="globalBrowse.deleteRows()">
					<com:Icon name="delete" size="16" text="core.delete" />
					<span> echo Yii::t('core', 'delete'); ?></span>
				</a>
				<a class="icon button" href="javascript:void(0)" onclick="globalBrowse.exportRows()">
					<com:Icon name="save" size="16" text="core.export" />
					<span> echo Yii::t('core', 'export'); ?></span>
				</a>
			</div>
			 if ($keyData) { ?>
				<script type="text/javascript">
					var keyData =  echo json_encode($keyData); ?>;
				</script>
			 } ?>
		 } ?>
	</div>
	<div class="buttonContainer">
		 $this->widget('LinkPager',array('pages'=>$model->getPagination())); ?>
	</div>

 } elseif($model->execute) { ?>
	 echo Yii::t('message', 'emptyResultSet'); ?>
 } ?>

<script type="text/javascript">
	globalBrowse.setup();
	AjaxResponse.handle( echo $model->getResponse(); ?>);
</script>