<com:Select items={$languages} htmlOptions={array('style'=>'display: none;', 'class'=>'dropdown', 'id'=>'languageSelect',)} />

<div id="login">
	<h1>Login</h1>

	<?php echo CHtml::form(); ?>

	<?php echo CHtml::errorSummary($form); ?>

	<div class="formItems">
		<div class="row item1">
			<div class="left">
				<?php echo CHtml::activeLabel($form,'username'); ?>
			</div>
			<div class="right">
				<?php echo CHtml::activeTextField($form,'username') ?>
			</div>
		</div>
		<div class="row item2">
			<div class="left">
				<?php echo CHtml::activeLabel($form,'password'); ?>
			</div>
			<div class="right">
				<?php echo CHtml::activePasswordField($form,'password'); ?>
			</div>
		</div>
		<div class="row item2">
			<div class="left">
			</div>
			<div class="right">
				<?php echo CHtml::activeCheckBox($form,'rememberMe'); ?>
				<?php echo CHtml::activeLabel($form,'rememberMe'); ?>
			</div>
		</div>
	</div>
	<div class="buttons">
		<?php echo CHtml::submitButton('Login'); ?>
	</div>

	<?php echo "<?php echo CHtml::closeTag('form'); ?>\n"; ?>
</div>