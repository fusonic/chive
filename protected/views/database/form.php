<?php echo CHtml::form('', 'post', array('id'=>'databaseForm')); ?>
	<h1><% if($database->isNewRecord): %>Add a new database<% else: %>Edit database<% endif %></h1>
	<?php echo CHtml::errorSummary($database, false); ?>
	<fieldset style="float: left; width: 200px">
		<legend><%= CHtml::activeLabel($database,'SCHEMA_NAME') %></legend>
		<% echo CHtml::activeTextField($database, 'SCHEMA_NAME', array('disabled' => ($database->isNewRecord ? '' : 'disabled'))) %>
	</fieldset>
	<fieldset style="float: left; width: 200px">
		<legend><%= CHtml::activeLabel($database,'COLLATION_NAME') %></legend>
		<% echo CHtml::activeDropDownList($database, 'DEFAULT_COLLATION_NAME', CHtml::listData($collations, 'COLLATION_NAME', 'COLLATION_NAME', 'collationGroup')) %>
	</fieldset>
	<div style="clear: left; padding-top: 5px">
		<?php echo CHtml::submitButton(Yii::t('action', ($database->isNewRecord ? 'create' : 'save')), array('class'=>'icon save')); ?>
		<?php echo CHtml::button(Yii::t('action', 'cancel'), array('class'=>'icon delete', 'onclick'=>'$("#addDatabaseForm").slideUp(function() {$("#addDatabaseForm")[0].reset();});')); ?>
	</div>
</form>