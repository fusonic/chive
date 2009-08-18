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
<div id="dropColumnsDialog" title=" echo Yii::t('database', 'dropColumns'); ?>" style="display: none">
	 echo Yii::t('database', 'doYouReallyWantToDropColumns'); ?>
	<ul></ul>
</div>
<div id="addIndexDialog" title=" echo Yii::t('database', 'addIndex'); ?>" style="display: none">
	<div> echo Yii::t('database', 'enterNameForNewIndex'); ?></div>
	<input type="text" id="newIndexName" name="newIndexName" />
</div>
<div id="dropIndexDialog" title=" echo Yii::t('database', 'dropIndex'); ?>" style="display: none">
	 echo Yii::t('database', 'doYouReallyWantToDropIndex');?>
</div>
<div id="dropTriggerDialog" title=" echo Yii::t('database', 'dropTrigger'); ?>" style="display: none">
	 echo Yii::t('database', 'doYouReallyWantToDropTrigger'); ?>
	<ul></ul>
</div>

<div class="list">

	<table id="columns" class="list addCheckboxes selectable">
		<colgroup>
			<col class="checkbox" />
			<col />
			<col class="type" />
			<col class="collation" />
			<col class="null" />
			<col />
			<col />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			 if(is_array($foreignKeys)) { ?>
				<col class="action" />
			 } ?>
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" /></th>
				<th> echo Yii::t('database','field'); ?></th>
				<th> echo Yii::t('database','type'); ?></th>
				<th> echo Yii::t('database','collation'); ?></th>
				<th> echo Yii::t('database','null'); ?></th>
				<th> echo Yii::t('database','default'); ?></th>
				<th colspan=" if(is_array($foreignKeys)) { ?>10 } else { ?>9 } ?>"> echo Yii::t('database','extra'); ?></th>
			</tr>
		</thead>
		<tbody>
			 if(count($table->columns) < 1) { ?>
				<tr>
					<td class="noEntries" colspan="15">
						 echo Yii::t('database', 'noColumns'); ?>
					</td>
				</tr>
			 } ?>
			 foreach($table->columns AS $column) { ?>
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
					<td>
						<span class="icon">
							<com:Icon name="arrow_move" size="16" text="core.move" htmlOptions={array('style'=>'cursor: pointer;')} />
						</span>
					</td>
					<td>
						 if($canAlter) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.editColumn($(this).closest('tr').attr('id').substr(8))" class="icon">
								<com:Icon name="edit" size="16" text="core.edit" />
							</a>
						 } else { ?>
							<span class="icon">
								<com:Icon name="edit" size="16" text="core.edit" disabled="true"/>
							</span>
						 }?>
					</td>
					<td>
						 if($canAlter) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.dropColumn($(this).closest('tr').attr('id').substr(8))" class="icon">
								<com:Icon name="delete" size="16" text="database.drop" />
							</a>
						 } else { ?>
							<span class="icon">
								<com:Icon name="delete" size="16" text="database.drop" disabled="true" />
							</span>
						 } ?>
					</td>
					<td>
						 if($canAlter && !$table->getHasPrimaryKey()) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('primary', $(this).closest('tr').attr('id').substr(8))" class="icon">
								<com:Icon name="key_primary" size="16" text="database.primaryKey" />
							</a>
						 } else { ?>
							<span class="icon">
								<com:Icon disabled="true" name="key_primary" size="16" text="database.primaryKey" />
							</span>
						 } ?>
					</td>
					<td>
						 if($canAlter && DataType::check($column->DATA_TYPE, DataType::SUPPORTS_INDEX)) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('index', $(this).closest('tr').attr('id').substr(8))" class="icon">
								<com:Icon name="key_index" size="16" text="database.index" />
							</a>
						 } else { ?>
							<span class="icon">
								<com:Icon name="key_index" size="16" text="database.index" disabled="true" />
							</span>
						 }?>
					</td>
					<td>
						 if($canAlter && DataType::check($column->DATA_TYPE, DataType::SUPPORTS_UNIQUE)) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('unique', $(this).closest('tr').attr('id').substr(8))" class="icon">
								<com:Icon name="key_unique" size="16" text="database.uniqueKey" />
							</a>
						 } else { ?>
							<span class="icon">
								<com:Icon name="key_unique" size="16" text="database.uniqueKey" disabled="true" />
							</span>
						 }?>
					</td>
					<td>
						 if($canAlter && DataType::check($column->DATA_TYPE, DataType::SUPPORTS_FULLTEXT)) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('fulltext', $(this).closest('tr').attr('id').substr(8))" class="icon">
								<com:Icon name="key_fulltext" size="16" text="database.fulltextIndex" />
							</a>
						 } else { ?>
							<span class="icon">
								<com:Icon name="key_fulltext" size="16" text="database.fulltextIndex" disabled="true" />
							</span>
						 }?>
					</td>
					 if(is_array($foreignKeys)) { ?>
						<td>
							<a href="javascript:void(0)" onclick="tableStructure.editRelation($(this).closest('tr').attr('id').substr(8))" class="icon">
								 if(in_array($column->COLUMN_NAME, $foreignKeys)) { ?>
									<com:Icon name="relation" size="16" text="database.relation" />
								 } else { ?>
									<com:Icon name="relation" size="16" text="database.relation" disabled="true" />
								 } ?>
							</a>
						</td>
					 } ?>
				</tr>
			 } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan=" if(is_array($foreignKeys)) { ?>15 } else { ?>14 } ?>"> echo Yii::t('database', 'XColumns', array('{count}' => count($table->columns))); ?></th>
			</tr>
		</tfoot>
	</table>

	<div class="buttonContainer">

		<div class="left withSelected">
			<span class="icon">
				<com:Icon name="arrow_turn_090" size="16" />
				<span> echo Yii::t('core', 'withSelected'); ?></span>
			</span>
			 if($canAlter) { ?>
				<a href="javascript:void(0)" onclick="tableStructure.dropColumns()" class="icon button">
					<com:Icon name="delete" size="16" />
					<span> echo Yii::t('database', 'drop'); ?></span>
				</a>
				 if(!$table->getHasPrimaryKey()) { ?>
					<a href="javascript:void(0)" onclick="tableStructure.addIndex('primary')" class="icon button">
						<com:Icon name="key_primary" size="16" text="database.primaryKey" />
						<span> echo Yii::t('database', 'primaryKey'); ?></span>
					</a>
				 } ?>
				<a href="javascript:void(0)" onclick="tableStructure.addIndex('index')" class="icon button">
					<com:Icon name="key_index" size="16" text="database.index" />
					<span> echo Yii::t('database', 'index'); ?></span>
				</a>
				<a href="javascript:void(0)" onclick="tableStructure.addIndex('unique')" class="icon button">
					<com:Icon name="key_unique" size="16" text="database.uniqueKey" />
					<span> echo Yii::t('database', 'uniqueKey'); ?></span>
				</a>
				<a href="javascript:void(0)" onclick="tableStructure.addIndex('fulltext')" class="icon button">
					<com:Icon name="key_fulltext" size="16" text="database.fulltextIndex" />
					<span> echo Yii::t('database', 'fulltextIndex'); ?></span>
				</a>
			 } else { ?>
				<span class="icon button">
					<com:Icon name="delete" size="16" disabled="true" />
					<span> echo Yii::t('database', 'drop'); ?></span>
				</span>
				 if(!$table->getHasPrimaryKey()) { ?>
					<span class="icon button">
						<com:Icon name="key_primary" size="16" text="database.primaryKey" disabled="true" />
						<span> echo Yii::t('database', 'primaryKey'); ?></span>
					</span>
				 } ?>
				<span class="icon button">
					<com:Icon name="key_index" size="16" text="database.index" disabled="true" />
					<span> echo Yii::t('database', 'index'); ?></span>
				</span>
				<span class="icon button">
					<com:Icon name="key_unique" size="16" text="database.uniqueKey" disabled="true" />
					<span> echo Yii::t('database', 'uniqueKey'); ?></span>
				</span>
				<span class="icon button">
					<com:Icon name="key_fulltext" size="16" text="database.fulltextIndex" disabled="true" />
					<span> echo Yii::t('database', 'fulltextIndex'); ?></span>
				</span>
			 } ?>
		</div>

		<div class="right">
			 if($canAlter) { ?>
				<a href="javascript:void(0)" onclick="tableStructure.addColumn()" class="icon button">
					<com:Icon name="add" size="16" />
					<span> echo Yii::t('database', 'addColumn'); ?></span>
				</a>
			 } else { ?>
				<span class="icon button">
					<com:Icon name="add" size="16" disabled="true" />
					<span> echo Yii::t('database', 'addColumn'); ?></span>
				</span>
			 } ?>
		</div>
	</div>

</div>

<div style="overflow: hidden; clear: both">

	<div style="width: 45%; float: left">
		<div style="padding-right: 10px">

			<div class="list">

				<table id="indices" class="list">
					<colgroup>
						<col />
						<col />
						<col />
						<col />
						<col class="action" />
						<col class="action" />
					</colgroup>
					<thead>
						<tr>
							<th> echo Yii::t('database', 'index'); ?></th>
							<th> echo Yii::t('database', 'type'); ?></th>
							<th> echo Yii::t('database', 'cardinality'); ?></th>
							<th colspan="3"> echo Yii::t('database', 'field'); ?></th>
						</tr>
					</thead>
					<tbody>
						 if(count($table->indices) < 1) { ?>
							<tr>
								<td class="noEntries" colspan="6">
									 echo Yii::t('database', 'noIndices'); ?>
								</td>
							</tr>
						 } ?>
						 foreach($table->indices AS $index) { ?>
							<tr id="indices_ echo $index->INDEX_NAME; ?>">
								<td> echo $index->INDEX_NAME; ?></td>
								<td>
									 echo $index->getType(); ?>
								</td>
								<td>
									 echo $index->CARDINALITY; ?>
								</td>
								<td>
									<ul>
										 foreach($index->columns AS $column) { ?>
											<li id="indices_ echo $index->INDEX_NAME; ?>_ echo $column->COLUMN_NAME; ?>">
												 echo $column->COLUMN_NAME; ?>
												 if(!is_null($column->SUB_PART)) { ?>
													( echo $column->SUB_PART; ?>)
												 } ?>
											</li>
										 } ?>
									</ul>
								</td>
								<td>
									 if($canAlter) { ?>
										<a href="javascript:void(0)" onclick="tableStructure.editIndex(' echo $index->INDEX_NAME; ?>')" class="icon">
											<com:Icon name="edit" size="16" text="core.edit" />
										</a>
									 } else { ?>
										<span class="icon">
											<com:Icon name="edit" size="16" text="core.edit" disabled="true" />
										</span>
									 } ?>
								</td>
								<td>
									 if($canAlter) { ?>
										<a href="javascript:void(0)" onclick="tableStructure.dropIndex(' echo $index->INDEX_NAME; ?>')" class="icon">
											<com:Icon name="delete" size="16" text="database.drop" />
										</a>
									 } else { ?>
										<span class="icon">
											<com:Icon name="delete" size="16" text="database.drop" disabled="true" />
										</span>
									 } ?>
								</td>
							</tr>
						 } ?>
					</tbody>
				</table>

				<div class="buttonContainer">
					<div class="right">
						 if($canAlter) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndexForm()" class="icon button">
								<com:Icon name="add" size="16" />
								<span> echo Yii::t('database', 'addIndex'); ?></span>
							</a>
						 } else { ?>
							<span class="icon button">
								<com:Icon name="add" size="16" disabled="disabled" />
								<span> echo Yii::t('database', 'addIndex'); ?></span>
							</span>
						 } ?>
					</div>
				</div>

			</div>

			<div class="list">

				<table id="triggers" class="list">
					<colgroup>
						<col />
						<col />
						<col class="action" />
						<col class="action" />
					</colgroup>
					<thead>
						<tr>
							<th> echo Yii::t('database', 'trigger'); ?></th>
							<th colspan="3"> echo Yii::t('core', 'event'); ?></th>
						</tr>
					</thead>
					<tbody>
						 if(count($table->triggers) < 1) { ?>
							<tr>
								<td class="noEntries" colspan="4">
									 echo Yii::t('database', 'noTriggers'); ?>
								</td>
							</tr>
						 } ?>
						 foreach($table->triggers AS $trigger) { ?>
							<tr id="triggers_ echo $trigger->TRIGGER_NAME; ?>">
								<td> echo $trigger->TRIGGER_NAME; ?></td>
								<td>
									 echo $trigger->ACTION_TIMING . ' ' . $trigger->EVENT_MANIPULATION; ?>
								</td>
								<td>
									<a href="javascript:void(0)" onclick="tableStructure.editTrigger(' echo $trigger->TRIGGER_NAME; ?>')" class="icon">
										<com:Icon name="edit" size="16" text="core.edit" />
									</a>
								</td>
								<td>
									<a href="javascript:void(0)" onclick="tableStructure.dropTrigger(' echo $trigger->TRIGGER_NAME; ?>')" class="icon">
										<com:Icon name="delete" size="16" text="database.drop" />
									</a>
								</td>
							</tr>
						 } ?>
					</tbody>
				</table>

				<div class="buttonContainer">
					<div class="right">
						<a href="javascript:void(0)" onclick="tableStructure.addTrigger()" class="icon button">
							<com:Icon name="add" size="16" />
							<span> echo Yii::t('database', 'addTrigger'); ?></span>
						</a>
					</div>
				</div>

			</div>

		</div>
	</div>

	<div style="width: 25%; float: left">
		<div style="padding: 0px 10px">

			<table class="list">
				<colgroup>
					<col />
					<col />
				</colgroup>
				<thead>
					<tr>
						<th colspan="2"> echo Yii::t('database', 'spaceUsage'); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td> echo Yii::t('database', 'data'); ?></td>
						<td class="right"> echo Formatter::fileSize($table->DATA_LENGTH); ?></td>
					</tr>
					<tr>
						<td> echo Yii::t('database', 'index'); ?></td>
						<td class="right"> echo Formatter::fileSize($table->INDEX_LENGTH); ?></td>
					</tr>
					<tr>
						<td> echo Yii::t('core', 'total'); ?></td>
						<td class="right"> echo Formatter::fileSize($table->INDEX_LENGTH + $table->DATA_LENGTH); ?></td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>

	<div style="width: 30%; float: right">
		<div style="padding-left: 10px">

			<table class="list">
				<colgroup>
					<col />
					<col />
				</colgroup>
				<thead>
					<tr>
						<th colspan="2">
							 echo Yii::t('core', 'information'); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td> echo Yii::t('database', 'storageEngine'); ?></td>
						<td> echo $table->ENGINE; ?></td>
					</tr>
					<tr>
						<td> echo Yii::t('database', 'format'); ?></td>
						<td> echo $table->ROW_FORMAT; ?></td>
					</tr>
					<tr>
						<td> echo Yii::t('database', 'collation'); ?></td>
						<td>
							<dfn class="collation" title=" echo Collation::getDefinition($table->TABLE_COLLATION); ?>">
								 echo $table->TABLE_COLLATION; ?>
							</dfn>
						</td>
					</tr>
					<tr>
						<td> echo Yii::t('database', 'rows'); ?></td>
						<td> echo $table->getRowCount(); ?></td>
					</tr>
					<tr>
						<td> echo Yii::t('database', 'averageRowLength'); ?></td>
						<td> echo $table->AVG_ROW_LENGTH; ?></td>
					</tr>
					<tr>
						<td> echo Yii::t('database', 'averageRowSize'); ?></td>
						<td> echo Formatter::fileSize($table->getAverageRowSize()); ?></td>
					</tr>
					 if ($table->AUTO_INCREMENT) { ?>
						<tr>
							<td> echo Yii::t('database', 'nextAutoincrementValue'); ?></td>
							<td> echo $table->AUTO_INCREMENT; ?></td>
						</tr>
					 } ?>
					<tr>
						<td> echo Yii::t('core', 'creationDate'); ?></td>
						<td> echo ($table->CREATE_TIME ? Yii::app()->getDateFormatter()->formatDateTime($table->CREATE_TIME, 'short', 'short') : '-'); ?></td>
					</tr>
					<tr>
						<td> echo Yii::t('core', 'lastUpdateDate'); ?></td>
						<td> echo ($table->UPDATE_TIME ? Yii::app()->getDateFormatter()->formatDateTime($table->UPDATE_TIME, 'short', 'short') : '-'); ?></td>
					</tr>
					<tr>
						<td> echo Yii::t('core', 'lastCheckDate'); ?></td>
						<td>
							 echo ($table->CHECK_TIME ? Yii::app()->getDateFormatter()->formatDateTime($table->CHECK_TIME, 'short', 'short') : '-'); ?>
						</td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>

</div>

<script type="text/javascript">
setTimeout(function() {
	tableStructure.setupDialogs();
	tableStructure.setupSortable();
}, 500);
</script>