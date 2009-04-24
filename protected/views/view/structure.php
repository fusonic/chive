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
			<?php foreach($view->columns AS $column) { ?>
				<tr id="columns_<?php echo $column->COLUMN_NAME; ?>">
					<td>
						<?php echo $column->COLUMN_NAME; ?>
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
				</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="14"><?php echo Yii::t('database', 'XColumns', array('{count}' => count($view->columns))); ?></th>
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
	</div>

</div>

<br />
<!---
<div style="overflow: hidden">

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
					<td class="right"><?php echo Formatter::fileSize($view->DATA_LENGTH); ?></td>
				</tr>
				<tr>
					<td><?php echo Yii::t('database', 'index'); ?></td>
					<td class="right"><?php echo Formatter::fileSize($view->INDEX_LENGTH); ?></td>
				</tr>
				<tr>
					<td><?php echo Yii::t('core', 'total'); ?></td>
					<td class="right"><?php echo Formatter::fileSize($view->INDEX_LENGTH + $view->DATA_LENGTH); ?></td>
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
					<td><?php echo $view->ROW_FORMAT; ?></td>
				</tr>
				<tr>
					<td><?php echo Yii::t('database', 'collation'); ?></td>
					<td>
						<dfn class="collation" title="<?php echo Collation::getDefinition($view->TABLE_COLLATION); ?>">
							<?php echo $view->TABLE_COLLATION; ?>
						</dfn>
					</td>
				</tr>
				<tr>
					<td><?php echo Yii::t('database', 'rows'); ?></td>
					<td><?php echo $view->getRowCount(); ?></td>
				</tr>
				<tr>
					<td><?php echo Yii::t('database', 'averageRowLength'); ?></td>
					<td><?php echo $view->AVG_ROW_LENGTH; ?></td>
				</tr>
				<tr>
					<td><?php echo Yii::t('database', 'averageRowSize'); ?></td>
					<td><?php echo Formatter::fileSize($view->getAverageRowSize()); ?></td>
				</tr>
				<?php if ($view->AUTO_INCREMENT) { ?>
					<tr>
						<td><?php echo Yii::t('database', 'nextAutoincrementValue'); ?></td>
						<td><?php echo $view->AUTO_INCREMENT; ?></td>
					</tr>
				<?php } ?>
				<tr>
					<td><?php echo Yii::t('core', 'creationDate'); ?></td>
					<td><?php echo ($view->CREATE_TIME ? Yii::app()->getDateFormatter()->formatDateTime($view->CREATE_TIME, 'short', 'short') : '-'); ?></td>
				</tr>
				<tr>
					<td><?php echo Yii::t('core', 'lastUpdateDate'); ?></td>
					<td><?php echo ($view->UPDATE_TIME ? Yii::app()->getDateFormatter()->formatDateTime($view->UPDATE_TIME, 'short', 'short') : '-'); ?></td>
				</tr>
				<tr>
					<td><?php echo Yii::t('core', 'lastCheckDate'); ?></td>
					<td>
						<?php echo ($view->CHECK_TIME ? Yii::app()->getDateFormatter()->formatDateTime($view->CHECK_TIME, 'short', 'short') : '-'); ?>
					</td>
				</tr>
			</tbody>
		</table>

	</div>

</div>

--->