<?php echo CHtml::form(); ?>


<table>
	<tr>
		<td>
			<?php echo CHtml::dropDownList('tables', '', CHtml::listData($tables, 'TABLE_NAME', 'TABLE_NAME'), array('multiple'=>'multiple', 'size' => 20, 'style'=>'width: 300px')); ?>
		</td>
		<td>
			settings
		</td>
	</tr>
</table>

<?php echo CHtml::submitButton(Yii::t('core', 'export')); ?>

<?php echo CHtml::endForm(); ?>
