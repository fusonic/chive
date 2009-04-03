<span id="<%= $helperId %>" />

<?php if($isSubmitted && !$database->isNewRecord): ?>
	<script type="text/javascript">
	$("#<%= $helperId %>").parents("tr").prev().effect("highlight", {}, 2000);
	$("#<%= $helperId %>").parents("tr").prev().find("td dfn.collation").html("<%= $database->DEFAULT_COLLATION_NAME %>");
	$("#<%= $helperId %>").parents("tr").prev().find("td dfn.collation").attr("title", "<%= $database->collation->definition %>");
	$("#<%= $helperId %>").parent().slideUp(500, function() {
		$("#<%= $helperId %>").parents("tr").remove();
	});
	</script>
<?php endif; ?>

<?php echo CHtml::form('', 'post'); ?>
	<h1><% if($database->isNewRecord): %>Add a new database<% else: %>Edit database<% endif %></h1>
	<?php echo CHtml::errorSummary($database, false); ?>
	<fieldset style="float: left; width: 200px">
		<legend><?php echo CHtml::activeLabel($database,'SCHEMA_NAME'); ?></legend>
		<?php echo CHtml::activeTextField($database, 'SCHEMA_NAME', ($database->isNewRecord ? array() : array('disabled' =>  true))); ?>
	</fieldset>
	<fieldset style="float: left; width: 200px">
		<legend><?php echo CHtml::activeLabel($database,'COLLATION_NAME'); ?></legend>
		<?php echo CHtml::activeDropDownList($database, 'DEFAULT_COLLATION_NAME', CHtml::listData($collations, 'COLLATION_NAME', 'COLLATION_NAME', 'collationGroup')); ?>
	</fieldset>
	<div style="clear: left; padding-top: 5px">
		<?php echo CHtml::submitButton(Yii::t('action', ($database->isNewRecord ? 'create' : 'save')), array('class'=>'icon save')); ?>
		<?php echo CHtml::button(Yii::t('action', 'cancel'), array('class'=>'icon delete', 'onclick'=>'$(this.form).slideUp(500, function() { $(this).parents("tr").remove(); })')); ?>
	</div>
</form>