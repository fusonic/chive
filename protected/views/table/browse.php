<?php echo CHtml::form(Yii::app()->baseUrl . '/' . str_replace('browse', 'sql', Yii::app()->getRequest()->pathInfo), 'post'); ?>

<div id="deleteRowDialog" title="<?php echo Yii::t('message', 'deleteRows'); ?>" style="display: none">
	<?php echo Yii::t('message', 'doYouReallyWantToDeleteSelectedRows'); ?>
</div>

<?php if($error) { ?>
	<div class="errorSummary">
		<?php echo $error; ?>
	</div>
<?php } ?>

<table style="width: 100%;">
	<tr>
		<td style="width: 80%;">
			<com:application.extensions.CodePress.CodePress language="sql" name="query" width="100%" height="80px" autogrow="true" value={$query} />
			<!---
			<textarea name="query" style="width: 99%; height: 90px;" id="query"><?php echo $query; ?></textarea>
			--->
			<div class="buttons">
				<?php echo CHtml::submitButton('Execute', array('class'=>'icon button execute')); ?>
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
				<span><?php echo Yii::t('database', 'Profiling'); ?></span>
			</a>
			<br/><br/>
			<a class="icon button" href="javascript:void(0);" onclick="$.post(baseUrl + '/ajaxSettings/toggle', {
					name: 'showFullColumnContent',
					scope: 'schema.table.browse',
					object: '<?php echo $this->schema; ?>.<?php echo $this->table; ?>'
				}, function() {
					refresh();
				});">
				<?php if( Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $this->schema . '.' .  $this->table)) {?>
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

<?php if(count($data) > 0) { ?>

	<div class="list">

		<div class="pager top">
			<?php $this->widget('LinkPager',array('pages'=>$pages, 'cssFile'=>false)); ?>
		</div>

		<?php $i = 0; ?>
		<table class="list <?php if($type == 'select' && $table->primaryKey !== null) { ?>addCheckboxes editable<?php } ?>" style="width: auto;" id="browse">
			<colgroup>
				<col class="checkbox" />
				<?php if($type == 'select') { ?>
					<col class="action" />
					<col class="action" />
					<col class="action" />
				<?php } ?>
				<?php foreach ($columns AS $column) { ?>
					<?php echo '<col class="date" />'; ?>
				<?php } ?>
			</colgroup>
			<thead>
				<tr>
					<?php if($type == 'select') { ?>
						<th><input type="checkbox" /></th>
						<th></th>
						<th></th>
						<th></th>
					<?php } ?>
					<?php foreach ($columns AS $column) { ?>
						<th><?php echo ($type == 'select' ? $sort->link($column) : $column); ?></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data AS $row) { ?>
					<tr>
						<?php if($type == 'select') { ?>
							<td>
								<input type="checkbox" name="browse[]" value="row_<?php echo $i; ?>" />
							</td>
							<td class="action">
								<a href="javascript:void(0);" class="icon" onclick="tableBrowse.deleteRow(<?php echo $i; ?>);">
									<com:Icon name="delete" size="16" text="core.delete" />
								</a>
							</td>
							<td class="action">
								<a href="javascript:void(0);" class="icon" onclick="tableBrowse.editRow(<?php echo $i; ?>);">
									<com:Icon name="edit" size="16" text="core.edit" />
								</a>
							</td>
							<td class="action">
								<a href="javascript:void(0);" class="icon" onclick="tableBrowse.deleteRow(<?php echo $i; ?>);">
									<com:Icon name="insert" size="16" text="core.insert" />
								</a>
							</td>
						<?php } ?>
						<?php foreach($row AS $key=>$value) { ?>
							<td class="<?php echo $key; ?>">
								<span><?php echo is_null($value) ? '<span class="null">NULL</span>' : (Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $this->schema . '.' .  $this->table) ? str_replace(array('<','>'),array('&lt;','&gt;'),$value) : StringUtil::cutText(str_replace(array('<','>'),array('&lt;','&gt;'),$value), 100)); ?></span>
							</td>

							<?php if($type == 'select' && $table->primaryKey !== null && in_array($key, (array)$table->primaryKey)) { ?>
								<?php $keyData[$i][$key] = $value; ?>
							<?php } ?>

						<?php } ?>
					</tr>
					<?php $i++; ?>
				<?php } ?>
			</tbody>
		</table>

		<?php if ($type == 'select') { ?>
			<div class="withSelected">
				<span class="icon">
					<com:Icon name="arrow_turn_090" size="16" />
					<span><?php echo Yii::t('core', 'withSelected'); ?></span>
				</span>
				<a class="icon button" href="javascript:void(0)" onclick="tableBrowse.deleteRows()">
					<com:Icon name="delete" size="16" text="core.delete" />
					<span><?php echo Yii::t('core', 'delete'); ?></span>
				</a>
				<a class="icon button" href="javascript:void(0)" onclick="tableBrowse.exportRows()">
					<com:Icon name="save" size="16" text="core.export" />
					<span><?php echo Yii::t('core', 'export'); ?></span>
				</a>
			</div>
			<?php if ($table->primaryKey !== null) { ?>
				<script type="text/javascript">
					var keyData = <?php echo json_encode($keyData); ?>;
				</script>
			<?php } ?>
		<?php } ?>

		<div class="pager bottom">
			<?php $this->widget('LinkPager',array('pages'=>$pages, 'cssFile'=>false)); ?>
		</div>

	</div>

<?php } elseif($this->isSent) { ?>
	Es wurden keine Enträge gefunden!
<?php } ?>

<script type="text/javascript">
	tableBrowse.setup();
	AjaxResponse.handle(<?php echo $response; ?>);
</script>