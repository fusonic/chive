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
 echo CHtml::form(Yii::app()->baseUrl . '/schema/' . $this->schema . '/sql' , 'post'); ?>

 if($error) { ?>
	<div class="errorSummary">
		 echo $error; ?>
	</div>
 } ?>

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
			<a class="icon" href="javascript:void(0);" onclick="">
				<com:Icon size="16" name="chart" />
				<span> echo Yii::t('database', 'profiling'); ?></span>
			</a>
		</td>
	</tr>
</table>

<div class="buttons">
	 echo CHtml::submitButton('Execute'); ?>
</div>


 echo CHtml::endForm(); ?>

 if(count($data)) { ?>

	<div class="pager top">
	 $this->widget('LinkPager',array('pages'=>$pages)); ?>
	</div>

	<br/>

	<table class="list" style="width: auto;" id="browse">
		<colgroup>
			<col class="action" />
			<col class="action" />
			 foreach ($columns AS $column) { ?>
				 echo '<col class="date" />'; ?>
			 } ?>
		</colgroup>
		<thead>
			<tr>
				<th></th>
				<th></th>
				 foreach ($columns AS $column) { ?>
					<th> echo $sort->link($column); ?></th>
				 } ?>
			</tr>
		</thead>
		<tbody>
			 foreach($data AS $row) { ?>
				<tr>
					<td>
						<a href="" class="icon">
							<com:Icon name="edit" size="16" text="core.edit" />
						</a>
					</td>
					<td>
						<a href="" class="icon">
							<com:Icon name="delete" size="16" text="core.edit" />
						</a>
					</td>
					 foreach($row AS $key=>$value) { ?>
						<td>
							 echo (is_null($value) ? '<i>NULL</i>' : substr(str_replace(array('<','>'),array('&lt;','&gt;'),$value), 0, 100)); ?>
						</td>
					 } ?>
				</tr>
			 } ?>
		</tbody>
	</table>

	<div class="pager bottom">
	 $this->widget('LinkPager',array('pages'=>$pages)); ?>
	</div>

 }  elseif($isSent) { ?>
	Es wurden keine Enträge gefunden!
 } ?>