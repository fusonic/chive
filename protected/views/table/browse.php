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
		</td>
		<td style="vertical-align: top; padding: 10px;">
			<a class="icon" href="javascript:void(0);" onclick="Bookmark.add('<?php echo $this->schema; ?>', query.getCode());">
				<com:Icon size="16" name="bookmark_add" />
				<span><?php echo Yii::t('core', 'bookmark'); ?></span>
			</a>
			<br/><br/>
			<a class="icon" href="javascript:void(0);" onclick="Profiling.toggle();">
				<com:Icon size="16" name="chart" />
				<span><?php echo Yii::t('database', 'toggleProfiling'); ?></span>
			</a>
			<br/><br/>
			<a class="icon" href="javascript:void(0);" onclick="$.post(baseUrl + '/ajaxSettings/toggle', {
				name: 'showFullColumnContent',
				scope: 'schema.table.browse',
				object: '<?php echo $this->schema; ?>.<?php echo $this->table; ?>'
				}, function() {
					reload();
				});;">
				<com:Icon size="16" name="chart" />
				<?php if( Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $this->schema . '.' .  $this->table)) {?>
					<span><?php echo Yii::t('database', 'cutColumnContent'); ?></span>
				<?php } else { ?>
					<span><?php echo Yii::t('database', 'showFullColumnContent'); ?></span>
				<?php } ?>
			</a>
		</td>
	</tr>
</table>

<div class="buttons">
	<?php echo CHtml::submitButton('Execute'); ?>
</div>


<?php echo CHtml::endForm(); ?>

<?php if(count($data) > 0) { ?>

	<div class="pager top">
	<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
	</div>

	<br/>

	<?php $i = 0; ?>
	<table class="list <?php if($type == 'select') { ?>addCheckboxes editable<?php } ?>" style="width: auto;" id="browse">
		<colgroup>
			<?php if($type == 'select') { ?>
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
				<tr id="row_<?php echo $i; ?>">
					<?php if($type == 'select') { ?>
						<td class="action">
							<a href="javascript:void(0);" class="icon" onclick="tableBrowse.deleteRow(<?php echo $i; ?>);">
								<com:Icon name="delete" size="16" text="core.delete" />
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
							<?php echo is_null($value) ? '<span class="null">NULL</span>' : (Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $this->schema . '.' .  $this->table) ? str_replace(array('<','>'),array('&lt;','&gt;'),$value) : StringUtil::cutText(str_replace(array('<','>'),array('&lt;','&gt;'),$value), 100)); ?>
						</td>
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
		<a class="icon" href="javascript:void(0)" onclick="tableBrowse.deleteRows()">
			<com:Icon name="delete" size="16" />
			<span><?php echo Yii::t('core', 'delete'); ?></span>
		</a>
	</div>
	<script type="text/javascript">

		var tableData = <?php echo json_encode($table); ?>;
		var rowData = <?php echo json_encode($data); ?>;

	</script>
	<?php } ?>

	<div class="pager bottom">
	<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
	</div>

<?php } elseif($this->isSent) { ?>
	Es wurden keine Enträge gefunden!
<?php } ?>

<script type="text/javascript">
	tableBrowse.setup();
	AjaxResponse.handle(<?php echo $response; ?>);
</script>