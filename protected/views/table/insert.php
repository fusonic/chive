<!---
<?php echo CHtml::form(str_replace('browse', 'sql', Yii::app()->request->url), 'post'); ?>

<?php echo CHtml::hiddenField('sent', 'true'); ?>

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
			<?php foreach($table->columns AS $column) { ?>
				<tr>
					<td><?php echo $column->COLUMN_NAME; ?></td>
					<td><?php echo $column->COLUMN_TYPE; ?></td>
					<td><?php echo CHtml::dropDownList('function','',$functions); ?></td>
					<td><?php echo ($column->IS_NULLABLE != "NO" ? CHtml::checkBox($column->COLUMN_NAME.'[null]', true) : ''); ?></td>
					<td>
						<com:InputField column={$column} />
					</td>
				</tr>
			<?php } ?>
	</tbody>
</table>

<div class="buttons">
	<?php echo CHtml::submitButton('Execute'); ?>
</div>

<?php echo CHtml::endForm(); ?>
--->

<h2>Create New Post</h2>

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

<?php echo CHtml::submitButton('test', array('name'=>'submitRow')); ?>

<?php echo CHtml::endForm(); ?>