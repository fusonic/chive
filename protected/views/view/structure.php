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
<div class="list">

	<table id="columns" class="list addCheckboxes">
		<colgroup>
			<col class="checkbox" />
			<col />
			<col class="type" />
			<col class="collation" />
			<col class="null" />
			<col />
			<col />
			<col class="action" />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" /></th>
				<th> echo Yii::t('database','field'); ?></th>
				<th> echo Yii::t('database','type'); ?></th>
				<th> echo Yii::t('database','collation'); ?></th>
				<th> echo Yii::t('database','null'); ?></th>
				<th> echo Yii::t('database','default'); ?></th>
				<th colspan="2"> echo Yii::t('database','extra'); ?></th>
			</tr>
		</thead>
		<tbody>
			 if(count($view->columns) < 1) { ?>
				<tr>
					<td class="noEntries" colspan="7">
						 echo Yii::t('database', 'noColumns'); ?>
					</td>
				</tr>
			 } ?>
			 foreach($view->columns AS $column) { ?>
				<tr id="columns_ echo $column->COLUMN_NAME; ?>">
					<td>
						<input type="checkbox" name="columns[]" value=" echo $column->COLUMN_NAME; ?>" />
					</td>
					<td>
						 if($column->getIsPartOfPrimaryKey($table->indices)): ?>
							<span class="primaryKey"> echo $column->COLUMN_NAME; ?></span>
						 else: ?>
							 echo $column->COLUMN_NAME; ?>
						 endif; ?>
					</td>
					<td>
						 echo $column->COLUMN_TYPE; ?>
					</td>
					<td>
						 if(!is_null($column->COLLATION_NAME)) { ?>
							<dfn class="collation" title=" echo Collation::getDefinition($column->COLLATION_NAME); ?>">
								 echo $column->COLLATION_NAME; ?>
							</dfn>
						 } ?>
					</td>
					<td>
						 echo Yii::t('core', strtolower($column->IS_NULLABLE)); ?>
					</td>
					<td>
						 if(is_null($column->COLUMN_DEFAULT) && $column->IS_NULLABLE == 'YES') { ?>
							<span class="null">NULL</span>
						 } else { ?>
							 echo $column->COLUMN_DEFAULT; ?>
						 } ?>
					</td>
					<td> echo $column->EXTRA; ?></td>
					<td>
						<span class="icon">
							<com:Icon disabled="true" name="browse" size="16" text="schema.browseDistinctValues" title={Yii::t('database','browseDistinctValues')} />
						</span>
					</td>
				</tr>
			 } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="7"> echo Yii::t('database', 'XColumns', array('{count}' => count($view->columns))); ?></th>
			</tr>
		</tfoot>
	</table>

</div>