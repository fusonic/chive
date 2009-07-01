
<div id="deleteRowDialog" title="<?php echo Yii::t('message', 'deleteRows'); ?>" style="display: none">
	<?php echo Yii::t('message', 'doYouReallyWantToDeleteSelectedRows'); ?>
</div>

<?php if($model->showInput) { ?>

	<?php echo CHtml::form(BASEURL . '/' . $model->formTarget, 'post', array('id' => 'queryForm')); ?>
	<table style="width: 100%;">
		<tr>
			<td style="width: 80%;">
				<?php $this->widget("SqlEditor", array(
					'autogrow' => true,
				    'allowToggle' => false,
					'htmlOptions' => array('name' => 'query'),
					'value' => $model->getOriginalQueries(),
				)); ?>
			
				<?php /*<textarea name="query" style="width: 99%; height: 90px;" id="query"><?php echo $model->getOriginalQueries(); ?></textarea> */ ?>
				<div class="buttons">
					<a href="javascript:void(0);" onclick="$('form').submit();" class="icon button">
						<com:Icon size="16" name="execute" text="core.execute" />
						<span><?php echo Yii::t('core', 'execute'); ?></span>
					</a>
				</div>
			</td>
			<td style="vertical-align: top; padding: 2px 5px;">
				<a class="icon button" href="javascript:void(0);" onclick="Bookmark.add('<?php echo $this->schema; ?>', $('#query').val());">
					<com:Icon size="16" name="bookmark_add" />
					<span><?php echo Yii::t('core', 'bookmark'); ?></span>
				</a>
				<br/><br/>
				<a class="icon button" href="javascript:void(0);" onclick="Profiling.toggle();">
					<?php if( Yii::app()->user->settings->get('profiling')) {?>
						<com:Icon size="16" name="square_green" text="core.on" htmlOptions={array('id'=>'profiling_indicator')} />
					<?php } else { ?>
						<com:Icon size="16" name="square_red" text="core.off" htmlOptions={array('id'=>'profiling_indicator')} />
					<?php } ?>
					<span><?php echo Yii::t('database', 'profiling'); ?></span>
				</a>
				<br/><br/>
				<a class="icon button" href="javascript:void(0);" onclick="$.post(baseUrl + '/ajaxSettings/toggle', {
						name: 'showFullColumnContent',
						scope: 'schema.table.browse',
						object: '<?php echo $model->schema; ?>.<?php echo $model->table; ?>'
					}, function() {
						refresh();
					});">
					<?php if( Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $model->schema . '.' .  $model->table)) {?>
						<com:Icon size="16" name="square_green" />
					<?php } else { ?>
						<com:Icon size="16" name="square_red" />
					<?php } ?>
					<span><?php echo Yii::t('core', 'showFullColumnContent'); ?></span>
				</a>
			</td>
		</tr>
	</table>
	
	<?php echo CHtml::endForm(); ?>
	
	<script type="text/javascript">
		$('#queryForm').ajaxForm({
			success: 	function(responseText)
			{
				AjaxResponse.handle(responseText);
				$('div.ui-layout-center').html(responseText);
				init();
			}
		});
	</script>

<?php } ?>

<?php if($model->hasResultSet() && $model->getData()) { ?>

	<div class="list">
		<div class="buttonContainer">
			<?php $this->widget('LinkPager',array('pages'=>$model->getPagination())); ?>
		</div>

		<?php $i = 0; ?>
		<table class="list <?php if($model->getIsEditable()) { ?>addCheckboxes editable<?php } ?>" style="width: auto;" id="browse">
			<colgroup>
				<col class="checkbox" />
				<?php if($type == 'select') { ?>
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
									<com:Icon name="delete" size="16" text="core.delete" />
								</a>
							</td>
							<td class="action">
								<a href="javascript:void(0);" class="icon" onclick="globalBrowse.editRow(<?php echo $i; ?>);">
									<com:Icon name="edit" size="16" text="core.edit" />
								</a>
							</td>
							<td class="action">
								<a href="javascript:void(0);" class="icon" onclick="globalBrowse.deleteRow(<?php echo $i; ?>);">
									<com:Icon name="insert" size="16" text="core.insert" />
								</a>
							</td>
						<?php } ?>
						<?php foreach($row AS $key=>$value) { ?>
							<td class="<?php echo $key; ?>">
								<?php if(DataType::getBaseType($model->getTable()->columns[$key]->dbType) == "blob" && $value) { ?>
									<a href="javascript:void(0);" class="icon" onclick="download('<?php echo BASEURL; ?>/row/download', {key: JSON.stringify(keyData[<?php echo $i; ?>]), column: '<?php echo $column; ?>', table: '<?php echo $this->table; ?>', schema: '<?php echo $this->schema; ?>'})">
										<com:Icon name="save" text="core.download" size="16" />
										<?php echo Yii::t('core', 'download'); ?>
									</a>
								<?php } else { ?>
									<span><?php echo is_null($value) ? '<span class="null">NULL</span>' : (Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $this->schema . '.' .  $this->table) ? str_replace(array('<','>'),array('&lt;','&gt;'),$value) : StringUtil::cutText(str_replace(array('<','>'),array('&lt;','&gt;'),$value), 100)); ?></span>
								<?php } ?>
							</td>
							<?php if($model->getIsEditable() && (in_array($key, (array)$model->getTable()->primaryKey) || $model->getTable()->primaryKey === null)) { ?>
								<?php $keyData[$i][$key] = is_null($value) ? null : $value; ?>
							<?php } ?>
						<?php } ?>
					</tr>
					<?php $i++; ?>
				<?php } ?>
			</tbody>
		</table>

	<div class="buttonContainer">
		<?php if ($model->getQueryType() == 'select') { ?>
			<div class="withSelected left">
				<span class="icon">
					<com:Icon name="arrow_turn_090" size="16" />
					<span><?php echo Yii::t('core', 'withSelected'); ?></span>
				</span>
				<a class="icon button" href="javascript:void(0)" onclick="globalBrowse.deleteRows()">
					<com:Icon name="delete" size="16" text="core.delete" />
					<span><?php echo Yii::t('core', 'delete'); ?></span>
				</a>
				<a class="icon button" href="javascript:void(0)" onclick="globalBrowse.exportRows()">
					<com:Icon name="save" size="16" text="core.export" />
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
	<?php echo Yii::t('message', 'emptyResultSet'); ?>
<?php } ?>

<script type="text/javascript">
	globalBrowse.setup();
	AjaxResponse.handle(<?php echo $model->getResponse(); ?>);
</script>