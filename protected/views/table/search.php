<h2><?php Yii::t('core', 'search'); ?></h2>

<div class="form">
<?php echo CHtml::form(); ?>

<table class="list">
	<colgroup>
		<col style="width: 100px; font-weight: bold;" />
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
		<?php $tabIndex = 10; ?>
		<?php foreach($row->getMetaData()->tableSchema->columns AS $column) { ?>
			<tr>
				<td><?php echo $column->name; ?></td>
				<td><?php echo $column->dbType; ?></td>
				<td><?php echo CHtml::dropDownList('operator['.$column->name.']','', $operators); ?></td>
				<td>
					<?php echo CHtml::activeTextField($row, $column->name, array('class'=>'text', 'tabIndex'=>$tabIndex)); ?>
				</td>
			</tr>
			<?php $tabIndex++; ?>
		<?php } ?>
	</tbody>
</table>

<div class="buttons">
	<a href="javascript:void(0);" onclick="$('form').submit();" class="icon button">
		<com:Icon name="search" size="16" text="core.insert" />
		<span><?php echo Yii::t('core', 'search'); ?></span>
	</a>
</div>

<?php echo CHtml::endForm(); ?>