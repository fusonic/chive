<div class="list">

	<table id="columns" class="list addCheckboxes">
		<colgroup>
			<col class="checkbox" />
			<col />
			<col class="type" />
			<col class="collation" />
			<col class="null" />
			<col />
			<col />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" /></th>
				<th><?php echo Yii::t('core','field'); ?></th>
				<th><?php echo Yii::t('core','type'); ?></th>
				<th><?php echo Yii::t('core','collation'); ?></th>
				<th><?php echo Yii::t('core','null'); ?></th>
				<th><?php echo Yii::t('core','default'); ?></th>
				<th><?php echo Yii::t('core','extra'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(count($view->columns) < 1) { ?>
				<tr>
					<td class="noEntries" colspan="7">
						<?php echo Yii::t('core', 'noColumns'); ?>
					</td>
				</tr>
			<?php } ?>
			<?php foreach($view->columns AS $column) { ?>
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
				</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th><input type="checkbox" /></th>
				<th colspan="6"><?php echo Yii::t('core', 'XColumns', array('{count}' => count($view->columns))); ?></th>
			</tr>
		</tfoot>
	</table>

</div>