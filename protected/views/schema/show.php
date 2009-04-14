<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->getRequest()->baseUrl.'/js/views/table/general.js', CClientScript::POS_HEAD); ?>

<h2><?php echo $schema->SCHEMA_NAME; ?></h2>

<div id="truncateTableDialog" title="<?php echo Yii::t('database', 'truncateTable'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToTruncateTable'); ?>
</div>
<div id="dropTableDialog" title="<?php echo Yii::t('database', 'dropTable'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToDropTable'); ?>
</div>

<div class="list">

	<table class="list addCheckboxes">
		<colgroup>
			<col />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="action" />
			<col class="number" />
			<col class="engine" />
			<col class="collation" />
			<col class="filesize" />
			<col class="filesize" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo $sort->link('TABLE_NAME', Yii::t('database', 'table'), array('rel'=>'no-ajax')); ?></th>
				<th colspan="6"><?php echo Yii::t('core', 'action'); ?></th>
				<th><?php echo $sort->link('TABLE_ROWS', Yii::t('database', 'rows'), array('rel'=>'no-ajax')); ?></th>
				<th><?php echo $sort->link('ENGINE', Yii::t('database', 'engine'), array('rel'=>'no-ajax')); ?></th>
				<th><?php echo $sort->link('TABLE_COLLATION', Yii::t('database', 'collation'), array('rel'=>'no-ajax')); ?></th>
				<th><?php echo $sort->link('DATA_LENGTH', Yii::t('database', 'dataSize'), array('rel'=>'no-ajax')); ?></th>
				<th><?php echo $sort->link('DATA_FREE', Yii::t('database', 'free'), array('rel'=>'no-ajax')); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $totalRowCount = $totalDataLength = $totalDataFree = 0;?>
			<?php foreach($schema->tables AS $table) { ?>
				<tr>
					<td>
						<a href="<?php echo Yii::app()->baseUrl; ?>/schema/<?php echo $schema->SCHEMA_NAME; ?>/tables/<?php echo $table->TABLE_NAME; ?>/structure">
							<?php echo $table->TABLE_NAME; ?>
						</a>
					</td>
					<td>
						<a href="#tables/<?php echo $table->TABLE_NAME; ?>/browse" class="icon" rel="no-ajax">
							<com:Icon name="browse" size="16" text="schema.browse" />
						</a>
					</td>
					<td>
						<a href="#tables/<?php echo $table->TABLE_NAME; ?>/structure" class="icon" rel="no-ajax">
							<com:Icon name="structure" size="16" text="schema.structure" />
						</a>
					</td>
					<td>
						<a href="#tables/<?php echo $table->TABLE_NAME; ?>/search" class="icon" rel="no-ajax">
							<com:Icon name="search" size="16" text="schema.search" />
						</a>
					</td>
					<td>
						<a href="#tables/<?php echo $table->TABLE_NAME; ?>/insert" class="icon" rel="no-ajax">
							<com:Icon name="insert" size="16" text="schema.insert" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0);" onclick="truncateTable('<?php echo $schema->SCHEMA_NAME; ?>', '<?php echo $table->TABLE_NAME; ?>')" class="icon" rel="no-ajax">
							<com:Icon name="truncate" size="16" text="schema.truncate" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0);" onclick="dropTable('<?php echo $schema->SCHEMA_NAME; ?>', '<?php echo $table->TABLE_NAME; ?>')" class="icon" rel="no-ajax">
							<com:Icon name="drop" size="16" text="schema.drop" />
						</a>
					</td>
					<td><?php echo $table->getRowCount(); ?></td>
					<td><?php echo $table->ENGINE; ?></td>
					<td><?php echo $table->TABLE_COLLATION; ?></td>
					<td><?php echo Formatter::fileSize($table->DATA_LENGTH); //@todo (rponudic) display real size here, check if this usage is correct ?></td>
					<td><?php echo Formatter::fileSize($table->DATA_FREE); //@todo (rponudic) display overhead here ?></td>
				</tr>
			<?php $totalRowCount += $table->getRowCount(); ?>
			<?php $totalDataLength += $table->DATA_LENGTH; ?>
			<?php $totalDataFree += $table->DATA_FREE; ?>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><?php echo Yii::t('database', 'amountTables', array($schema->tableCount, '{amount} '=> $schema->tableCount)); ?></th>
				<th colspan="6"></th>
				<th><?php echo $totalRowCount; ?></th>
				<th></th>
				<th></th>
				<th><?php echo Formatter::fileSize($totalDataLength); ?></th>
				<th><?php echo Formatter::fileSize($totalDataFree); ?></th>
			</tr>
		</tfoot>
	</table>

</div>