<?php CHtml::generateRandomIdPrefix(); ?>

<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		<?php echo Yii::t('core', ($schema->isNewRecord ? 'addSchemaSpecificPrivileges' : 'editSchemaSpecificPrivileges')); ?>
	</h1>
	<?php echo CHtml::errorSummary($schema, false); ?>
	<table class="form">
		<colgroup>
			<col class="col1"/>
			<col class="col2" />
			<col class="col3" />
		</colgroup>
		<tbody>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($schema, 'Db'); ?>
				</td>
				<td colspan="2">
					<?php if($schema->isNewRecord) { ?>
						<?php echo CHtml::activeDropDownList($schema, 'Db', $schemata); ?>
					<?php } else { ?>
						<?php echo CHtml::activeTextField($schema, 'Db', array('disabled' => true)); ?>
					<?php } ?>
				</td>
			</tr>
		</tbody>
	</table>
	<div style="overflow: hidden">
		<fieldset style="float: left">
			<legend><?php echo Yii::t('core', 'data'); ?></legend>
			<?php foreach(array_keys(SchemaPrivilege::getAllPrivileges('data')) AS $priv) { ?>
				<?php echo CHtml::checkBox('SchemaPrivilege[Privileges][' . $priv . ']', $schema->checkPrivilege($priv)); ?>
				<?php echo CHtml::label($priv, 'SchemaPrivilege_Privileges_' . $priv); ?><br />
			<?php } ?>
		</fieldset>
		<fieldset style="float: left; margin-left: 10px">
			<legend><?php echo Yii::t('core', 'structure'); ?></legend>
			<?php foreach(array_keys(SchemaPrivilege::getAllPrivileges('structure')) AS $priv) { ?>
				<?php echo CHtml::checkBox('SchemaPrivilege[Privileges][' . $priv . ']', $schema->checkPrivilege($priv)); ?>
				<?php echo CHtml::label($priv, 'SchemaPrivilege_Privileges_' . $priv); ?><br />
			<?php } ?>
		</fieldset>
		<fieldset style="float: left; margin-left: 10px">
			<legend><?php echo Yii::t('core', 'administration'); ?></legend>
			<?php foreach(array_keys(SchemaPrivilege::getAllPrivileges('administration')) AS $priv) { ?>
				<?php echo CHtml::checkBox('SchemaPrivilege[Privileges][' . $priv . ']', $schema->checkPrivilege($priv)); ?>
				<?php echo CHtml::label($priv, 'SchemaPrivilege_Privileges_' . $priv); ?><br />
			<?php } ?>
		</fieldset>
	</div>
	<div class="buttonContainer">
		<a href="javascript:void(0)" onclick="$('#<?php echo CHtml::$idPrefix; ?>').submit()" class="icon button">
			<?php echo Html::icon('save'); ?>
			<span><?php echo Yii::t('core', 'save'); ?></span>
		</a>
		<a href="javascript:void(0)" onclick="$('#<?php echo CHtml::$idPrefix; ?>').slideUp(500, function() { $(this).parents('tr').remove(); })" class="icon button">
			<?php echo Html::icon('delete'); ?>
			<span><?php echo Yii::t('core', 'cancel'); ?></span>
		</a>
	</div>
</form>