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
 echo CHtml::form(Yii::app()->baseUrl . '/' . str_replace('browse', 'sql', Yii::app()->getRequest()->pathInfo), 'post'); ?>
 Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/views/table/browse.js', CClientScript::POS_END); ?>

<table style="width: 100%;">
	<tr>
		<td style="width: 80%;">
			<com:application.extensions.CodePress.CodePress language="sql" name="query" width="100%" height="80px" autogrow="true" value={$query} />
		</td>
		<td style="vertical-align: top; padding: 10px;">
			<a class="icon" href="javascript:void(0);" onclick="Bookmark.add(' echo $this->schema; ?>', query.getCode());">
				<com:Icon size="16" name="bookmark_add" />
				<span> echo Yii::t('core', 'bookmark'); ?></span>
			</a>
			<br/><br/>
			<a class="icon" href="javascript:void(0);" onclick="Profiling.toggle();">
				<com:Icon size="16" name="chart" />
				<span> echo Yii::t('database', 'toggleProfiling'); ?></span>
			</a>
			<br/><br/>
			<a class="icon" href="javascript:void(0);" onclick="$.post(baseUrl + '/ajaxSettings/toggle', {
				name: 'showFullColumnContent',
				scope: 'schema.table.browse',
				object: ' echo $this->schema; ?>. echo $this->view; ?>'
				}, function() {
					reload();
				});;">
				 if( Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $this->schema . '.' .  $this->view)) {?>
					<com:Icon size="16" name="square_green" />
					<span> echo Yii::t('database', 'cutColumnContent'); ?></span>
				 } else { ?>
					<com:Icon size="16" name="square_red" />
					<span> echo Yii::t('database', 'showFullColumnContent'); ?></span>
				 } ?>
			</a>
		</td>
	</tr>
</table>

<div class="buttons">
	 echo CHtml::submitButton('Execute'); ?>
</div>

 echo CHtml::endForm(); ?>

 if(count($data) > 0) { ?>

	<div class="pager top">
	 $this->widget('LinkPager',array('pages'=>$pages)); ?>
	</div>

	<br/>

	 $i = 0; ?>
	<table class="list" id="browse">
		<colgroup>
			 foreach ($columns AS $column) { ?>
				 echo '<col />'; ?>
			 } ?>
		</colgroup>
		<thead>
			<tr>
				 foreach ($columns AS $column) { ?>
					<th> echo ($type == 'select' ? $sort->link($column) : $column); ?></th>
				 } ?>
			</tr>
		</thead>
		<tbody>
			 foreach($data AS $row) { ?>
				<tr id="row_ echo $i; ?>">
					 foreach($row AS $key=>$value) { ?>
						<td class=" echo $key; ?>">
							 echo is_null($value) ? '<span class="null">NULL</span>' : (Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $this->schema . '.' .  $this->view) ? str_replace(array('<','>'),array('&lt;','&gt;'),$value) : StringUtil::cutText(str_replace(array('<','>'),array('&lt;','&gt;'),$value), 100)); ?>
						</td>
					 } ?>
				</tr>
				 $i++; ?>
			 } ?>
		</tbody>
	</table>

	<div class="pager bottom">
	 $this->widget('LinkPager',array('pages'=>$pages)); ?>
	</div>

 }  elseif($this->isSent) { ?>
	Es wurden keine Enträge gefunden!
 } ?>

<script type="text/javascript">
	AjaxResponse.handle( echo $response; ?>);
</script>