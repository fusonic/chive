<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery/jquery.jeditable.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/views/table/structure.js', CClientScript::POS_HEAD); ?>

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

	<table id="columns" class="list addCheckboxes">
		<colgroup>
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
		</colgroup>
		<thead>
			<tr>
				<th><%= Yii::t('database','field'); %></th>
				<th><%= Yii::t('database','type'); %></th>
				<th><%= Yii::t('database','collation'); %></th>
				<th><%= Yii::t('database','null'); %></th>
				<th><%= Yii::t('database','default'); %></th>
				<th colspan="9"><%= Yii::t('database','extra'); %></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($table->columns AS $column) { ?>
				<tr id="columns_<?php echo $column->COLUMN_NAME; ?>">
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
						<com:Icon disabled="true" name="browse" size="16" text="schema.browseDistinctValues" title={Yii::t('database','browseDistinctValues')} />
					</td>
					<td>
						<com:Icon name="arrow_move" size="16" text="core.move" htmlOptions={array('style'=>'cursor: pointer;')} />
					</td>
					<td>
						<a href="javascript:void(0)" onclick="tableStructure.editColumn('<?php echo $column->COLUMN_NAME; ?>')" class="icon">
							<com:Icon name="edit" size="16" text="core.edit" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="tableStructure.dropColumn('<?php echo $column->COLUMN_NAME; ?>')" class="icon">
							<com:Icon name="delete" size="16" text="database.drop" />
						</a>
					</td>
					<td>
						<?php if(!$table->getHasPrimaryKey()) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('primary', '<?php echo $column->COLUMN_NAME; ?>')" class="icon">
								<com:Icon name="key_primary" size="16" text="database.primaryKey" />
							</a>
						<?php } else { ?>
							<com:Icon disabled="true" name="key_primary" size="16" text="database.primaryKey" />
						<?php } ?>
					</td>
					<td>
						<?php if(DataType::check($column->DATA_TYPE, DataType::SUPPORTS_INDEX)) { ?>
						<a href="javascript:void(0)" onclick="tableStructure.addIndex1('index', '<?php echo $column->COLUMN_NAME; ?>')" class="icon">
							<com:Icon name="key_index" size="16" text="database.index" />
						</a>
						<?php } else { ?>
							<com:Icon name="key_index" size="16" text="database.index" disabled="true" />
						<?php }?>
					</td>
					<td>
						<?php if(DataType::check($column->DATA_TYPE, DataType::SUPPORTS_UNIQUE)) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('unique', '<?php echo $column->COLUMN_NAME; ?>')" class="icon">
								<com:Icon name="key_unique" size="16" text="database.uniqueKey" />
							</a>
						<?php } else { ?>
							<com:Icon name="key_unique" size="16" text="database.uniqueKey" disabled="true" />
						<?php }?>
					</td>
					<td>
						<?php if(DataType::check($column->DATA_TYPE, DataType::SUPPORTS_FULLTEXT)) { ?>
							<a href="javascript:void(0)" onclick="tableStructure.addIndex1('fulltext', '<?php echo $column->COLUMN_NAME; ?>')" class="icon">
								<com:Icon name="key_fulltext" size="16" text="database.fulltextIndex" />
							</a>
						<?php } else { ?>
							<com:Icon name="key_fulltext" size="16" text="database.fulltextIndex" disabled="true" />
						<?php }?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="14"><?php echo Yii::t('database', 'XColumns', array('{count}' => count($table->columns))); ?></th>
			</tr>
		</tfoot>
	</table>

	<div class="rightLinks">
		<a href="javascript:void(0)" onclick="tableStructure.addColumn()" class="icon">
			<com:Icon name="add" size="16" />
			<span><?php echo Yii::t('database', 'addColumn'); ?></span>
		</a>
	</div>

	<div class="withSelected">
		<span class="icon">
			<com:Icon name="arrow_turn_090" size="16" />
			<span><?php echo Yii::t('core', 'withSelected'); ?></span>
		</span>
		<a href="javascript:void(0)" onclick="tableStructure.dropColumns()" class="icon">
			<com:Icon name="delete" size="16" />
			<span><?php echo Yii::t('database', 'drop'); ?></span>
		</a>
		<?php if(!$table->getHasPrimaryKey()) { ?>
			<a href="javascript:void(0)" onclick="tableStructure.addIndex('primary')" class="icon">
				<com:Icon name="key_primary" size="16" text="database.primaryKey" />
				<span><?php echo Yii::t('database', 'primaryKey'); ?></span>
			</a>
		<?php } ?>
		<a href="javascript:void(0)" onclick="tableStructure.addIndex('index')" class="icon">
			<com:Icon name="key_index" size="16" text="database.index" />
			<span><?php echo Yii::t('database', 'index'); ?></span>
		</a>
		<a href="javascript:void(0)" onclick="tableStructure.addIndex('unique')" class="icon">
			<com:Icon name="key_unique" size="16" text="database.uniqueKey" />
			<span><?php echo Yii::t('database', 'uniqueKey'); ?></span>
		</a>
		<a href="javascript:void(0)" onclick="tableStructure.addIndex('fulltext')" class="icon">
			<com:Icon name="key_fulltext" size="16" text="database.fulltextIndex" />
			<span><?php echo Yii::t('database', 'fulltextIndex'); ?></span>
		</a>
	</div>

</div>

<br />

<div style="overflow: hidden">

	<div style="width: 40%; float: left; padding-right: 5px">

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
						<th colspan="6"><?php echo Yii::t('database', 'indices'); ?></th>
					</tr>
					<tr>
						<th><?php echo Yii::t('database', 'key'); ?></th>
						<th><?php echo Yii::t('database', 'type'); ?></th>
						<th><?php echo Yii::t('database', 'cardinality'); ?></th>
						<th colspan="3"><?php echo Yii::t('database', 'field'); ?></th>
					</tr>
				</thead>
				<tbody>
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
								<a href="javascript:void(0)" onclick="tableStructure.editIndex('<?php echo $index->INDEX_NAME; ?>')" class="icon">
									<com:Icon name="edit" size="16" text="core.edit" />
								</a>
							</td>
							<td>
								<a href="javascript:void(0)" onclick="tableStructure.dropIndex('<?php echo $index->INDEX_NAME; ?>')" class="icon">
									<com:Icon name="delete" size="16" text="database.drop" />
								</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>

			<div class="rightLinks">
				<a href="javascript:void(0)" onclick="tableStructure.addIndexForm()" class="icon">
					<com:Icon name="add" size="16" />
					<span><?php echo Yii::t('database', 'addIndex'); ?></span>
				</a>
			</div>

		</div>

	</div>

	<div style="width: 20%; float: left; padding: 0px 10px">

		<table class="list">
			<colgroup>
				<col />
				<col />
			</colgroup>
			<thead>
				<tr>
					<th colspan="2"><?php echo Yii::t('database', 'spaceUsage'); ?></th>
				</tr>
				<tr>
					<th><?php echo Yii::t('database', 'type'); ?></th>
					<th><?php echo Yii::t('database', 'usage'); ?></th>
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

	<div style="width: 20%; float: left; padding-left: 10px">

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