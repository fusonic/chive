<h2><?php echo $schema->SCHEMA_NAME; ?></h2>

<div id="truncateTablesDialog" title="<?php echo Yii::t('database', 'truncateTables'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToTruncateTables'); ?>
</div>
<div id="dropTablesDialog" title="<?php echo Yii::t('database', 'dropTables'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToDropTables'); ?>
</div>

<div class="list">

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
			<?php foreach($schema->tables AS $table) { ?>
				<tr id="tables_<?php echo $table->TABLE_NAME; ?>">
					<td>
						<input type="checkbox" name="tables[]" value="<?php echo $table->TABLE_NAME; ?>" />
					</td>
					<td>
						<a href="#tables/<?php echo $table->TABLE_NAME; ?>/structure">
							<?php echo $table->TABLE_NAME; ?>
						</a>
					</td>
					<td>
						<a href="#tables/<?php echo $table->TABLE_NAME; ?>/browse" class="icon">
							<com:Icon name="browse" size="16" text="database.browse" />
						</a>
					</td>
					<td>
						<a href="#tables/<?php echo $table->TABLE_NAME; ?>/structure" class="icon">
							<com:Icon name="structure" size="16" text="database.structure" />
						</a>
					</td>
					<td>
						<a href="#tables/<?php echo $table->TABLE_NAME; ?>/search" class="icon">
							<com:Icon name="search" size="16" text="core.search" />
						</a>
					</td>
					<td>
						<a href="#tables/<?php echo $table->TABLE_NAME; ?>/insert" class="icon">
							<com:Icon name="insert" size="16" text="database.insert" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0);" onclick="schemaShow.editTable($(this).closest('tr').attr('id').substr(7))" class="icon">
							<com:Icon name="edit" size="16" text="core.edit" />
						</a>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkTable($table->TABLE_SCHEMA, $table->TABLE_NAME, 'DELETE')) { ?>
						<a href="javascript:void(0);" onclick="schemaShow.truncateTable($(this).closest('tr').attr('id').substr(7))" class="icon">
							<com:Icon name="truncate" size="16" text="database.truncate" />
						</a>
						<?php } else { ?>
							<com:Icon name="truncate" size="16" text="database.truncate" disabled="true" />
						<?php } ?>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkTable($table->TABLE_SCHEMA, $table->TABLE_NAME, 'DROP')) { ?>
							<a href="javascript:void(0);" onclick="schemaShow.dropTable($(this).closest('tr').attr('id').substr(7))" class="icon">
								<com:Icon name="delete" size="16" text="database.drop" />
							</a>
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
				<th colspan="8"><?php echo Yii::t('database', 'amountTables', array($schema->tableCount, '{amount} '=> $schema->tableCount)); ?></th>
				<th><?php echo $totalRowCount; ?></th>
				<th></th>
				<th></th>
				<th style="text-align: right"><?php echo Formatter::fileSize($totalDataLength); ?></th>
				<th style="text-align: right"><?php echo Formatter::fileSize($totalDataFree); ?></th>
			</tr>
		</tfoot>
	</table>

	<div class="rightLinks">
		<a href="javascript:void(0)" class="icon">
			<com:Icon name="add" size="16" />
			<span><?php echo Yii::t('database', 'addTable'); ?></span>
		</a>
	</div>

	<div class="withSelected">
		<span class="icon">
			<com:Icon name="arrow_turn_090" size="16" />
			<span><?php echo Yii::t('core', 'withSelected'); ?></span>
		</span>
		<a href="javascript:void(0)" onclick="schemaShow.dropTables()" class="icon">
			<com:Icon name="delete" size="16" />
			<span><?php echo Yii::t('database', 'drop'); ?></span>
		</a>
		<a href="javascript:void(0)" onclick="schemaShow.truncateTables()" class="icon">
			<com:Icon name="truncate" size="16" />
			<span><?php echo Yii::t('database', 'truncate'); ?></span>
		</a>
	</div>

</div>

<script type="text/javascript">
schemaShow.setupDialogs();
</script>