<div class="yiiForm">
<?php echo CHtml::form(); ?>

<?php echo CHtml::errorSummary($database); ?>

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