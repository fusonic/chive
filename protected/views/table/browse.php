<?php echo CHtml::form(Yii::app()->baseUrl . '/' . str_replace('browse', 'sql', Yii::app()->getRequest()->pathInfo), 'post'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery/jquery.jeditable.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/views/table/browse.js', CClientScript::POS_HEAD); ?>

<div id="deleteRowDialog" title="<?php echo Yii::t('core', 'confirm'); ?>" style="display: none">
	<?php echo Yii::t('message', 'doYouReallyWantToDeleteRow'); ?>
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
			<a class="icon" href="javascript:void(0);" onclick="">
				<com:Icon size="16" name="chart" />
				<span><?php echo Yii::t('database', 'profiling'); ?></span>
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
	<table class="list addCheckboxes" style="width: auto;" id="browse">
		<colgroup>
			<!---<col class="action" /> --->
			<col class="action" />
			<?php foreach ($columns AS $column) { ?>
				<?php echo '<col class="date" />'; ?>
			<?php } ?>
		</colgroup>
		<thead>
			<tr>
				<!---<th></th> --->
				<th></th>
				<?php foreach ($columns AS $column) { ?>
					<th><?php echo $sort->link($column); ?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data AS $row) { ?>
				<tr id="row_<?php echo $i; ?>">
					<!---
					<td>
						<a href="" class="icon">
							<com:Icon name="edit" size="16" text="core.edit" />
						</a>
					</td>
					--->
					<td>
						<a href="javascript:void(0);" class="icon" onclick="tableBrowse.deleteRow(<?php echo $i; ?>);">
							<com:Icon name="delete" size="16" text="core.delete" />
						</a>
					</td>
					<?php foreach($row AS $key=>$value) { ?>
						<td class="<?php echo $key; ?>"><?php echo (is_null($value) ? '<i>NULL</i>' : substr(str_replace(array('<','>'),array('&lt;','&gt;'),$value), 0, 100)); ?></td>
					<?php } ?>
				</tr>
				<?php $i++; ?>
			<?php } ?>
		</tbody>
	</table>
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

		var keyData = new Array();

		<?php foreach($data AS $row) { ?>
			keyData.push({
			<?php foreach((array)$table->primaryKey AS $primaryKey) { ?>
				<?php echo $primaryKey; ?>: '<?php echo $row[$primaryKey]; ?>',
			<?php } ?>
			});
		<?php } ?>

		var rowData = new Array();

		<?php foreach($data AS $row) { ?>
			rowData.push({
			<?php foreach($row AS $key=>$value) { ?>
				<?php echo $key; ?>: '<?php echo $value; ?>',
			<?php } ?>
			});
		<?php } ?>

	</script>

	<div class="pager bottom">
	<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
	</div>

<?php }  elseif($this->isSent) { ?>
	Es wurden keine Enträge gefunden!
<?php } ?>