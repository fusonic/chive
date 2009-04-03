<?php echo CHtml::form(str_replace('browse', 'sql', Yii::app()->request->url), 'post'); ?>

<com:application.extensions.CodePress.CodePress language="sql" name="sql" width="100%" height="80px" autogrow="true" value={$sql} />

<div class="buttons">
	<?php echo CHtml::submitButton('Execute'); ?>
</div>

<?php echo CHtml::endForm(); ?>
<!---
<div class="pager top">
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div>
--->

<table class="list addCheckboxes" style="width: auto;" id="browse">
	<thead>
		<tr>
			<th></th>
			<th></th>
			<?php foreach ($columns AS $column) { ?>
			<th>
				<?php echo $column; ?>
			</th>
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
					<a href="" class="icon">
						<com:Icon name="delete" size="16" text="core.edit" />
					</a>
				</td>
				<?php foreach($row AS $cell) { ?>
					<td>
						<?php echo substr(str_replace(array('<','>'),array('&lt;','&gt;'),$cell), 0, 100); ?>
					</td>
				<?php } ?>
			</tr>
		<?php } ?>
	</tbody>
</table>

<div class="pager bottom">
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div>