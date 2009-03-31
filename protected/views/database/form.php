<?php echo CHtml::form(null, "post", array('id'=>'databaseForm')); ?>
	<h1>Add a new database</h1>
	<?php echo CHtml::errorSummary($database); ?>
	<fieldset style="float: left; width: 200px">
		<legend><%= CHtml::activeLabel($database,'SCHEMA_NAME') %></legend>
		<% echo CHtml::activeTextField($database, 'SCHEMA_NAME') %>
	</fieldset>
	<fieldset style="float: left; width: 200px">
		<legend><%= CHtml::activeLabel($database,'COLLATION_NAME') %></legend>
		<% echo CHtml::activeDropDownList($database, 'DEFAULT_COLLATION_NAME', CHtml::listData($collations, 'COLLATION_NAME', 'COLLATION_NAME', 'collationGroup')) %>
	</fieldset>
	<div style="clear: left; padding-top: 5px">
		<?php echo CHtml::submitButton('Create', array('class'=>'icon save')); ?>
		<?php echo CHtml::button('Cancel', array('class'=>'icon delete', 'onclick'=>'$("#addDatabaseForm").slideUp(function() {$("#addDatabaseForm")[0].reset();});')); ?>
	</div>
</form>

<!---
<div class="yiiForm">
<?php echo CHtml::form(); ?>

<div class="simple">
<?php echo CHtml::activeLabel($database,'SCHEMA_NAME'); ?>
<?php echo CHtml::activeTextField($database,'SCHEMA_NAME'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabel($database,'DEFAULT_COLLATION_NAME'); ?>
<% echo CHtml::dropDownList('test', null, CHtml::listData($collations, 'COLLATION_NAME', 'COLLATION_NAME', 'collationGroup')) %>
</div>

<?php echo CHtml::submitButton('Create'); ?>
</div>

</form>
</div><!-- yiiForm -->
--->