<?php CHtml::generateRandomIdPrefix(); ?>

<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		<?php echo Yii::t('core', ($index->isNewRecord ? 'addIndex' : 'editIndex')); ?>
	</h1>
	<?php echo CHtml::errorSummary($index, false); ?>
	<table class="form">
		<colgroup>
			<col class="col1"/>
			<col class="col2" />
			<col class="col3" />
		</colgroup>
		<tbody>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($index,'INDEX_NAME'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeTextField($index, 'INDEX_NAME', ($index->getType() == 'PRIMARY' && !$index->getIsNewRecord() ? array('readonly' => true) : '')); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($index, 'type'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeDropDownList($index, 'type', $indexTypes, ($index->getIsNewRecord() ? array() : array('disabled' => true))); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo Yii::t('core', 'columns'); ?>
				</td>
				<td colspan="2">
					<table class="formList" id="<?php echo CHtml::$idPrefix; ?>columns">
						<colgroup>
							<col />
							<col style="width: 50px" />
							<col class="action" />
							<col class="action" />
						</colgroup>
						<thead>
							<tr>
								<th><?php echo Yii::t('core', 'name'); ?></th>
								<th colspan="2"><?php echo Yii::t('core', 'size'); ?></th>
							</tr>
						</thead>
						<tbody class="noItems">
							<tr>
								<td colspan="3">
									<?php echo Yii::t('core', 'noColumnsAddedYet'); ?>
								</td>
							</tr>
						</tbody>
						<tbody class="content">
							<?php foreach($index->columns AS $column) { ?>
								<tr>
									<td>
										<input type="hidden" name="columns[]" value="<?php echo $column->COLUMN_NAME; ?>" />
										<?php echo $column->COLUMN_NAME; ?>
									</td>
									<td>
										<?php echo CHtml::textField('keyLengths[' . $column->COLUMN_NAME . ']', $column->SUB_PART, array('class' => 'indexSize')); ?>
									</td>
									<td>
										<a href="javascript:void(0)" class="icon">
											<?php echo Html::icon('arrow_move', 16, false, 'core.move'); ?>
										</a>
									</td>
									<td>
										<a href="javascript:void(0)" onclick="indexForm.removeColumn('<?php echo CHtml::$idPrefix; ?>', this)" class="icon">
											<?php echo Html::icon('delete', 16, false, 'core.remove'); ?>
										</a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="3">
									<?php echo CHtml::dropDownList('addColumn', null, $addColumnData); ?>
								</th>
							</tr>
						</tfoot>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<?php echo Html::submitFormArea(); ?>
</form>

<script type="text/javascript">
indexForm.create('<?php echo CHtml::$idPrefix; ?>');
</script>