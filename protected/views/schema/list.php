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
<div id="dropSchemataDialog" title=" echo Yii::t('database', 'dropSchemata'); ?>" style="display: none">
	 echo Yii::t('database', 'doYouReallyWantToDropSchemata'); ?>
	<ul></ul>
</div>

<div class="list">

	<div class="buttonContainer">

		<div class="left">
			 $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			 if(Yii::app()->user->privileges->checkGlobal('CREATE')) { ?>
				<a href="javascript:void(0)" onclick="schemaList.addSchema()" class="icon button">
					<com:Icon name="add" size="16" />
					<span> echo Yii::t('database', 'addSchema'); ?></span>
				</a>
			 } else { ?>
				<span class="icon button">
					<com:Icon name="add" size="16" disabled="disabled" />
					<span> echo Yii::t('database', 'addSchema'); ?></span>
				</span>
			 } ?>
		</div>
	</div>

	<div class="clear"></div>

	<table id="schemata" class="list addCheckboxes selectable">
		<colgroup>
			<col class="checkbox" />
			<col />
			<col style="width: 80px" />
			<col class="collation" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" /></th>
				<th> echo $sort->link('SCHEMA_NAME'); ?></th>
				<th> echo $sort->link('tableCount'); ?></th>
				<th colspan="4"> echo $sort->link('DEFAULT_COLLATION_NAME'); ?></th>
			</tr>
		</thead>
		<tbody>

			 $canDrop = false; ?>
			 foreach($schemaList as $n => $model) { ?>
				<tr id="schemata_ echo $model->SCHEMA_NAME; ?>">
					<td>
						<input type="checkbox" name="schemata[]" value=" echo $model->SCHEMA_NAME; ?>" />
					</td>
					<td>
						 echo CHtml::link($model->SCHEMA_NAME, 'schema/' . $model->SCHEMA_NAME); ?>
					</td>
					<td class="count">
						 echo $model->tableCount; ?>
					</td>
					<td>
						<dfn class="collation" title=" echo Collation::getDefinition($model->DEFAULT_COLLATION_NAME); ?>">
							 echo $model->DEFAULT_COLLATION_NAME; ?>
						</dfn>
					</td>
					<td>
						<com:Icon name="privileges" size="16" text="core.privileges" disabled="true" />
					</td>
					<td>
						 if(Yii::app()->user->privileges->checkSchema($model->SCHEMA_NAME, 'ALTER')) { ?>
							<a href="javascript:void(0)" onclick="schemaList.editSchema(' echo $model->SCHEMA_NAME; ?>')" class="icon">
								<com:Icon name="edit" size="16" text="core.edit" />
							</a>
						 } else { ?>
							<com:Icon name="edit" size="16" text="core.edit" disabled="true" />
						 } ?>
					</td>
					<td>
						 if(Yii::app()->user->privileges->checkSchema($model->SCHEMA_NAME, 'DROP')) { ?>
							 $canDrop = true; ?>
							<a href="javascript:void(0)" onclick="schemaList.dropSchema(' echo $model->SCHEMA_NAME; ?>')" class="icon">
								<com:Icon name="delete" size="16" text="database.drop" />
							</a>
						 } else { ?>
							<com:Icon name="delete" size="16" text="database.drop" disabled="true" />
						 } ?>
					</td>
				</tr>
			 } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="6">
					 echo Yii::t('database', 'showingXSchemata', array('{count}' => $schemaCountThisPage, '{total}' => $schemaCount)); ?>
				</th>
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
				<a class="icon button" href="javascript:void(0)" onclick="schemaList.dropSchemata()">
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
			 if(Yii::app()->user->privileges->checkGlobal('CREATE')) { ?>
				<a href="javascript:void(0)" onclick="schemaList.addSchema()" class="icon button">
					<com:Icon name="add" size="16" />
					<span> echo Yii::t('database', 'addSchema'); ?></span>
				</a>
			 } else { ?>
				<span class="icon button">
					<com:Icon name="add" size="16" disabled="disabled" />
					<span> echo Yii::t('database', 'addSchema'); ?></span>
				</span>
			 } ?>
		</div>
	</div>

	<div class="clear"></div>

	<!---
	<div class="pager bottom">
		 $this->widget('LinkPager',array('pages'=>$pages, 'cssFile'=>false, 'nextPageLabel'=>'&raquo;', 'prevPageLabel'=>'&laquo;')); ?>
	</div>
	 --->

</div>

<script type="text/javascript">
setTimeout(function() {
	schemaList.setup();
}, 500);
</script>