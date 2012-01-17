<div id="truncateTablesDialog" title="<?php echo Yii::t('core', 'truncateTables'); ?>" style="display: none">
	<?php echo Yii::t('core', 'doYouReallyWantToTruncateTables'); ?>
	<ul></ul>
</div>
<div id="dropTablesDialog" title="<?php echo Yii::t('core', 'dropTables'); ?>" style="display: none">
	<?php echo Yii::t('core', 'doYouReallyWantToDropTables'); ?>
	<ul></ul>
</div>

<div class="list">
	<div class="buttonContainer">
		<div class="left">
			<?php $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaTables.addTable()">
				<?php echo Html::icon('add'); ?>
				<span><?php echo Yii::t('core', 'addTable'); ?></span>
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
				<th colspan="8"><?php echo $sort->link('name', Yii::t('core', 'table')); ?></th>
				<th><?php echo $sort->link('rows', Yii::t('core', 'rows')); ?></th>
				<th><?php echo $sort->link('engine', Yii::t('core', 'engine')); ?></th>
				<th><?php echo $sort->link('collation', Yii::t('core', 'collation')); ?></th>
				<th><?php echo $sort->link('datalength', Yii::t('core', 'size')); ?></th>
				<th><?php echo $sort->link('datafree', Yii::t('core', 'overhead')); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $totalRowCount = $totalDataLength = $totalDataFree = 0;?>
			<?php $canDrop = $canTruncate = false; ?>
			<?php if($tableCount < 1) { ?>
				<tr>
					<td class="noEntries" colspan="14">
						<?php echo Yii::t('core', 'noTables'); ?>
					</td>
				</tr>
			<?php } ?>
			<?php foreach($schema->tables AS $table) { ?>
				<?php $tableNameHtml = CHtml::encode($table->TABLE_NAME); ?>
				<?php $tableNameUrlEnc = urlencode($table->TABLE_NAME); ?>
				<tr id="tables_<?php echo $tableNameHtml; ?>">
					<td>
						<input type="checkbox" name="tables[]" value="<?php echo $tableNameHtml; ?>" />
					</td>
					<td>
						<?php echo Html::ajaxLink('tables/' . $tableNameUrlEnc . '/structure'); ?>
							<?php echo $tableNameHtml; ?>
						</a>
					</td>
					<td>
						<?php echo Html::ajaxLink('tables/' . $tableNameUrlEnc . '/browse', array('class' => 'icon')); ?>
							<?php echo Html::icon('browse', 16, false, 'core.browse'); ?>
						</a>
					</td>
					<td>
						<?php echo Html::ajaxLink('tables/' . $tableNameUrlEnc . '/structure', array('class' => 'icon')); ?>
							<?php echo Html::icon('structure', 16, false, 'core.structure'); ?>
						</a>
					</td>
					<td>
						<?php echo Html::ajaxLink('tables/' . $tableNameUrlEnc . '/search', array('class' => 'icon')); ?>
							<?php echo Html::icon('search', 16, false, 'core.search'); ?>
						</a>
					</td>
					<td>
						<?php echo Html::ajaxLink('tables/' . $tableNameUrlEnc . '/insert', array('class' => 'icon')); ?>
							<?php echo Html::icon('insert', 16, false, 'core.insert'); ?>
						</a>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkTable($table->TABLE_SCHEMA, $table->TABLE_NAME, 'ALTER')) { ?>
							<a href="javascript:void(0);" onclick="schemaTables.editTable($(this).closest('tr').attr('id').substr(7))" class="icon">
								<?php echo Html::icon('edit', 16, false, 'core.edit'); ?>
							</a>
						<?php } else { ?>
							<?php echo Html::icon('edit', 16, true, 'core.edit'); ?>
						<?php } ?>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkTable($table->TABLE_SCHEMA, $table->TABLE_NAME, 'DELETE')) { ?>
							<a href="javascript:void(0);" onclick="schemaTables.truncateTable($(this).closest('tr').attr('id').substr(7))" class="icon">
								<?php echo Html::icon('truncate', 16, false, 'core.truncate'); ?>
							</a>
							<?php $canTruncate = true; ?>
						<?php } else { ?>
							<?php echo Html::icon('truncate', 16, true, 'core.truncate'); ?>
						<?php } ?>
					</td>
					<td>
						<?php if(Yii::app()->user->privileges->checkTable($table->TABLE_SCHEMA, $table->TABLE_NAME, 'DROP')) { ?>
							<a href="javascript:void(0);" onclick="schemaTables.dropTable($(this).closest('tr').attr('id').substr(7))" class="icon">
								<?php echo Html::icon('delete', 16, false, 'core.drop'); ?>
							</a>
							<?php $canDrop = true; ?>
						<?php } else { ?>
							<?php echo Html::icon('delete', 16, true, 'core.drop'); ?>
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
				<th colspan="8"><?php echo Yii::t('core', 'amountTables', array($tableCount, '{amount} '=> $tableCount)); ?></th>
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
				<?php echo Html::icon('arrow_turn_090'); ?>
				<span><?php echo Yii::t('core', 'withSelected'); ?></span>
			</span>
			<?php if($canDrop) { ?>
				<a href="javascript:void(0)" onclick="schemaTables.dropTables()" class="icon button">
					<?php echo Html::icon('delete'); ?>
					<span><?php echo Yii::t('core', 'drop'); ?></span>
				</a>
			<?php } else { ?>
				<span class="icon button">
					<?php echo Html::icon('delete', 16, true); ?>
					<span><?php echo Yii::t('core', 'drop'); ?></span>
				</span>
			<?php } ?>
			<?php if($canTruncate) { ?>
				<a href="javascript:void(0)" onclick="schemaTables.truncateTables()" class="icon button">
					<?php echo Html::icon('truncate'); ?>
					<span><?php echo Yii::t('core', 'truncate'); ?></span>
				</a>
			<?php } else { ?>
				<span class="icon button">
					<?php echo Html::icon('truncate', 16, true); ?>
					<span><?php echo Yii::t('core', 'truncate'); ?></span>
				</span>
			<?php } ?>

			<a href="javascript:void(0)" onclick="schemaTables.runTableOperation('OPTIMIZE')" class="icon button">
					<?php echo Html::icon('accordion', 16); ?>
				<span><?php echo Yii::t('core', 'optimize'); ?></span>
			</a>

			<a href="javascript:void(0)" onclick="schemaTables.runTableOperation('CHECK')" class="icon button">
					<?php echo Html::icon('engine', 16); ?>
				<span><?php echo Yii::t('core', 'check'); ?></span>
			</a>

			<a href="javascript:void(0)" onclick="schemaTables.runTableOperation('ANALYZE')" class="icon button">
					<?php echo Html::icon('chart', 16); ?>
				<span><?php echo Yii::t('core', 'analyze'); ?></span>
			</a>

			<a href="javascript:void(0)" onclick="schemaTables.runTableOperation('REPAIR')" class="icon button">
					<?php echo Html::icon('operation', 16); ?>
				<span><?php echo Yii::t('core', 'repair'); ?></span>
			</a>

		</div>
		<div class="right">
			<a href="javascript:void(0)" class="icon button" onclick="schemaTables.addTable()">
				<?php echo Html::icon('add'); ?>
				<span><?php echo Yii::t('core', 'addTable'); ?></span>
			</a>
		</div>
	</div>

</div>

<script type="text/javascript">
setTimeout(function() {
	schemaTables.setupDialogs();
}, 500);
</script>