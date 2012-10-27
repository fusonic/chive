<h2><?php Yii::t('core', 'search'); ?></h2>

<div class="form">
<?php echo CHtml::form('', 'post', array('id' => 'searchForm')); ?>

<table class="list">
	<colgroup>
		<col style="width: 100px; font-weight: bold;" />
		<col class="type" />
		<col class="operator" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th><?php echo Yii::t('core', 'field'); ?></th>
			<th><?php echo Yii::t('core', 'type'); ?></th>
			<th><?php echo Yii::t('core', 'operator'); ?></th>
			<th><?php echo Yii::t('core', 'value'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php $tabIndex = 10; ?>
		<?php foreach($row->getMetaData()->tableSchema->columns AS $column) { ?>
			<tr>
				<td><?php echo CHtml::encode($column->name); ?></td>
				<td><?php echo strlen($column->dbType) > 50 ? substr($column->dbType, 0, 50) . "..." : $column->dbType ?></td>
				<td><?php echo CHtml::dropDownList('operator['.$column->name.']','', $operators); ?></td>
				<td>
					<?php #echo CHtml::activeTextField($row, $column->name, array('class'=>'text', 'tabIndex'=>$tabIndex)); ?>
					<?php echo CHtml::textField('Row[' . $column->name . ']', '', array('class'=>'text', 'tabIndex'=>$tabIndex)); ?>
				</td>
			</tr>
			<?php $tabIndex++; ?>
		<?php } ?>
	</tbody>
</table>

<div class="buttons">
	<a href="javascript:void(0);" onclick="$('form').submit();" class="icon button">
		<?php echo Html::icon('search', 16, false, 'core.insert'); ?>
		<span><?php echo Yii::t('core', 'search'); ?></span>
	</a>
</div>

<script type="text/javascript">
$('#searchForm').ajaxForm({
	success: function(responseText, statusText) {
		AjaxResponse.handle(responseText);
		$('div.ui-layout-center').html(responseText);
		init();
	}
});

$('#searchForm').keydown(function(e) {
	if(e.which == 13)
	{
		$(this).submit();
	}	
});

$('table.list input:first').focus();

</script>

<input type="submit" name="submit" style="display: none;" />

<?php echo CHtml::endForm(); ?>