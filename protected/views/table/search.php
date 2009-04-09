<h2><?php Yii::t('core', 'search'); ?></h2>

<div class="form">
<?php echo CHtml::form(); ?>

<table class="list">
	<colgroup>
		<col />
		<col class="type" />
		<col class="operator" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th><?php echo Yii::t('database', 'field'); ?></th>
			<th><?php echo Yii::t('database', 'type'); ?></th>
			<th><?php echo Yii::t('database', 'operator'); ?></th>
			<th><?php echo Yii::t('core', 'value'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($row->getMetaData()->tableSchema->columns AS $column) { ?>
			<tr>
				<td><?php echo $column->name; ?></td>
				<td><?php echo $column->dbType; ?></td>
				<td><?php echo CHtml::dropDownList('operator['.$column->name.']','', $operators); ?></td>
				<td>
					<?php echo CHtml::activeTextField($row, $column->name, array('class'=>'text')); ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<?php echo CHtml::submitButton(Yii::t('core', 'search'), array('name'=>'submitSearch')); ?>

<?php echo CHtml::endForm(); ?>