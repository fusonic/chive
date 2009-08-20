<div class="form">
<?php echo CHtml::form(); ?>

<?php echo CHtml::errorSummary($row); ?>

<table class="list">
	<colgroup>
		<col style="width: 100px;" />
		<col class="type" />
		<col style="width: 100px;" />
		<col class="checkbox" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th><?php echo Yii::t('core', 'field'); ?></th>
			<th><?php echo Yii::t('core', 'type'); ?></th>
			<th><?php echo Yii::t('core', 'function'); ?></th>
			<th><?php echo Yii::t('core', 'null'); ?></th>
			<th><?php echo Yii::t('core', 'value'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($row->getMetaData()->tableSchema->columns AS $column) { ?>
			<tr>
				<td style="font-weight: bold;"><?php echo $column->name; ?></td>
				<td><?php echo $column->dbType; ?></td>
				<td><?php echo CHtml::dropDownList($column->name . '[function]','',$functions); ?></td>
				<td class="center">
					<?php echo ($column->allowNull ? CHtml::checkBox($column->name.'[null]', true) : ''); ?>
				</td>
				<td>
					<?php $this->widget('InputField', array('row' => $row, 'column'=>$column, 'htmlOptions'=>array(
						'onfocus' => '$("#'.$column->name.'_null").attr("checked", "").change();',
					))); ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<div class="buttons">
	<a href="javascript:void(0);" onclick="$('form').submit();" class="icon button">
		<?php echo Html::icon('add', 16, false, 'core.insert'); ?>
		<span><?php echo Yii::t('core', 'insert'); ?></span>
	</a>
</div>

<?php echo CHtml::endForm(); ?>