<?php echo CHtml::form(Yii::app()->baseUrl . '/' . str_replace('browse', 'sql', Yii::app()->getRequest()->pathInfo), 'post'); ?>

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
			<a class="icon" href="javascript:void(0);">
				<?php $this->widget('Icon', array(
					'name'=>'bookmark',
					'size'=>16,
					'htmlOptions'=>array(
						'onclick'=>'addBookmark("' . $this->schema . '", query.getCode());'
					),
				)); ?>
				<span><?php echo Yii::t('core', 'bookmark'); ?></span>
			</a>
			<br/><br/>
			<a class="icon" href="javascript:void(0);">
				<?php $this->widget('Icon', array(
					'name'=>'chart',
					'size'=>16,
					'htmlOptions'=>array(
						'onclick'=>'toggleProfiling();'
					),
				)); ?>
				<span><?php echo Yii::t('database', 'profiling'); ?></span>
			</a>
		</td>
	</tr>
</table>

<div class="buttons">
	<?php echo CHtml::submitButton('Execute'); ?>
</div>


<?php echo CHtml::endForm(); ?>

<?php if(count($data)) { ?>

	<div class="pager top">
	<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
	</div>

	<br/>

	<table class="list" style="width: auto;" id="browse">
		<colgroup>
			<col class="action" />
			<col class="action" />
			<?php foreach ($columns AS $column) { ?>
				<?php echo '<col class="date" />'; ?>
			<?php } ?>
		</colgroup>
		<thead>
			<tr>
				<th></th>
				<th></th>
				<?php foreach ($columns AS $column) { ?>
					<th><?php echo $sort->link($column); ?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data AS $row) { ?>
				<tr>
					<td>
						<a href="" class="icon">
							<com:Icon name="edit" size="16" text="core.edit" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0);" class="icon" onclick="deleteRow($(this).parent().parent());">
							<com:Icon name="delete" size="16" text="core.edit" />
						</a>
					</td>
					<?php foreach($row AS $key=>$value) { ?>
						<td>
							<?php echo (is_null($value) ? '<i>NULL</i>' : substr(str_replace(array('<','>'),array('&lt;','&gt;'),$value), 0, 100)); ?>
						</td>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>

	<div class="pager bottom">
	<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
	</div>

<?php }  elseif($this->isSent) { ?>
	Es wurden keine Enträge gefunden!
<?php } ?>