<?php echo CHtml::errorSummary($column, false); ?>
<table class="form" style="float: left; margin-right: 20px">
	<colgroup>
		<col class="col1"/>
		<col class="col2" />
		<col class="col3" />
	</colgroup>
	<tbody>
		<tr>
			<td>
				<?php echo CHtml::activeLabel($column,'COLUMN_NAME'); ?>
			</td>
			<td colspan="2">
				<?php echo CHtml::activeTextField($column, 'COLUMN_NAME'); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo CHtml::activeLabel($column, 'dataType'); ?>
			</td>
			<td colspan="2">
				<?php echo CHtml::activeDropDownList($column, 'dataType', Column::getDataTypes()); ?>
			</td>
		</tr>
		<tr id="<?php echo CHtml::$idPrefix; ?>settingSize">
			<td>
				<?php echo CHtml::activeLabel($column, 'size'); ?>
			</td>
			<td colspan="2">
				<?php echo CHtml::activeTextField($column, 'size'); ?>
			</td>
		</tr>
		<tr id="<?php echo CHtml::$idPrefix; ?>settingScale">
			<td>
				<?php echo CHtml::activeLabel($column, 'scale'); ?>
			</td>
			<td colspan="2">
				<?php echo CHtml::activeTextField($column, 'scale'); ?>
			</td>
		</tr>
		<tr id="<?php echo CHtml::$idPrefix; ?>settingValues">
			<td>
				<?php echo CHtml::activeLabel($column, 'values'); ?>
			</td>
			<td colspan="2">
				<?php echo CHtml::activeTextArea($column, 'values'); ?>
				<div class="small">
					<?php echo Yii::t('core', 'enterOneValuePerLine'); ?>
				</div>
			</td>
		</tr>
		<tr id="<?php echo CHtml::$idPrefix; ?>settingCollation">
			<td>
				<?php echo CHtml::activeLabel($column, 'COLLATION_NAME'); ?>
			</td>
			<td colspan="2">
				<?php echo CHtml::activeDropDownList($column, 'COLLATION_NAME', CHtml::listData($collations, 'COLLATION_NAME', 'COLLATION_NAME', 'collationGroup')); ?>
			</td>
		</tr>
		<tr id="<?php echo CHtml::$idPrefix; ?>settingDefault">
			<td>
				<?php echo CHtml::activeLabel($column, 'COLUMN_DEFAULT'); ?>
			</td>
			<td colspan="2">
				<?php echo CHtml::activeTextField($column, 'COLUMN_DEFAULT'); ?>
				<div class="small" id="<?php echo CHtml::$idPrefix; ?>settingDefaultNullHint">
					<?php echo Yii::t('core', 'leaveEmptyForNull'); ?>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo CHtml::activeLabel($column,'COLUMN_COMMENT'); ?>
			</td>
			<td colspan="2">
				<?php echo CHtml::activeTextField($column, 'COLUMN_COMMENT'); ?>
			</td>
		</tr>
	</tbody>
</table>
<table class="form">
	<colgroup>
		<col class="col1"/>
		<col class="col2" />
		<col class="col3" />
	</colgroup>
	<tbody>
		<tr>
			<td>
				<?php echo Yii::t('core', 'options'); ?>
			</td>
			<td>
				<?php echo CHtml::activeCheckBox($column, 'isNullable'); ?>
				<?php echo CHtml::activeLabel($column, 'isNullable'); ?>
			</td>
			<td>
				<?php echo CHtml::activeCheckBox($column, 'autoIncrement'); ?>
				<?php echo CHtml::activeLabel($column, 'autoIncrement'); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo Yii::t('core', 'attribute'); ?>
				<?php $radio_options = explode('|', CHtml::activeRadioButtonList($column, 'attribute', array(
					'' => Yii::t('core', 'noAttribute'),
					'unsigned' => Yii::t('core', 'unsigned'),
					'unsigned zerofill' => Yii::t('core', 'unsignedZerofill'),
					'on update current_timestamp' => Yii::t('core', 'onUpdateCurrentTimestamp'),
				), array('template' => '{input} {label}', 'separator' => '|'))); ?>
			</td>
			<td colspan="2">
				<?php echo $radio_options[0]; ?>
				<!---
				<?php echo CHtml::activeRadioButton($column, 'attribute', array('value' => '', 'id' => CHtml::$idPrefix . 'Column_attribute_')); ?>
				<?php echo CHtml::label(Yii::t('core', 'noAttribute'), 'Column_attribute_', array('style' => 'font-style: italic')); ?>
				--->
			</td>
		</tr>
		<tr>
			<td />
			<td>
				<?php echo $radio_options[1]; ?>
				<!---
				<?php echo CHtml::activeRadioButton($column, 'attribute', array('value' => 'unsigned', 'id' => CHtml::$idPrefix . 'Column_attribute_unsigned')); ?>
				<?php echo CHtml::label(Yii::t('core', 'unsigned'), 'Column_attribute_unsigned'); ?>
				--->
			</td>
			<td>
				<?php echo $radio_options[2]; ?>
				<!---
				<?php echo CHtml::activeRadioButton($column, 'attribute', array('value' => 'unsigned zerofill', 'id' => CHtml::$idPrefix . 'Column_attribute_unsignedzerofill')); ?>
				<?php echo CHtml::label(Yii::t('core', 'unsignedZerofill'), 'Column_attribute_unsignedzerofill'); ?>
				--->
			</td>
		</tr>
		<tr>
			<td />
			<td colspan="2">
				<?php echo $radio_options[3]; ?>
				<!---
				<?php echo CHtml::activeRadioButton($column, 'attribute', array('value' => 'on update current_timestamp', 'id' => CHtml::$idPrefix . 'Column_attribute_on_update_current_timestamp')); ?>
				<?php echo CHtml::label(Yii::t('core', 'onUpdateCurrentTimestamp'), 'Column_attribute_on_update_current_timestamp'); ?>
				--->
			</td>
		</tr>
		<?php if($column->isNewRecord) { ?>
			<tr id="<?php echo CHtml::$idPrefix; ?>settingSize">
				<td>
					<?php echo Yii::t('core', 'createIndex'); ?>
				</td>
				<td>
					<?php echo CHtml::checkBox('createIndexPrimary', isset($_POST['createIndexPrimary'])); ?>
					<?php echo CHtml::label(Yii::t('core', 'primaryKey'), 'createIndexPrimary', array('disabled' => $table->getHasPrimaryKey())); ?>
				</td>
				<td>
					<?php echo CHtml::checkBox('createIndex', isset($_POST['createIndex'])); ?>
					<?php echo CHtml::label(Yii::t('core', 'index'), 'createIndex'); ?>
				</td>
			</tr>
			<tr id="<?php echo CHtml::$idPrefix; ?>settingScale">
				<td />
				<td>
					<?php echo CHtml::checkBox('createIndexUnique', isset($_POST['createIndexUnique'])); ?>
					<?php echo CHtml::label(Yii::t('core', 'uniqueKey'), 'createIndexUnique'); ?>
				</td>
				<td>
					<?php echo CHtml::checkBox('createIndexFulltext', isset($_POST['createIndexFulltext'])); ?>
					<?php echo CHtml::label(Yii::t('core', 'fulltextIndex'), 'createIndexFulltext'); ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>