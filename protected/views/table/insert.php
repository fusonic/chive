<div class="form">
<?php echo CHtml::form(); ?>

<?php echo CHtml::errorSummary($row); ?>

<table class="list">
	<colgroup>
		<col />
		<col class="type" />
		<col />
		<col class="checkbox" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th><?php echo Yii::t('database', 'field'); ?></th>
			<th><?php echo Yii::t('database', 'type'); ?></th>
			<th><?php echo Yii::t('database', 'function'); ?></th>
			<th><?php echo Yii::t('database', 'null'); ?></th>
			<th><?php echo Yii::t('core', 'value'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($row->getMetaData()->tableSchema->columns AS $column) { ?>
			<tr>
				<td><?php echo $column->name; ?></td>
				<td><?php echo $column->dbType; ?></td>
				<td><?php echo CHtml::dropDownList('function','',$functions); ?></td>
				<td><?php echo ($column->allowNull ? CHtml::checkBox($column->name.'[null]', true) : ''); ?></td>
				<td>
					<com:InputField row={$row} column={$column} />
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<?php echo CHtml::submitButton(Yii::t('core', 'insert'), array('name'=>'submitRow')); ?>

<?php echo CHtml::endForm(); ?>