<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/views/table/structure.js', CClientScript::POS_HEAD); ?>

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
			<th><%= Yii::t('schema','field'); %></th>
			<th><%= Yii::t('schema','type'); %></th>
			<th><%= Yii::t('schema','collation'); %></th>
			<th><%= Yii::t('schema','null'); %></th>
			<th><%= Yii::t('schema','default'); %></th>
			<th><%= Yii::t('schema','extra'); %></th>
			<th colspan="8" />
		</tr>
	</thead>
	<tbody>
		<?php foreach ($table->columns AS $column) { ?>
			<tr id="columns_<?php echo $column->COLUMN_NAME; ?>">
				<td>
					<?php if($column->getIsPartOfPrimaryKey($table->indices)): ?>
						<span class="primaryKey"><?php echo $column->COLUMN_NAME; ?></span>
					<?php else: ?>
						<?php echo $column->COLUMN_NAME; ?>
					<?php endif; ?>
				</td>
				<td><%= $column->COLUMN_TYPE %></td>
				<td>
					<?php if(!is_null($column->COLLATION_NAME)): ?>
						<dfn class="collation" title="<?php echo Collation::getDefinition($column->COLLATION_NAME); ?>">
							<?php echo $column->COLLATION_NAME; ?>
						</dfn>
					<?php endif; ?>
				</td>
				<td>
					<?php echo Yii::t('core', strtolower($column->IS_NULLABLE)); ?>
				</td>
				<td>
					<?php if(is_null($column->COLUMN_DEFAULT) && $column->IS_NULLABLE == 'YES'): ?>
						<span class="null">NULL</span>
					<?php else: ?>
						<?php echo $column->COLUMN_DEFAULT; ?>
					<?php endif; ?>
				</td>
				<td><?php echo $column->EXTRA; ?></td>
				<td class="center">
					<a href="#sql-query">
						<com:Icon name="browse" size="16" text="database.browseDistinctValues" title={Yii::t('schema','browseDistinctValues')} />
					</a>
				</td>
				<td><com:Icon name="arrow_updown" size="16" text="core.move" /></td>
				<td>
					<a href="javascript:void(0)" onclick="editColumn('<?php echo $column->COLUMN_NAME; ?>')" class="icon">
						<com:Icon name="edit" size="16" text="core.edit"/>
					</a>
				</td>
				<td><com:Icon name="delete" size="16" text="core.delete"/></td>
				<td><com:Icon name="key_primary" size="16" text="database.primaryKey"/></td>
				<td><com:Icon name="key_unique" size="16" text="database.uniqueKey"/></td>
				<td><com:Icon name="key_index" size="16" text="database.index"/></td>
				<td><com:Icon name="key_fulltext" size="16" text="database.fulltextIndex"/></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<br/>

<table class="list" style="width: 42%; float: left;margin-right: 10px;">
	<colgroup>
		<col />
		<col />
		<col />
		<col class="action" />
		<col class="action" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th colspan="6"><?php echo Yii::t('schema', 'indices'); ?></th>
		</tr>
		<tr>
			<th><?php echo Yii::t('schema', 'key'); ?></th>
			<th><?php echo Yii::t('schema', 'type'); ?></th>
			<th><?php echo Yii::t('schema', 'cardinality'); ?></th>
			<th colspan="2"><?php echo Yii::t('schema', 'action'); ?></th>
			<th><?php echo Yii::t('schema', 'field'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($indices AS $key=>$index) { ?>
			<tr>
				<td><?php echo $index[0]->INDEX_NAME; ?></td>
				<td><?php echo $index[0]->getType(); ?></td>
				<td><?php echo $index[0]->CARDINALITY; ?></td>
				<td><com:Icon name="edit" size="16" text="core.edit" /></td>
				<td><com:Icon name="delete" size="16" text="core.delete" /></td>
				<td>
					<?php foreach($index AS $column) {?>
						<?php echo $column->COLUMN_NAME; ?><br/>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<table class="list" style="width: 25%; float: left; margin-right: 10px;">
	<colgroup>
		<col />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th colspan="2"><?php echo Yii::t('schema', 'spaceUsage'); ?></th>
		</tr>
		<tr>
			<th><?php echo Yii::t('schema', 'type'); ?></th>
			<th><?php echo Yii::t('schema', 'usage'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo Yii::t('schema', 'data'); ?></td>
			<td class="right"><?php echo Formatter::fileSize($table->DATA_LENGTH); ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('schema', 'index'); ?></td>
			<td class="right"><?php echo Formatter::fileSize($table->INDEX_LENGTH); ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('core', 'total'); ?></td>
			<td class="right"><?php echo Formatter::fileSize($table->INDEX_LENGTH + $table->DATA_LENGTH); ?></td>
		</tr>
	</tbody>
</table>

<table class="list" style="width: 30%; float: right;">
	<colgroup>
		<col />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th colspan="2">

				<?php echo Yii::t('core', 'information'); ?>
				<span class="icon">
					<com:Icon name="edit" size="16" text="core.information" />
					<span><?php echo Yii::t('core', 'information'); ?></span>
				</span>

			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo Yii::t('schema', 'format'); ?></td>
			<td><?php echo $table->ROW_FORMAT; ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('schema', 'collation'); ?></td>
			<td><?php echo $table->TABLE_COLLATION; ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('schema', 'rows'); ?></td>
			<td><?php echo $table->getRowCount(); ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('schema', 'averageRowLength'); ?></td>
			<td><?php echo $table->AVG_ROW_LENGTH; ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('schema', 'averageRowSize'); ?></td>
			<td><?php echo Formatter::fileSize($table->getAverageRowSize()); ?></td>
		</tr>
		<?php if ($table->AUTO_INCREMENT) { ?>
			<tr>
				<td><?php echo Yii::t('schema', 'nextAutoincrementValue'); ?></td>
				<td><?php echo $table->AUTO_INCREMENT; ?></td>
			</tr>
		<?php } ?>
		<tr>
			<td><?php echo Yii::t('core', 'creationDate'); ?></td>
			<td><?php echo Yii::app()->getDateFormatter()->formatDateTime($table->CREATE_TIME, 'short', 'short'); ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('core', 'lastUpdateDate'); ?></td>
			<td><?php echo Yii::app()->getDateFormatter()->formatDateTime($table->UPDATE_TIME, 'short', 'short'); ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('core', 'lastCheckDate'); ?></td>
			<td>
				<?php echo ($table->CHECK_TIME ? Yii::app()->getDateFormatter()->formatDateTime($table->CHECK_TIME, 'short', 'short') : '-'); ?>
			</td>
		</tr>
	</tbody>
</table>