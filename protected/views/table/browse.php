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
# echo CHtml::form(Yii::app()->baseUrl . '/' . str_replace('browse', 'sql', Yii::app()->getRequest()->pathInfo), 'post'); ?>

<div id="deleteRowDialog" title=" echo Yii::t('message', 'deleteRows'); ?>" style="display: none">
	 echo Yii::t('message', 'doYouReallyWantToDeleteSelectedRows'); ?>
</div>

<table style="width: 100%;">
	<tr>
		<td style="width: 80%;">
			<!---
			<com:application.extensions.CodePress.CodePress language="sql" name="query" width="100%" height="80px" autogrow="true" value={$query} />
			--->
			<textarea name="query" style="width: 99%; height: 90px;" id="query"> echo $query; ?></textarea>
			<div class="buttons">
				<a href="javascript:void(0);" onclick="$('form').submit();" class="icon button">
					<com:Icon size="16" name="execute" text="core.execute" />
					<span> echo Yii::t('core', 'execute'); ?></span>
				</a>
			</div>
		</td>
		<td style="vertical-align: top; padding: 2px 5px;">
			<a class="icon button" href="javascript:void(0);" onclick="Bookmark.add(' echo $this->schema; ?>', $('#query').val());">
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
					object: ' echo $this->schema; ?>. echo $this->table; ?>'
				}, function() {
					refresh();
				});">
				 if( Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $this->schema . '.' .  $this->table)) {?>
					<com:Icon size="16" name="square_green" />
				 } else { ?>
					<com:Icon size="16" name="square_red" />
				 } ?>
				<span> echo Yii::t('core', 'showFullColumnContent'); ?></span>
			</a>
		</td>
	</tr>
</table>


 echo CHtml::endForm(); ?>

 if(count($data) > 0) { ?>

	<div class="list">

		<div class="pager top">
			 $this->widget('LinkPager',array('pages'=>$pages, 'cssFile'=>false)); ?>
		</div>

		 $i = 0; ?>
		<table class="list  if($type == 'select' && $table->primaryKey !== null) { ?>addCheckboxes editable } ?>" style="width: auto;" id="browse">
			<colgroup>
				<col class="checkbox" />
				 if($type == 'select') { ?>
					<col class="action" />
					<col class="action" />
					<col class="action" />
				 } ?>
				 foreach ($columns AS $column) { ?>
					 echo '<col class="date" />'; ?>
				 } ?>
			</colgroup>
			<thead>
				<tr>
					 if($type == 'select') { ?>
						<th><input type="checkbox" /></th>
						<th></th>
						<th></th>
						<th></th>
					 } ?>
					 foreach ($columns AS $column) { ?>
						<th> echo ($type == 'select' ? $sort->link($column) : $column); ?></th>
					 } ?>
				</tr>
			</thead>
			<tbody>
				 foreach($data AS $row) { ?>
					<tr>
						 if($type == 'select') { ?>
							<td>
								<input type="checkbox" name="browse[]" value="row_ echo $i; ?>" />
							</td>
							<td class="action">
								<a href="javascript:void(0);" class="icon" onclick="tableBrowse.deleteRow( echo $i; ?>);">
									<com:Icon name="delete" size="16" text="core.delete" />
								</a>
							</td>
							<td class="action">
								<a href="javascript:void(0);" class="icon" onclick="tableBrowse.editRow( echo $i; ?>);">
									<com:Icon name="edit" size="16" text="core.edit" />
								</a>
							</td>
							<td class="action">
								<a href="javascript:void(0);" class="icon" onclick="tableBrowse.deleteRow( echo $i; ?>);">
									<com:Icon name="insert" size="16" text="core.insert" />
								</a>
							</td>
						 } ?>
						 foreach($row AS $key=>$value) { ?>
							<td class=" echo $key; ?>">
								 if(DataType::getBaseType($table->columns[$key]->dbType) == "blob" && $value) { ?>
									<a href="javascript:void(0);" class="icon" onclick="download(' echo BASEURL; ?>/row/download', {key: JSON.stringify(keyData[ echo $i; ?>]), column: ' echo $column; ?>', table: ' echo $this->table; ?>', schema: ' echo $this->schema; ?>'})">
										<com:Icon name="save" text="core.download" size="16" />
										 echo Yii::t('core', 'download'); ?>
									</a>
								 } else { ?>
									<span> echo is_null($value) ? '<span class="null">NULL</span>' : (Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $this->schema . '.' .  $this->table) ? str_replace(array('<','>'),array('&lt;','&gt;'),$value) : StringUtil::cutText(str_replace(array('<','>'),array('&lt;','&gt;'),$value), 100)); ?></span>
								 } ?>
							</td>

							 if($type == 'select' && ($table->primaryKey === null || in_array($key, (array)$table->primaryKey))) { ?>
								 $keyData[$i][$key] = $value; ?>
							 } ?>

						 } ?>
					</tr>
					 $i++; ?>
				 } ?>
			</tbody>
		</table>

		 if ($type == 'select') { ?>
			<div class="withSelected">
				<span class="icon">
					<com:Icon name="arrow_turn_090" size="16" />
					<span> echo Yii::t('core', 'withSelected'); ?></span>
				</span>
				<a class="icon button" href="javascript:void(0)" onclick="tableBrowse.deleteRows()">
					<com:Icon name="delete" size="16" text="core.delete" />
					<span> echo Yii::t('core', 'delete'); ?></span>
				</a>
				<a class="icon button" href="javascript:void(0)" onclick="tableBrowse.exportRows()">
					<com:Icon name="save" size="16" text="core.export" />
					<span> echo Yii::t('core', 'export'); ?></span>
				</a>
			</div>
			 if ($table->primaryKey !== null) { ?>
				<script type="text/javascript">
					var keyData =  echo json_encode($keyData); ?>;
				</script>
			 } ?>
		 } ?>

		<div class="pager bottom">
			 $this->widget('LinkPager',array('pages'=>$pages, 'cssFile'=>false)); ?>
		</div>

	</div>

 } elseif($isSent) { ?>
	 echo Yii::t('message', 'emptyResultSet'); ?>
 } ?>

<script type="text/javascript">
	tableBrowse.setup();
	AjaxResponse.handle( echo $response; ?>);
</script>