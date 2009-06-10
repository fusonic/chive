<div id="dropColumnsDialog" title="<?php echo Yii::t('database', 'dropColumns'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToDropColumns'); ?>
</div>
<div id="addIndexDialog" title="<?php echo Yii::t('database', 'addIndex'); ?>" style="display: none">
	<div><?php echo Yii::t('database', 'enterNameForNewIndex'); ?></div>
	<input type="text" id="newIndexName" name="newIndexName" />
</div>
<div id="dropIndexDialog" title="<?php echo Yii::t('database', 'dropIndex'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToDropIndex'); ?>
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
			<?php if(is_array($foreignKeys)) { ?>
				<col class="action" />
			<?php } ?>
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" /></th>
				<th><?php echo Yii::t('database','field'); ?></th>
				<th><?php echo Yii::t('database','type'); ?></th>
				<th><?php echo Yii::t('database','collation'); ?></th>
				<th><?php echo Yii::t('database','null'); ?></th>
				<th><?php echo Yii::t('database','default'); ?></th>
				<th colspan="<?php if(is_array($foreignKeys)) { ?>10<?php } else { ?>9<?php } ?>"><?php echo Yii::t('database','extra'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(count($table->columns) < 1) { ?>
				<tr>
					<td class="noEntries" colspan="15">
						<?php echo Yii::t('database', 'noColumns'); ?>
					</td>
				</tr>
			<?php } ?>
			<?php foreach($table->columns AS $column) { ?>
				<tr id="columns_<?php echo $column->COLUMN_NAME; ?>">
					<td>
						<input type="checkbox" name="columns[]" value="<?php echo $column->COLUMN_NAME; ?>" />
					</td>
					<td>
						<?php if($column->getIsPartOfPrimaryKey($table->indices)): ?>
							<span class="primaryKey"><?php echo $column->COLUMN_NAME; ?></span>
						<?php else: ?>
							<?php echo $column->COLUMN_NAME; ?>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $column->COLUMN_TYPE; ?>
					</td>
					<td>
						<?php if(!is_null($column->COLLATION_NAME)) { ?>
							<dfn class="collation" title="<?php echo Collation::getDefinition($column->COLLATION_NAME); ?>">
								<?php echo $column->COLLATION_NAME; ?>
							</dfn>
						<?php } ?>
					</td>
					<td>
						<?php echo Yii::t('core', strtolower($column->IS_NULLABLE)); ?>
					</td>
					<td>
						<?php if(is_null($column->COLUMN_DEFAULT) && $column->IS_NULLABLE == 'YES') { ?>
							<span class="null">NULL</span>
						<?php } else { ?>
							<?php echo $column->COLUMN_DEFAULT; ?>
						<?php } ?>
					</td>
					<td><?php echo $column->EXTRA; ?></td>
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
						<?php if($canAlter) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.editColumn($(this).closest('tr').attr('id').substr(8))" class="icon">
								<com:Icon name="edit" size="16" text="core.edit" />
							</a>
						<?php } else { ?>
							<span class="icon">
								<com:Icon name="edit" size="16" text="core.edit" disabled="true"/>
							</span>
						<?php }?>
					</td>
					<td>
						<?php if($canAlter) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.dropColumn($(this).closest('tr').attr('id').substr(8))" class="icon">
								<com:Icon name="delete" size="16" text="database.drop" />
							</a>
						<?php } else { ?>
							<span class="icon">
								<com:Icon name="delete" size="16" text="database.drop" disabled="true" />
							</span>
						<?php } ?>
					</td>
					<td>
						<?php if($canAlter && !$table->getHasPrimaryKey()) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('primary', $(this).closest('tr').attr('id').substr(8))" class="icon">
								<com:Icon name="key_primary" size="16" text="database.primaryKey" />
							</a>
						<?php } else { ?>
							<span class="icon">
								<com:Icon disabled="true" name="key_primary" size="16" text="database.primaryKey" />
							</span>
						<?php } ?>
					</td>
					<td>
						<?php if($canAlter && DataType::check($column->DATA_TYPE, DataType::SUPPORTS_INDEX)) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('index', $(this).closest('tr').attr('id').substr(8))" class="icon">
								<com:Icon name="key_index" size="16" text="database.index" />
							</a>
						<?php } else { ?>
							<span class="icon">
								<com:Icon name="key_index" size="16" text="database.index" disabled="true" />
							</span>
						<?php }?>
					</td>
					<td>
						<?php if($canAlter && DataType::check($column->DATA_TYPE, DataType::SUPPORTS_UNIQUE)) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('unique', $(this).closest('tr').attr('id').substr(8))" class="icon">
								<com:Icon name="key_unique" size="16" text="database.uniqueKey" />
							</a>
						<?php } else { ?>
							<span class="icon">
								<com:Icon name="key_unique" size="16" text="database.uniqueKey" disabled="true" />
							</span>
						<?php }?>
					</td>
					<td>
						<?php if($canAlter && DataType::check($column->DATA_TYPE, DataType::SUPPORTS_FULLTEXT)) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('fulltext', $(this).closest('tr').attr('id').substr(8))" class="icon">
								<com:Icon name="key_fulltext" size="16" text="database.fulltextIndex" />
							</a>
						<?php } else { ?>
							<span class="icon">
								<com:Icon name="key_fulltext" size="16" text="database.fulltextIndex" disabled="true" />
							</span>
						<?php }?>
					</td>
					<?php if(is_array($foreignKeys)) { ?>
						<td>
							<a href="javascript:void(0)" onclick="tableStructure.editRelation($(this).closest('tr').attr('id').substr(8))" class="icon">
								<?php if(in_array($column->COLUMN_NAME, $foreignKeys)) { ?>
									<com:Icon name="relation" size="16" text="database.relation" />
								<?php } else { ?>
									<com:Icon name="relation" size="16" text="database.relation" disabled="true" />
								<?php } ?>
							</a>
						</td>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="<?php if(is_array($foreignKeys)) { ?>15<?php } else { ?>14<?php } ?>"><?php echo Yii::t('database', 'XColumns', array('{count}' => count($table->columns))); ?></th>
			</tr>
		</tfoot>
	</table>

	<div class="buttonContainer">

		<div class="left withSelected">
			<span class="icon">
				<com:Icon name="arrow_turn_090" size="16" />
				<span><?php echo Yii::t('core', 'withSelected'); ?></span>
			</span>
			<?php if($canAlter) { ?>
				<a href="javascript:void(0)" onclick="tableStructure.dropColumns()" class="icon button">
					<com:Icon name="delete" size="16" />
					<span><?php echo Yii::t('database', 'drop'); ?></span>
				</a>
				<?php if(!$table->getHasPrimaryKey()) { ?>
					<a href="javascript:void(0)" onclick="tableStructure.addIndex('primary')" class="icon button">
						<com:Icon name="key_primary" size="16" text="database.primaryKey" />
						<span><?php echo Yii::t('database', 'primaryKey'); ?></span>
					</a>
				<?php } ?>
				<a href="javascript:void(0)" onclick="tableStructure.addIndex('index')" class="icon button">
					<com:Icon name="key_index" size="16" text="database.index" />
					<span><?php echo Yii::t('database', 'index'); ?></span>
				</a>
				<a href="javascript:void(0)" onclick="tableStructure.addIndex('unique')" class="icon button">
					<com:Icon name="key_unique" size="16" text="database.uniqueKey" />
					<span><?php echo Yii::t('database', 'uniqueKey'); ?></span>
				</a>
				<a href="javascript:void(0)" onclick="tableStructure.addIndex('fulltext')" class="icon button">
					<com:Icon name="key_fulltext" size="16" text="database.fulltextIndex" />
					<span><?php echo Yii::t('database', 'fulltextIndex'); ?></span>
				</a>
			<?php } else { ?>
				<span class="icon button">
					<com:Icon name="delete" size="16" disabled="true" />
					<span><?php echo Yii::t('database', 'drop'); ?></span>
				</span>
				<?php if(!$table->getHasPrimaryKey()) { ?>
					<span class="icon button">
						<com:Icon name="key_primary" size="16" text="database.primaryKey" disabled="true" />
						<span><?php echo Yii::t('database', 'primaryKey'); ?></span>
					</span>
				<?php } ?>
				<span class="icon button">
					<com:Icon name="key_index" size="16" text="database.index" disabled="true" />
					<span><?php echo Yii::t('database', 'index'); ?></span>
				</span>
				<span class="icon button">
					<com:Icon name="key_unique" size="16" text="database.uniqueKey" disabled="true" />
					<span><?php echo Yii::t('database', 'uniqueKey'); ?></span>
				</span>
				<span class="icon button">
					<com:Icon name="key_fulltext" size="16" text="database.fulltextIndex" disabled="true" />
					<span><?php echo Yii::t('database', 'fulltextIndex'); ?></span>
				</span>
			<?php } ?>
		</div>

		<div class="right">
			<?php if($canAlter) { ?>
				<a href="javascript:void(0)" onclick="tableStructure.addColumn()" class="icon button">
					<com:Icon name="add" size="16" />
					<span><?php echo Yii::t('database', 'addColumn'); ?></span>
				</a>
			<?php } else { ?>
				<span class="icon button">
					<com:Icon name="add" size="16" disabled="true" />
					<span><?php echo Yii::t('database', 'addColumn'); ?></span>
				</span>
			<?php } ?>
		</div>
	</div>

</div>

<div style="overflow: hidden; clear: both; padding-top: 10px">

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
							<th><?php echo Yii::t('database', 'index'); ?></th>
							<th><?php echo Yii::t('database', 'type'); ?></th>
							<th><?php echo Yii::t('database', 'cardinality'); ?></th>
							<th colspan="3"><?php echo Yii::t('database', 'field'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if(count($table->indices) < 1) { ?>
							<tr>
								<td class="noEntries" colspan="6">
									<?php echo Yii::t('database', 'noIndices'); ?>
								</td>
							</tr>
						<?php } ?>
						<?php foreach($table->indices AS $index) { ?>
							<tr id="indices_<?php echo $index->INDEX_NAME; ?>">
								<td><?php echo $index->INDEX_NAME; ?></td>
								<td>
									<?php echo $index->getType(); ?>
								</td>
								<td>
									<?php echo $index->CARDINALITY; ?>
								</td>
								<td>
									<ul>
										<?php foreach($index->columns AS $column) { ?>
											<li id="indices_<?php echo $index->INDEX_NAME; ?>_<?php echo $column->COLUMN_NAME; ?>">
												<?php echo $column->COLUMN_NAME; ?>
												<?php if(!is_null($column->SUB_PART)) { ?>
													(<?php echo $column->SUB_PART; ?>)
												<?php } ?>
											</li>
										<?php } ?>
									</ul>
								</td>
								<td>
									<?php if($canAlter) { ?>
										<a href="javascript:void(0)" onclick="tableStructure.editIndex('<?php echo $index->INDEX_NAME; ?>')" class="icon">
											<com:Icon name="edit" size="16" text="core.edit" />
										</a>
									<?php } else { ?>
										<span class="icon">
											<com:Icon name="edit" size="16" text="core.edit" disabled="true" />
										</span>
									<?php } ?>
								</td>
								<td>
									<?php if($canAlter) { ?>
										<a href="javascript:void(0)" onclick="tableStructure.dropIndex('<?php echo $index->INDEX_NAME; ?>')" class="icon">
											<com:Icon name="delete" size="16" text="database.drop" />
										</a>
									<?php } else { ?>
										<span class="icon">
											<com:Icon name="delete" size="16" text="database.drop" disabled="true" />
										</span>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>

				<div class="buttonContainer">
					<div class="right">
						<?php if($canAlter) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndexForm()" class="icon button">
								<com:Icon name="add" size="16" />
								<span><?php echo Yii::t('database', 'addIndex'); ?></span>
							</a>
						<?php } else { ?>
							<span class="icon button">
								<com:Icon name="add" size="16" disabled="disabled" />
								<span><?php echo Yii::t('database', 'addIndex'); ?></span>
							</span>
						<?php } ?>
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
						<th colspan="2"><?php echo Yii::t('database', 'spaceUsage'); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo Yii::t('database', 'data'); ?></td>
						<td class="right"><?php echo Formatter::fileSize($table->DATA_LENGTH); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('database', 'index'); ?></td>
						<td class="right"><?php echo Formatter::fileSize($table->INDEX_LENGTH); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('core', 'total'); ?></td>
						<td class="right"><?php echo Formatter::fileSize($table->INDEX_LENGTH + $table->DATA_LENGTH); ?></td>
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
							<?php echo Yii::t('core', 'information'); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo Yii::t('database', 'storageEngine'); ?></td>
						<td><?php echo $table->ENGINE; ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('database', 'format'); ?></td>
						<td><?php echo $table->ROW_FORMAT; ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('database', 'collation'); ?></td>
						<td>
							<dfn class="collation" title="<?php echo Collation::getDefinition($table->TABLE_COLLATION); ?>">
								<?php echo $table->TABLE_COLLATION; ?>
							</dfn>
						</td>
					</tr>
					<tr>
						<td><?php echo Yii::t('database', 'rows'); ?></td>
						<td><?php echo $table->getRowCount(); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('database', 'averageRowLength'); ?></td>
						<td><?php echo $table->AVG_ROW_LENGTH; ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('database', 'averageRowSize'); ?></td>
						<td><?php echo Formatter::fileSize($table->getAverageRowSize()); ?></td>
					</tr>
					<?php if ($table->AUTO_INCREMENT) { ?>
						<tr>
							<td><?php echo Yii::t('database', 'nextAutoincrementValue'); ?></td>
							<td><?php echo $table->AUTO_INCREMENT; ?></td>
						</tr>
					<?php } ?>
					<tr>
						<td><?php echo Yii::t('core', 'creationDate'); ?></td>
						<td><?php echo ($table->CREATE_TIME ? Yii::app()->getDateFormatter()->formatDateTime($table->CREATE_TIME, 'short', 'short') : '-'); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('core', 'lastUpdateDate'); ?></td>
						<td><?php echo ($table->UPDATE_TIME ? Yii::app()->getDateFormatter()->formatDateTime($table->UPDATE_TIME, 'short', 'short') : '-'); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('core', 'lastCheckDate'); ?></td>
						<td>
							<?php echo ($table->CHECK_TIME ? Yii::app()->getDateFormatter()->formatDateTime($table->CHECK_TIME, 'short', 'short') : '-'); ?>
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