<div id="truncateTablesDialog" title="<?php echo Yii::t('database', 'truncateTables'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToTruncateTables'); ?>
	<ul></ul>
</div>
<div id="dropTablesDialog" title="<?php echo Yii::t('database', 'dropTables'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToDropTables'); ?>
	<ul></ul>
</div>

<div class="list">
	<div class="buttonContainer">
		<div class="left">
			<?php $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaTables.addTable()">
				<com:Icon name="add" size="16" />
				<span><?php echo Yii::t('database', 'addTable'); ?></span>
			</a>
		</div>
	</div>

	<table class="list addCheckboxes selectable" id="tables">
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
				<th colspan="8"><?php echo $sort->link('TABLE_NAME', Yii::t('database', 'table')); ?></th>
				<th><?php echo $sort->link('TABLE_ROWS', Yii::t('database', 'rows')); ?></th>
				<th><?php echo $sort->link('ENGINE', Yii::t('database', 'engine')); ?></th>
				<th><?php echo $sort->link('TABLE_COLLATION', Yii::t('database', 'collation')); ?></th>
				<th><?php echo $sort->link('DATA_LENGTH', Yii::t('core', 'size')); ?></th>
				<th><?php echo $sort->link('DATA_FREE', Yii::t('database', 'overhead')); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $totalRowCount = $totalDataLength = $totalDataFree = 0;?>
			<?php $canDrop = $canTruncate = false; ?>
			<?php if($tableCount < 1) { ?>
				<tr>
					<td class="noEntries" colspan="14">
						<?php echo Yii::t('database', 'noTables'); ?>
					</td>
				</tr>
			<?php } ?>
			<?php foreach($schema->tables AS $table) { ?>
				<tr id="tables_<?php echo $table->TABLE_NAME; ?>">
					<td>
						<input type="checkbox" name="tables[]" value="<?php echo $table->TABLE_NAME; ?>" />
					</td>
					<td>
						<a href="javascript:chive.goto('tables/<?php echo $table->TABLE_NAME; ?>/structure')">
							<?php echo $table->TABLE_NAME; ?>
						</a>
					</td>
					<td>
						<a href="javascript:chive.goto('tables/<?php echo $table->TABLE_NAME; ?>/browse')" class="icon">
							<com:Icon name="browse" size="16" text="database.browse" />
						</a>
					</td>
					<td>
						<a href="javascript:chive.goto('tables/<?php echo $table->TABLE_NAME; ?>/structure')" class="icon">
							<com:Icon name="structure" size="16" text="database.structure" />
						</a>
					</td>
					<td>
						<a href="javascript:chive.goto('tables/<?php echo $table->TABLE_NAME; ?>/search')" class="icon">
							<com:Icon name="search" size="16" text="core.search" />
						</a>
					</td>
					<td>
						<a href="javascript:chive.goto('tables/<?php echo $table->TABLE_NAME; ?>/insert')" class="icon">
							<com:Icon name="insert" size="16" text="database.insert" />
						</a>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkTable($table->TABLE_SCHEMA, $table->TABLE_NAME, 'ALTER')) { ?>
							<a href="javascript:void(0);" onclick="schemaTables.editTable($(this).closest('tr').attr('id').substr(7))" class="icon">
								<com:Icon name="edit" size="16" text="core.edit" />
							</a>
						<?php } else { ?>
							<com:Icon name="edit" size="16" text="core.edit" disabled="true" />
						<?php } ?>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkTable($table->TABLE_SCHEMA, $table->TABLE_NAME, 'DELETE')) { ?>
							<a href="javascript:void(0);" onclick="schemaTables.truncateTable($(this).closest('tr').attr('id').substr(7))" class="icon">
								<com:Icon name="truncate" size="16" text="database.truncate" />
							</a>
							<?php $canTruncate = true; ?>
						<?php } else { ?>
							<com:Icon name="truncate" size="16" text="database.truncate" disabled="true" />
						<?php } ?>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkTable($table->TABLE_SCHEMA, $table->TABLE_NAME, 'DROP')) { ?>
							<a href="javascript:void(0);" onclick="schemaTables.dropTable($(this).closest('tr').attr('id').substr(7))" class="icon">
								<com:Icon name="delete" size="16" text="database.drop" />
							</a>
							<?php $canDrop = true; ?>
						<?php } else { ?>
							<com:Icon name="delete" size="16" text="database.drop" disabled="true" />
						<?php } ?>
					</td>
					<td>
						<?php echo $table->getRowCount(); ?>
					</td>
					<td>
						<?php echo $table->ENGINE; ?>
					</td>
					<td>
						<dfn title="<?php echo Collation::getDefinition($table->TABLE_COLLATION); ?>"><?php echo $table->TABLE_COLLATION; ?></dfn>
					</td>
					<td style="text-align: right">
						<?php echo Formatter::fileSize($table->DATA_LENGTH + $table->INDEX_LENGTH); ?>
					</td>
					<td style="text-align: right">
						<?php echo Formatter::fileSize($table->DATA_FREE); ?>
					</td>
				</tr>
			<?php $totalRowCount += $table->getRowCount(); ?>
			<?php $totalDataLength += $table->DATA_LENGTH; ?>
			<?php $totalDataFree += $table->DATA_FREE; ?>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="8"><?php echo Yii::t('database', 'amountTables', array($tableCount, '{amount} '=> $tableCount)); ?></th>
				<th><?php echo $totalRowCount; ?></th>
				<th></th>
				<th></th>
				<th style="text-align: right"><?php echo Formatter::fileSize($totalDataLength); ?></th>
				<th style="text-align: right"><?php echo Formatter::fileSize($totalDataFree); ?></th>
			</tr>
		</tfoot>
	</table>

	<div class="buttonContainer">
		<div class="left withSelected">
			<span class="icon">
				<com:Icon name="arrow_turn_090" size="16" />
				<span><?php echo Yii::t('core', 'withSelected'); ?></span>
			</span>
			<?php if($canDrop) { ?>
				<a href="javascript:void(0)" onclick="schemaTables.dropTables()" class="icon button">
					<com:Icon name="delete" size="16" />
					<span><?php echo Yii::t('database', 'drop'); ?></span>
				</a>
			<?php } else { ?>
				<span class="icon button">
					<com:Icon name="delete" size="16" disabled="true" />
					<span><?php echo Yii::t('database', 'drop'); ?></span>
				</span>
			<?php } ?>
			<?php if($canTruncate) { ?>
				<a href="javascript:void(0)" onclick="schemaTables.truncateTables()" class="icon button">
					<com:Icon name="truncate" size="16" />
					<span><?php echo Yii::t('database', 'truncate'); ?></span>
				</a>
			<?php } else { ?>
				<span class="icon button">
					<com:Icon name="truncate" size="16" disabled="true" />
					<span><?php echo Yii::t('database', 'truncate'); ?></span>
				</span>
			<?php } ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaTables.addTable()">
				<com:Icon name="add" size="16" />
				<span><?php echo Yii::t('database', 'addTable'); ?></span>
			</a>
		</div>
	</div>

</div>

<script type="text/javascript">
setTimeout(function() {
	schemaTables.setupDialogs();
}, 500);
</script>