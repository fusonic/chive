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
 
<h2> echo $schema->SCHEMA_NAME; ?></h2>

<div id="truncateTablesDialog" title=" echo Yii::t('database', 'truncateTables'); ?>" style="display: none">
	 echo Yii::t('database', 'doYouReallyWantToTruncateTables'); ?>
</div>
<div id="dropTablesDialog" title=" echo Yii::t('database', 'dropTables'); ?>" style="display: none">
	 echo Yii::t('database', 'doYouReallyWantToDropTables'); ?>
</div>

<div class="list">

	<div class="buttonContainer">
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaShow.addTable()">
				<com:Icon name="add" size="16" />
				<span> echo Yii::t('database', 'addTable'); ?></span>
			</a>
		</div>
	</div>
	
	<div class="clear"></div>

	<table class="list addCheckboxes" id="tables">
		<colgroup>
			<col class="checkbox" />
			<col />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="count" />
			<col class="engine" />
			<col class="collation" />
			<col class="filesize" />
			<col class="filesize" />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="8"> echo $sort->link('TABLE_NAME', Yii::t('database', 'table')); ?></th>
				<th> echo $sort->link('TABLE_ROWS', Yii::t('database', 'rows')); ?></th>
				<th> echo $sort->link('ENGINE', Yii::t('database', 'engine')); ?></th>
				<th> echo $sort->link('TABLE_COLLATION', Yii::t('database', 'collation')); ?></th>
				<th> echo $sort->link('DATA_LENGTH', Yii::t('core', 'size')); ?></th>
				<th> echo $sort->link('DATA_FREE', Yii::t('database', 'overhead')); ?></th>
			</tr>
		</thead>
		<tbody>
			 $totalRowCount = $totalDataLength = $totalDataFree = 0;?>
			 $canDrop = $canTruncate = false; ?>
			 if(count($schema->tables) < 1) { ?>
				<tr>
					<td class="noEntries" colspan="14">
						 echo Yii::t('database', 'noTables'); ?>
					</td>
				</tr>
			 } ?>
			 foreach($schema->tables AS $table) { ?>
				<tr id="tables_ echo $table->TABLE_NAME; ?>">
					<td>
						<input type="checkbox" name="tables[]" value=" echo $table->TABLE_NAME; ?>" />
					</td>
					<td>
						<a href="#tables/ echo $table->TABLE_NAME; ?>/structure">
							 echo $table->TABLE_NAME; ?>
						</a>
					</td>
					<td>
						<a href="#tables/ echo $table->TABLE_NAME; ?>/browse" class="icon">
							<com:Icon name="browse" size="16" text="database.browse" />
						</a>
					</td>
					<td>
						<a href="#tables/ echo $table->TABLE_NAME; ?>/structure" class="icon">
							<com:Icon name="structure" size="16" text="database.structure" />
						</a>
					</td>
					<td>
						<a href="#tables/ echo $table->TABLE_NAME; ?>/search" class="icon">
							<com:Icon name="search" size="16" text="core.search" />
						</a>
					</td>
					<td>
						<a href="#tables/ echo $table->TABLE_NAME; ?>/insert" class="icon">
							<com:Icon name="insert" size="16" text="database.insert" />
						</a>
					</td>
					<td>
						 if(Yii::app()->user->privileges->checkTable($table->TABLE_SCHEMA, $table->TABLE_NAME, 'ALTER')) { ?>
							<a href="javascript:void(0);" onclick="schemaShow.editTable($(this).closest('tr').attr('id').substr(7))" class="icon">
								<com:Icon name="edit" size="16" text="core.edit" />
							</a>
						 } else { ?>
							<com:Icon name="edit" size="16" text="core.edit" disabled="true" />
						 } ?>
					</td>
					<td>
						 if(Yii::app()->user->privileges->checkTable($table->TABLE_SCHEMA, $table->TABLE_NAME, 'DELETE')) { ?>
							<a href="javascript:void(0);" onclick="schemaShow.truncateTable($(this).closest('tr').attr('id').substr(7))" class="icon">
								<com:Icon name="truncate" size="16" text="database.truncate" />
							</a>
							 $canTruncate = true; ?>
						 } else { ?>
							<com:Icon name="truncate" size="16" text="database.truncate" disabled="true" />
						 } ?>
					</td>
					<td>
						 if(Yii::app()->user->privileges->checkTable($table->TABLE_SCHEMA, $table->TABLE_NAME, 'DROP')) { ?>
							<a href="javascript:void(0);" onclick="schemaShow.dropTable($(this).closest('tr').attr('id').substr(7))" class="icon">
								<com:Icon name="delete" size="16" text="database.drop" />
							</a>
							 $canDrop = true; ?>
						 } else { ?>
							<com:Icon name="delete" size="16" text="database.drop" disabled="true" />
						 } ?>
					</td>
					<td>
						 echo $table->getRowCount(); ?>
					</td>
					<td>
						 echo $table->ENGINE; ?>
					</td>
					<td>
						<dfn title=" echo Collation::getDefinition($table->TABLE_COLLATION); ?>"> echo $table->TABLE_COLLATION; ?></dfn>
					</td>
					<td style="text-align: right">
						 echo Formatter::fileSize($table->DATA_LENGTH + $table->INDEX_LENGTH); ?>
					</td>
					<td style="text-align: right">
						 echo Formatter::fileSize($table->DATA_FREE); ?>
					</td>
				</tr>
			 $totalRowCount += $table->getRowCount(); ?>
			 $totalDataLength += $table->DATA_LENGTH; ?>
			 $totalDataFree += $table->DATA_FREE; ?>
			 } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="8"> echo Yii::t('database', 'amountTables', array($schema->tableCount, '{amount} '=> $schema->tableCount)); ?></th>
				<th> echo $totalRowCount; ?></th>
				<th></th>
				<th></th>
				<th style="text-align: right"> echo Formatter::fileSize($totalDataLength); ?></th>
				<th style="text-align: right"> echo Formatter::fileSize($totalDataFree); ?></th>
			</tr>
		</tfoot>
	</table>

	<div class="buttonContainer">
		<div class="left">
			<span class="icon">
				<com:Icon name="arrow_turn_090" size="16" />
				<span> echo Yii::t('core', 'withSelected'); ?></span>
			</span>
			 if($canDrop) { ?>
				<a href="javascript:void(0)" onclick="schemaShow.dropTables()" class="icon button">
					<com:Icon name="delete" size="16" />
					<span> echo Yii::t('database', 'drop'); ?></span>
				</a>
			 } else { ?>
				<span class="icon button">
					<com:Icon name="delete" size="16" disabled="true" />
					<span> echo Yii::t('database', 'drop'); ?></span>
				</span>
			 } ?>
			 if($canTruncate) { ?>
				<a href="javascript:void(0)" onclick="schemaShow.truncateTables()" class="icon button">
					<com:Icon name="truncate" size="16" />
					<span> echo Yii::t('database', 'truncate'); ?></span>
				</a>
			 } else { ?>
				<span class="icon button">
					<com:Icon name="truncate" size="16" disabled="true" />
					<span> echo Yii::t('database', 'truncate'); ?></span>
				</span>
			 } ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaShow.addTable()">
				<com:Icon name="add" size="16" />
				<span> echo Yii::t('database', 'addTable'); ?></span>
			</a>
		</div>
	</div>
</div>

<script type="text/javascript">
	schemaShow.setup();
</script>