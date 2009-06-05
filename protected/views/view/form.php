<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		<?php echo Yii::t('database', ($view->isNewRecord ? 'addView' : 'editView')); ?>
	</h1>
	<?php echo CHtml::errorSummary($view, false); ?>
	<table class="form">
		<colgroup>
			<col class="col1"/>
			<col class="col2" />
			<col class="col3" />
		</colgroup>
		<tbody>
			<tr>
				<td colspan="3">
					<?php echo CHtml::textArea('query', $query, array('style' => 'width: 600px; height: 200px')); ?>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="buttonContainer">
		<a href="javascript:void(0)" onclick="$('#<?php echo CHtml::$idPrefix; ?>').submit()" class="icon button">
			<com:Icon name="execute" size="16" />
			<span><?php echo Yii::t('action', 'execute'); ?></span>
		</a>
		<a href="javascript:void(0)" onclick="$('#<?php echo CHtml::$idPrefix; ?>').slideUp(500, function() { $(this).parents('tr').remove(); })" class="icon button">
			<com:Icon name="delete" size="16" />
			<span><?php echo Yii::t('action', 'cancel'); ?></span>
		</a>
	</div>
</form>