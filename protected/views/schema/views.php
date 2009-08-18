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
<div id="dropViewsDialog" title=" echo Yii::t('database', 'dropViews'); ?>" style="display: none">
	 echo Yii::t('database', 'doYouReallyWantToDropViews'); ?>
	<ul></ul>
</div>

<div class="list">
	<div class="buttonContainer">
		<div class="left">
			 $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaViews.addView()">
				<com:Icon name="add" size="16" />
				<span> echo Yii::t('database', 'addView'); ?></span>
			</a>
		</div>
	</div>

	<table class="list addCheckboxes selectable" id="views">
		<colgroup>
			<col class="checkbox" />
			<col />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="6"> echo $sort->link('TABLE_NAME', Yii::t('database', 'view')); ?></th>
				<th> echo $sort->link('IS_UPDATABLE'); ?></th>
			</tr>
		</thead>
		<tbody>
			 if($viewCount < 1) { ?>
				<tr>
					<td class="noEntries" colspan="14">
						 echo Yii::t('database', 'noViews'); ?>
					</td>
				</tr>
			 } ?>
			 foreach($schema->views AS $view) { ?>
				<tr id="views_ echo $view->TABLE_NAME; ?>">
					<td>
						<input type="checkbox" name="views[]" value=" echo $view->TABLE_NAME; ?>" />
					</td>
					<td>
						<a href="#views/ echo $view->TABLE_NAME; ?>/structure">
							 echo $view->TABLE_NAME; ?>
						</a>
					</td>
					<td>
						<a href="#views/ echo $view->TABLE_NAME; ?>/browse" class="icon">
							<com:Icon name="browse" size="16" text="database.browse" />
						</a>
					</td>
					<td>
						<a href="#tables/ echo $view->TABLE_NAME; ?>/structure" class="icon">
							<com:Icon name="structure" size="16" text="database.structure" />
						</a>
					</td>
					<td>
						<a href="#tables/ echo $table->TABLE_NAME; ?>/search" class="icon">
							<com:Icon name="search" size="16" text="core.search" />
						</a>
					</td>
					<td>
						 if(Yii::app()->user->privileges->checkTable($view->TABLE_SCHEMA, $view->TABLE_NAME, 'ALTER')) { ?>
							<a href="javascript:void(0);" onclick="schemaViews.editView($(this).closest('tr').attr('id').substr(6))" class="icon">
								<com:Icon name="edit" size="16" text="core.edit" />
							</a>
						 } else { ?>
							<com:Icon name="edit" size="16" text="core.edit" disabled="true" />
						 } ?>
					</td>
					<td>
						 if(Yii::app()->user->privileges->checkTable($view->TABLE_SCHEMA, $view->TABLE_NAME, 'DROP')) { ?>
							<a href="javascript:void(0);" onclick="schemaViews.dropView($(this).closest('tr').attr('id').substr(6))" class="icon">
								<com:Icon name="delete" size="16" text="database.drop" />
							</a>
							 $canDrop = true; ?>
						 } else { ?>
							<com:Icon name="delete" size="16" text="database.drop" disabled="true" />
						 } ?>
					</td>
					<td>
						 echo Yii::t('core', strtolower($view->IS_UPDATABLE)); ?>
					</td>
				</tr>
			 } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="7"> echo Yii::t('database', 'amountViews', array($viewCount, '{amount} '=> $viewCount)); ?></th>
			</tr>
		</tfoot>
	</table>

	<div class="buttonContainer">
		<div class="left withSelected">
			<span class="icon">
				<com:Icon name="arrow_turn_090" size="16" />
				<span> echo Yii::t('core', 'withSelected'); ?></span>
			</span>
			 if($canDrop) { ?>
				<a href="javascript:void(0)" onclick="schemaViews.dropViews()" class="icon button">
					<com:Icon name="delete" size="16" />
					<span> echo Yii::t('database', 'drop'); ?></span>
				</a>
			 } else { ?>
				<span class="icon button">
					<com:Icon name="delete" size="16" disabled="true" />
					<span> echo Yii::t('database', 'drop'); ?></span>
				</span>
			 } ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaViews.addView()">
				<com:Icon name="add" size="16" />
				<span> echo Yii::t('database', 'addView'); ?></span>
			</a>
		</div>
	</div>

</div>

<script type="text/javascript">
setTimeout(function() {
	schemaViews.setupDialogs();
}, 500);
</script>