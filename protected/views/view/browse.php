<?php echo CHtml::form(Yii::app()->baseUrl . '/' . str_replace('browse', 'sql', Yii::app()->getRequest()->pathInfo), 'post'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/views/table/browse.js', CClientScript::POS_END); ?>

<table style="width: 100%;">
	<tr>
		<td style="width: 80%;">
			<com:application.extensions.CodePress.CodePress language="sql" name="query" width="100%" height="80px" autogrow="true" value={$query} />
		</td>
		<td style="vertical-align: top; padding: 10px;">
			<a class="icon" href="javascript:void(0);" onclick="Bookmark.add('<?php echo $this->schema; ?>', query.getCode());">
				<?php echo Html::icon('bookmark_add'); ?>
				<span><?php echo Yii::t('core', 'bookmark'); ?></span>
			</a>
			<br/><br/>
			<a class="icon" href="javascript:void(0);" onclick="Profiling.toggle();">
				<?php echo Html::icon('chart'); ?>
				<span><?php echo Yii::t('core', 'toggleProfiling'); ?></span>
			</a>
			<br/><br/>
			<a class="icon" href="javascript:alert('OK'); javascript:void(0);" onclick="alert('OK'); $.post(baseUrl + '/ajaxSettings/toggle', {
					name: 'showFullColumnContent',
					scope: 'schema.table.browse',
					object: '<?php echo $this->schema; ?>.<?php echo $this->view; ?>'
				};, function() {
					reload();
				});">
				<?php if( Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $this->schema . '.' .  $this->view)) {?>
					<?php echo Html::icon('square_green'); ?>
					<span><?php echo Yii::t('core', 'cutColumnContent'); ?></span>
				<?php } else { ?>
					<?php echo Html::icon('square_red'); ?>
					<span><?php echo Yii::t('core', 'showFullColumnContent'); ?></span>
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
	<?php $this->widget('LinkPager',array('pages'=>$pages)); ?>
	</div>

	<br/>

	<?php $i = 0; ?>
	<table class="list" id="browse">
		<colgroup>
			<?php foreach ($columns AS $column) { ?>
				<?php echo '<col />'; ?>
			<?php } ?>
		</colgroup>
		<thead>
			<tr>
				<?php foreach ($columns AS $column) { ?>
					<th><?php echo ($type == 'select' ? $sort->link($column) : $column); ?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data AS $row) { ?>
				<tr id="row_<?php echo $i; ?>">
					<?php foreach($row AS $key=>$value) { ?>
						<td class="<?php echo $key; ?>">
							<?php echo is_null($value) ? '<span class="null">NULL</span>' : (Yii::app()->user->settings->get('showFullColumnContent', 'schema.table.browse', $this->schema . '.' .  $this->view) ? str_replace(array('<','>'),array('&lt;','&gt;'),$value) : StringUtil::cutText(str_replace(array('<','>'),array('&lt;','&gt;'),$value), 100)); ?>
						</td>
					<?php } ?>
				</tr>
				<?php $i++; ?>
			<?php } ?>
		</tbody>
	</table>

	<div class="pager bottom">
	<?php $this->widget('LinkPager',array('pages'=>$pages)); ?>
	</div>

<?php }  elseif($this->isSent) { ?>
	<?php Yii::t('core', 'noEntriesFound'); ?>
<?php } ?>

<script type="text/javascript">
	AjaxResponse.handle(<?php echo $response; ?>);
</script>