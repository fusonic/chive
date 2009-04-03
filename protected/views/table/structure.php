<div class="pager top">
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div>

<table class="list addCheckboxes">
	<colgroup>
		<col />
		<col />
		<col class="collation" />
		<col />
		<col />
		<col />
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
			<th><%= Yii::t('database','extra'); %></th>
			<th colspan="7"><%= Yii::t('database','actions'); %></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($table->columns AS $column) { ?>
			<tr>
				<td><%= $column->COLUMN_NAME %></td>
				<td><%= $column->COLUMN_TYPE %></td>
				<td><%= $column->COLLATION_NAME %></td>
				<td><%= $column->IS_NULLABLE %></td>
				<td>
					<%= (is_null($column->COLUMN_DEFAULT) && $column->IS_NULLABLE == 'YES' ? 'NULL' : $column->COLUMN_DEFAULT) %>
				</td>
				<td><%= $column->EXTRA %></td>
				<td class="center">
					<a href="#sql-query">
						<com:Icon name="browse" size="16" text="database.browseDistinctValues" title={Yii::t('database','browseDistinctValues')} />
					</a>
				</td>
				<td><com:Icon name="edit" size="16" text="core.edit"/></td>
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

<table class="list" style="width: 30%; float: left;margin-right: 10px;">
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
			<th colspan="6"><?php echo Yii::t('database', 'indices'); ?></th>
		</tr>
		<tr>
			<th><?php echo Yii::t('database', 'key'); ?></th>
			<th><?php echo Yii::t('database', 'type'); ?></th>
			<th><?php echo Yii::t('database', 'cardinality'); ?></th>
			<th colspan="2"><?php echo Yii::t('database', 'action'); ?></th>
			<th><?php echo Yii::t('database', 'field'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($table->indices AS $index) { ?>
			<tr>
				<td><?php echo $index->INDEX_NAME; ?></td>
				<td><?php echo $index->getType(); ?></td>
				<td><?php echo $index->CARDINALITY; ?></td>
				<td><com:Icon name="edit" size="16" text="core.edit" /></td>
				<td><com:Icon name="delete" size="16" text="core.delete" /></td>
				<td>
					<?php echo $index->COLUMN_NAME; ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<table class="list" style="width: 30%; float: left;margin-right: 10px;">
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
			<td><?php echo Yii::t('core', 'data'); ?></td>
			<td><?php echo $table->INDEX_LENGTH; ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('core', 'index'); ?></td>
			<td><?php echo $table->DATA_LENGTH; ?></td>
		</tr>
		<tr>
			<td class="bold"><?php echo Yii::t('core', 'total'); ?></td>
			<td class="bold"><?php echo $table->INDEX_LENGTH + $table->DATA_LENGTH; ?></td>
		</tr>
	</tbody>
</table>

<table class="list" style="width: 30%; float: left;margin-right: 10px;">
	<colgroup>
		<col />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th colspan="2">
				<span class="icon">
					<com:Icon name="edit" size="16" text="core.information" />
					<span><?php echo Yii::t('core', 'informations'); ?></span>
				</span>
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
			<td><?php echo $table->TABLE_COLLATION; ?></td>
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
			<td><?php echo $table->getAverageRowSize(); ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('database', 'nextAutoincrementValue'); ?></td>
			<td><?php echo $table->AUTO_INCREMENT; ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('core', 'creationDate'); ?></td>
			<td><?php echo Yii::app()->getDateFormatter()->formatDateTime($table->CREATE_TIME); ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('core', 'lastUpdateDate'); ?></td>
			<td><?php echo Yii::app()->getDateFormatter()->formatDateTime($table->UPDATE_TIME); ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('core', 'lastCheckDate'); ?></td>
			<td><?php echo Yii::app()->getDateFormatter()->formatDateTime($table->CHECK_TIME); ?></td>
		</tr>
	</tbody>
</table>

<div class="pager bottom">
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div>