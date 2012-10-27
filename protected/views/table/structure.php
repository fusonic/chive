<div id="dropColumnsDialog" title="<?php echo Yii::t('core', 'dropColumns'); ?>" style="display: none">
	<?php echo Yii::t('core', 'doYouReallyWantToDropColumns'); ?>
	<ul></ul>
</div>
<div id="addIndexDialog" title="<?php echo Yii::t('core', 'addIndex'); ?>" style="display: none">
	<div><?php echo Yii::t('core', 'enterNameForNewIndex'); ?></div>
	<input type="text" id="newIndexName" name="newIndexName" />
</div>
<div id="dropIndexDialog" title="<?php echo Yii::t('core', 'dropIndex'); ?>" style="display: none">
	<?php echo Yii::t('core', 'doYouReallyWantToDropIndex');?>
</div>
<div id="dropTriggerDialog" title="<?php echo Yii::t('core', 'dropTrigger'); ?>" style="display: none">
	<?php echo Yii::t('core', 'doYouReallyWantToDropTrigger'); ?>
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
			<?php if(is_array($foreignKeys)) { ?>
				<col class="action" />
			<?php } ?>
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" /></th>
				<th><?php echo Yii::t('core','field'); ?></th>
				<th><?php echo Yii::t('core','type'); ?></th>
				<th><?php echo Yii::t('core','collation'); ?></th>
				<th><?php echo Yii::t('core','null'); ?></th>
				<th><?php echo Yii::t('core','default'); ?></th>
				<th colspan="<?php if(is_array($foreignKeys)) { ?>9<?php } else { ?>8<?php } ?>"><?php echo Yii::t('core','extra'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(count($table->columns) < 1) { ?>
				<tr>
					<td class="noEntries" colspan="15">
						<?php echo Yii::t('core', 'noColumns'); ?>
					</td>
				</tr>
			<?php } ?>
			<?php foreach($table->columns AS $column) { ?>
				<tr id="columns_<?php echo CHtml::encode($column->COLUMN_NAME); ?>">
					<td>
						<input type="checkbox" name="columns[]" value="<?php echo CHtml::encode($column->COLUMN_NAME); ?>" />
					</td>
					<td>
						<?php if($column->getIsPartOfPrimaryKey($indicesRaw)): ?>
							<span class="primaryKey"><?php echo CHtml::encode($column->COLUMN_NAME); ?></span>
						<?php else: ?>
							<?php echo CHtml::encode($column->COLUMN_NAME); ?>
						<?php endif; ?>
					</td>
					<td>
                        <?php echo strlen($column->COLUMN_TYPE) > 50 ? substr($column->COLUMN_TYPE, 0, 50) . "..." : $column->COLUMN_TYPE ?>
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
							<?php echo Html::icon('arrow_move', 16, false, 'core.move', array('style' => 'cursor: pointer')); ?>
						</span>
					</td>
					<td>
						<?php if($canAlter) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.editColumn($(this).closest('tr').attr('id').substr(8))" class="icon">
								<?php echo Html::icon('edit', 16, false, 'core.edit'); ?>
							</a>
						<?php } else { ?>
							<span class="icon">
								<?php echo Html::icon('edit', 16, true, 'core.edit'); ?>
							</span>
						<?php }?>
					</td>
					<td>
						<?php if($canAlter) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.dropColumn($(this).closest('tr').attr('id').substr(8))" class="icon">
								<?php echo Html::icon('delete', 16, false, 'core.drop'); ?>
							</a>
						<?php } else { ?>
							<span class="icon">
								<?php echo Html::icon('delete', 16, true, 'core.drop'); ?>
							</span>
						<?php } ?>
					</td>
					<td>
						<?php if($canAlter && !$table->getHasPrimaryKey()) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('primary', $(this).closest('tr').attr('id').substr(8))" class="icon">
								<?php echo Html::icon('key_primary', 16, false, 'core.primaryKey'); ?>
							</a>
						<?php } else { ?>
							<span class="icon">
								<?php echo Html::icon('key_primary', 16, true, 'core.primaryKey'); ?>
							</span>
						<?php } ?>
					</td>
					<td>
						<?php if($canAlter && DataType::check($column->DATA_TYPE, DataType::SUPPORTS_INDEX)) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('index', $(this).closest('tr').attr('id').substr(8))" class="icon">
								<?php echo Html::icon('key_index', 16, false, 'core.index'); ?>
							</a>
						<?php } else { ?>
							<span class="icon">
								<?php echo Html::icon('key_index', 16, true, 'core.index'); ?>
							</span>
						<?php }?>
					</td>
					<td>
						<?php if($canAlter && DataType::check($column->DATA_TYPE, DataType::SUPPORTS_UNIQUE)) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('unique', $(this).closest('tr').attr('id').substr(8))" class="icon">
								<?php echo Html::icon('key_unique', 16, false, 'core.uniqueKey'); ?>
							</a>
						<?php } else { ?>
							<span class="icon">
								<?php echo Html::icon('key_unique', 16, true, 'core.uniqueKey'); ?>
							</span>
						<?php }?>
					</td>
					<td>
						<?php if($canAlter && DataType::check($column->DATA_TYPE, DataType::SUPPORTS_FULLTEXT)) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('fulltext', $(this).closest('tr').attr('id').substr(8))" class="icon">
								<?php echo Html::icon('key_fulltext', 16, false, 'core.fulltextIndex'); ?>
							</a>
						<?php } else { ?>
							<span class="icon">
								<?php echo Html::icon('key_fulltext', 16, true, 'core.fulltextIndex'); ?>
							</span>
						<?php }?>
					</td>
					<?php if(is_array($foreignKeys)) { ?>
						<td>
							<a href="javascript:void(0)" onclick="tableStructure.editRelation($(this).closest('tr').attr('id').substr(8))" class="icon">
								<?php if(in_array(CHtml::encode($column->COLUMN_NAME), $foreignKeys)) { ?>
									<?php echo Html::icon('relation', 16, false, 'core.relation'); ?>
								<?php } else { ?>
									<?php echo Html::icon('relation', 16, true, 'core.relation'); ?>
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
				<th colspan="<?php if(is_array($foreignKeys)) { ?>15<?php } else { ?>14<?php } ?>"><?php echo Yii::t('core', 'XColumns', array('{count}' => count($table->columns))); ?></th>
			</tr>
		</tfoot>
	</table>

	<div class="buttonContainer">

		<div class="left withSelected">
			<span class="icon">
				<?php echo Html::icon('arrow_turn_090'); ?>
				<span><?php echo Yii::t('core', 'withSelected'); ?></span>
			</span>
			<?php if($canAlter) { ?>
				<a href="javascript:void(0)" onclick="tableStructure.dropColumns()" class="icon button">
					<?php echo Html::icon('delete'); ?>
					<span><?php echo Yii::t('core', 'drop'); ?></span>
				</a>
				<?php if(!$table->getHasPrimaryKey()) { ?>
					<a href="javascript:void(0)" onclick="tableStructure.addIndex('primary')" class="icon button">
						<?php echo Html::icon('key_primary', 16, false, 'core.primaryKey'); ?>
						<span><?php echo Yii::t('core', 'primaryKey'); ?></span>
					</a>
				<?php } ?>
				<a href="javascript:void(0)" onclick="tableStructure.addIndex('index')" class="icon button">
					<?php echo Html::icon('key_index', 16, false, 'core.index'); ?>
					<span><?php echo Yii::t('core', 'index'); ?></span>
				</a>
				<a href="javascript:void(0)" onclick="tableStructure.addIndex('unique')" class="icon button">
					<?php echo Html::icon('key_unique', 16, false, 'core.uniqueKey'); ?>
					<span><?php echo Yii::t('core', 'uniqueKey'); ?></span>
				</a>
				<a href="javascript:void(0)" onclick="tableStructure.addIndex('fulltext')" class="icon button">
					<?php echo Html::icon('key_fulltext', 16, false, 'core.fulltextIndex'); ?>
					<span><?php echo Yii::t('core', 'fulltextIndex'); ?></span>
				</a>
			<?php } else { ?>
				<span class="icon button">
					<?php echo Html::icon('delete', 16, true); ?>
					<span><?php echo Yii::t('core', 'drop'); ?></span>
				</span>
				<?php if(!$table->getHasPrimaryKey()) { ?>
					<span class="icon button">
						<?php echo Html::icon('key_primary', 16, true); ?>
						<span><?php echo Yii::t('core', 'primaryKey'); ?></span>
					</span>
				<?php } ?>
				<span class="icon button">
					<?php echo Html::icon('key_index', 16, true); ?>
					<span><?php echo Yii::t('core', 'index'); ?></span>
				</span>
				<span class="icon button">
					<?php echo Html::icon('key_unique', 16, true); ?>
					<span><?php echo Yii::t('core', 'uniqueKey'); ?></span>
				</span>
				<span class="icon button">
					<?php echo Html::icon('key_fulltext', 16, true); ?>
					<span><?php echo Yii::t('core', 'fulltextIndex'); ?></span>
				</span>
			<?php } ?>
		</div>

		<div class="right">
			<?php if($canAlter) { ?>
				<a href="javascript:void(0)" onclick="tableStructure.addColumn()" class="icon button">
					<?php echo Html::icon('add'); ?>
					<span><?php echo Yii::t('core', 'addColumn'); ?></span>
				</a>
			<?php } else { ?>
				<span class="icon button">
					<?php echo Html::icon('add', 16, true); ?>
					<span><?php echo Yii::t('core', 'addColumn'); ?></span>
				</span>
			<?php } ?>
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
							<th><?php echo Yii::t('core', 'index'); ?></th>
							<th><?php echo Yii::t('core', 'type'); ?></th>
							<th><?php echo Yii::t('core', 'cardinality'); ?></th>
							<th colspan="3"><?php echo Yii::t('core', 'field'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if(count($table->indices) < 1) { ?>
							<tr>
								<td class="noEntries" colspan="6">
									<?php echo Yii::t('core', 'noIndices'); ?>
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
											<li id="indices_<?php echo $index->INDEX_NAME; ?>_<?php echo CHtml::encode($column->COLUMN_NAME); ?>">
												<?php echo CHtml::encode($column->COLUMN_NAME); ?>
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
											<?php echo Html::icon('edit', 16, false, 'core.edit'); ?>
										</a>
									<?php } else { ?>
										<span class="icon">
											<?php echo Html::icon('edit', 16, true, 'core.edit'); ?>
										</span>
									<?php } ?>
								</td>
								<td>
									<?php if($canAlter) { ?>
										<a href="javascript:void(0)" onclick="tableStructure.dropIndex('<?php echo $index->INDEX_NAME; ?>')" class="icon">
											<?php echo Html::icon('delete', 16, false, 'core.drop'); ?>
										</a>
									<?php } else { ?>
										<span class="icon">
											<?php echo Html::icon('delete', 16, true, 'core.drop'); ?>
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
								<?php echo Html::icon('add'); ?>
								<span><?php echo Yii::t('core', 'addIndex'); ?></span>
							</a>
						<?php } else { ?>
							<span class="icon button">
								<?php echo Html::icon('add', 16, true); ?>
								<span><?php echo Yii::t('core', 'addIndex'); ?></span>
							</span>
						<?php } ?>
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
							<th><?php echo Yii::t('core', 'trigger'); ?></th>
							<th colspan="3"><?php echo Yii::t('core', 'event'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if(count($table->triggers) < 1) { ?>
							<tr>
								<td class="noEntries" colspan="4">
									<?php echo Yii::t('core', 'noTriggers'); ?>
								</td>
							</tr>
						<?php } ?>
						<?php foreach($table->triggers AS $trigger) { ?>
							<tr id="triggers_<?php echo $trigger->TRIGGER_NAME; ?>">
								<td><?php echo $trigger->TRIGGER_NAME; ?></td>
								<td>
									<?php echo $trigger->ACTION_TIMING . ' ' . $trigger->EVENT_MANIPULATION; ?>
								</td>
								<td>
									<a href="javascript:void(0)" onclick="tableStructure.editTrigger('<?php echo $trigger->TRIGGER_NAME; ?>')" class="icon">
										<?php echo Html::icon('edit', 16, false, 'core.edit'); ?>
									</a>
								</td>
								<td>
									<a href="javascript:void(0)" onclick="tableStructure.dropTrigger('<?php echo $trigger->TRIGGER_NAME; ?>')" class="icon">
										<?php echo Html::icon('delete', 16, false, 'core.drop'); ?>
									</a>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>

				<div class="buttonContainer">
					<div class="right">
						<a href="javascript:void(0)" onclick="tableStructure.addTrigger()" class="icon button">
							<?php echo Html::icon('add'); ?>
							<span><?php echo Yii::t('core', 'addTrigger'); ?></span>
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
						<th colspan="2"><?php echo Yii::t('core', 'spaceUsage'); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo Yii::t('core', 'data'); ?></td>
						<td class="right"><?php echo Formatter::fileSize($table->DATA_LENGTH); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('core', 'index'); ?></td>
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
						<td><?php echo Yii::t('core', 'storageEngine'); ?></td>
						<td><?php echo $table->ENGINE; ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('core', 'format'); ?></td>
						<td><?php echo $table->ROW_FORMAT; ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('core', 'collation'); ?></td>
						<td>
							<dfn class="collation" title="<?php echo Collation::getDefinition($table->TABLE_COLLATION); ?>">
								<?php echo $table->TABLE_COLLATION; ?>
							</dfn>
						</td>
					</tr>
					<tr>
						<td><?php echo Yii::t('core', 'rows'); ?></td>
						<td><?php echo $table->getRowCount(); ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('core', 'averageRowLength'); ?></td>
						<td><?php echo $table->AVG_ROW_LENGTH; ?></td>
					</tr>
					<tr>
						<td><?php echo Yii::t('core', 'averageRowSize'); ?></td>
						<td><?php echo Formatter::fileSize($table->getAverageRowSize()); ?></td>
					</tr>
					<?php if ($table->AUTO_INCREMENT) { ?>
						<tr>
							<td><?php echo Yii::t('core', 'nextAutoincrementValue'); ?></td>
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