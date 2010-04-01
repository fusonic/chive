<div id="deleteRowDialog" title="<?php echo Yii::t('core', 'deleteRows'); ?>" style="display: none">
	<?php echo Yii::t('core', 'doYouReallyWantToDeleteSelectedRows'); ?>
</div>

<?php if($model->showInput) { ?>

	<?php echo CHtml::form(Yii::app()->createUrl($model->formTarget), 'post', array('id' => 'queryForm')); ?>
	<table style="width: 100%;">
		<tr>
			<td style="width: 80%;">
				<?php $this->widget("SqlEditor", array(
					    'id' => 'query',
						'autogrow' => true,
					   	'htmlOptions' => array('name' => 'query'),
						'value' => $model->getOriginalQueries(),
					)); ?>
				<?php /*<textarea name="query" style="width: 99%; height: 90px;" id="query"><?php echo $model->getOriginalQueries(); ?></textarea> */ ?>
				<div class="buttons">
					<a href="javascript:void(0);" onclick="$('#queryForm').submit();" class="icon button primary">
						<?php echo Html::icon('execute', 16, false, 'core.execute'); ?>
						<span><?php echo Yii::t('core', 'execute'); ?></span>
					</a>
					<a class="icon button" href="javascript:void(0);" onclick="Bookmark.add('<?php echo $model->schema; ?>', (editAreaLoader ? editAreaLoader.getValue('query') : $('#query').val()));">
						<?php echo Html::icon('bookmark_add'); ?>
						<span><?php echo Yii::t('core', 'bookmark'); ?></span>
					</a>
				</div>
			</td>
			<td style="vertical-align: top; padding: 2px 5px;">
				<a class="icon button" href="javascript:void(0);" onclick="Profiling.toggle();">
					<?php if( Yii::app()->user->settings->get('profiling')) {?>
						<?php echo Html::icon('square_green', 16, false, null, array('id' => 'profiling_indicator')); ?>
					<?php } else { ?>
						<?php echo Html::icon('square_red', 16, false, null, array('id' => 'profiling_indicator')); ?>
					<?php } ?>
					<span><?php echo Yii::t('core', 'profiling'); ?></span>
				</a>
				<br/><br/>
				<a class="icon button" href="javascript:void(0);" onclick="$.post(baseUrl + '/ajaxSettings/toggle', {
						name: 'showFullColumnContent',
						scope: 'schema.table.browse',
						object: '<?php echo $model->schema; ?>.<?php echo $model->table; ?>'
					}, function() {
						chive.refresh();
					});">
					<?php if( Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $model->schema . '.' .  $model->table)) {?>
						<?php echo Html::icon('square_green'); ?>
					<?php } else { ?>
						<?php echo Html::icon('square_red'); ?>
					<?php } ?>
					<span><?php echo Yii::t('core', 'showFullColumnContent'); ?></span>
				</a>
				<br/><br/>
				<a id="aToggleEditor" class="icon button" href="javascript:void(0);" onclick="toggleEditor('query','aToggleEditor');">
					<?php if( Yii::app()->user->settings->get('sqlEditorOn') == '1') {?>
						<?php echo Html::icon('square_green'); ?>
					<?php } else { ?>
						<?php echo Html::icon('square_red'); ?>
					<?php } ?>
					<span><?php echo Yii::t('core', 'toggleEditor'); ?></span>
				</a>
			</td>
		</tr>
	</table>

	<?php echo CHtml::endForm(); ?>

<?php } ?>

<?php if($model->hasResultSet() && $model->getData()) { ?>

	<div class="list">
		<div class="buttonContainer">
			<?php $this->widget('LinkPager',array('pages'=>$model->getPagination())); ?>
		</div>

		<?php $i = 0; ?>
		<table class="list <?php if($model->getIsUpdatable()) { ?>addCheckboxes editable<?php } ?>" style="width: auto;" id="browse">
			<colgroup>
				<col class="checkbox" />
				<?php if(isset($type) && $type == 'select') { ?>
					<col class="action" />
					<col class="action" />
					<col class="action" />
				<?php } ?>
				<?php foreach ($model->getColumns() AS $column) { ?>
					<?php echo '<col />'; ?>
				<?php } ?>
			</colgroup>
			<thead>
				<tr>
					<?php if($model->getQueryType() == 'select') { ?>
						<th><input type="checkbox" /></th>
						<th></th>
						<th></th>
						<th></th>
					<?php } ?>
					<?php foreach ($model->getColumns ()AS $column) { ?>
						<th><?php echo ($model->getQueryType() == 'select' ? $model->getSort()->link($column) : $column); ?></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($model->getData() AS $row) { ?>
					<tr>
						<?php if($model->getQueryType() == 'select') { ?>
							<td>
								<input type="checkbox" name="browse[]" value="row_<?php echo $i; ?>" />
							</td>
							<td class="action">
								<a href="javascript:void(0);" class="icon" onclick="globalBrowse.deleteRow(<?php echo $i; ?>);">
									<?php echo Html::icon('delete', 16, false, 'core.delete'); ?>
								</a>
							</td>
							<td class="action">
								<a href="javascript:void(0);" class="icon" onclick="globalBrowse.editRow(<?php echo $i; ?>);">
									<?php echo Html::icon('edit', 16, false, 'core.edit'); ?>
								</a>
							</td>
							<td class="action">
								<a href="javascript:void(0);" class="icon" onclick="globalBrowse.insertAsNewRow(<?php echo $i; ?>);">
									<?php echo Html::icon('insert', 16, false, 'core.insert'); ?>
								</a>
							</td>
						<?php } ?>
						<?php foreach($row AS $key=>$value) { ?>
							<td class="<?php echo $key; ?>">
								<?php if(DataType::getInputType($model->getTable()->columns[$key]->dbType) == "file" && $value) { ?>
									<a href="javascript:void(0);" class="icon" onclick="globalBrowse.download('<?php echo Yii::app()->createUrl('row/download'); ?>', {key: JSON.stringify(keyData[<?php echo $i; ?>]), column: '<?php echo $column; ?>', table: '<?php echo $model->table; ?>', schema: '<?php echo $model->schema; ?>'})">
										<?php echo Html::icon('save'); ?> 
										<?php echo Formatter::fileSize(strlen($value)); ?>
									</a>
								<?php } elseif($model->table !== null) { ?>
									<span><?php echo is_null($value) ? '<span class="null">NULL</span>' : (Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $model->schema . '.' .  $model->table) ? htmlspecialchars($value) : StringUtil::cutText(htmlspecialchars($value), 100)); ?></span>
								<?php } else { ?>
									<span><?php echo is_null($value) ? '<span class="null">NULL</span>' : (Yii::app()->user->settings->get('showFullColumnContent', 'schema.browse', $model->schema) ? htmlspecialchars($value) : StringUtil::cutText(htmlspecialchars($value), 100)); ?></span>
								<?php } ?>
							</td>
							<?php if($model->getIsUpdatable() && (in_array($key, (array)$model->getTable()->primaryKey) || $model->getTable()->primaryKey === null)) { ?>
								<?php $keyData[$i][$key] = is_null($value) ? null : $value; ?>
							<?php } ?>
						<?php } ?>
					</tr>
					<?php $i++; ?>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="<?php echo 4 + count($row); ?>">
						<?php echo Yii::t('core', 'showingRowsOfRows', array('{start}' => $model->getStart(), '{end}' => min($model->getStart() + $model->getPagination()->getPagesize(), $model->getTotal()), '{total}' => $model->getTotal())); ?>
					</th>
				</tr>
			</tfoot>
		</table>

	<div class="buttonContainer">
		<?php if ($model->getQueryType() == 'select') { ?>
			<div class="withSelected left">
				<span class="icon">
					<?php echo Html::icon('arrow_turn_090'); ?>
					<span><?php echo Yii::t('core', 'withSelected'); ?></span>
				</span>
				<a class="icon button" href="javascript:void(0)" onclick="globalBrowse.deleteRows()">
					<?php echo Html::icon('delete', 16, false, 'core.delete'); ?>
					<span><?php echo Yii::t('core', 'delete'); ?></span>
				</a>
				<a class="icon button" href="javascript:void(0)" onclick="globalBrowse.exportRows()">
					<?php echo Html::icon('save', 16, false, 'core.export'); ?>
					<span><?php echo Yii::t('core', 'export'); ?></span>
				</a>
			</div>
			<?php if ($keyData) { ?>
				<script type="text/javascript">
					var keyData = <?php echo json_encode($keyData); ?>;
				</script>
			<?php } ?>
		<?php } ?>
	</div>
	<div class="buttonContainer">
		<?php $this->widget('LinkPager',array('pages'=>$model->getPagination())); ?>
	</div>

<?php } elseif($model->execute) { ?>
	<?php echo Yii::t('core', 'emptyResultSet'); ?>
<?php } ?>


<script type="text/javascript">
	globalBrowse.setup();
	AjaxResponse.handle(<?php echo $model->getResponse(); ?>);
</script>