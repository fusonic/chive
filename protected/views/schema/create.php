<div class="yiiForm">
<?php echo CHtml::form(); ?>

<?php echo CHtml::errorSummary($schema); ?>

<div class="simple">
<?php echo CHtml::activeLabel($schema,'SCHEMA_NAME'); ?>
<?php echo CHtml::activeTextField($schema,'SCHEMA_NAME'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabel($schema,'DEFAULT_COLLATION_NAME'); ?>
<% echo CHtml::dropDownList('test', null, CHtml::listData($collations, 'COLLATION_NAME', 'COLLATION_NAME', 'collationGroup')) %>
</div>

<?php echo CHtml::submitButton('Create'); ?>
</div>

</form>
</div><!-- yiiForm -->