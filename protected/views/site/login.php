<?php if (count($languages) > 0 ) {?>
	<div id="languageDialog" title="<?php echo Yii::t('core', 'chooseLanguage'); ?>">
		<table>
			<tr>
			<?php $i = 0; ?>
			<?php $languageCount = count($languages); ?>
			<?php foreach($languages AS $language) { ?>

				<td style="width: 150px;">
					<a href="<?php echo $language['url']; ?>" class="icon">
						<img src="<?php echo BASEURL . '/' . $language['icon']; ?>" alt="test" />
						<span><?php echo $language['label']; ?></span>
					</a>
				</td>

				<?php $i++; ?>
				<?php if ($i % 3 == 0 && $languageCount > $i) { ?>
					</tr><tr>
				<?php } ?>


			<?php } ?>
			</tr>
		</table>
		<span style="float:right; margin-top: 20px;">Help translating this project...</span>
	</div>
<?php } ?>

<?php if (count($themes) > 0 ) {?>
	<div id="themeDialog" title="<?php echo Yii::t('core', 'chooseTheme'); ?>">
		<table>
			<tr>
			<?php $i = 0; ?>
			<?php $themeCount = count($themes); ?>
			<?php foreach($themes AS $theme) { ?>

				<td style="width: 150px;">
					<a href="<?php echo $theme['url']; ?>" class="icon">
						<img src="<?php echo BASEURL . '/' . $theme['icon']; ?>" alt="test" />
						<span><?php echo $theme['label']; ?></span>
					</a>
				</td>

				<?php $i++; ?>
				<?php if ($i % 3 == 0 && $themeCount > $i) { ?>
					</tr><tr>
				<?php } ?>


			<?php } ?>
			</tr>
		</table>
	</div>
<?php } ?>

<div id="login">

	<div style="background: url('../images/logo-big.png') no-repeat 15px 0px; padding-bottom: 35px; height: 67px;"></div>

	<div id="loginform">
		<?php echo CHtml::form(); ?>
		<div class="formItems non-floated" style="text-align: left;">
			<div class="item row1">
				<div class="left">
					<?php echo CHtml::activeLabel($form,'host'); ?>
				</div>
				<div class="right">
					<?php echo CHtml::activeTextField($form, 'host', array('value'=>'localhost', 'class'=>'text')); ?>
				</div>
			</div>
			<div class="item row2">
				<div class="left" style="float: none;">
					<?php echo CHtml::activeLabel($form,'username'); ?>
				</div>
				<div class="right">
					<?php echo CHtml::activeTextField($form,'username', array('class'=>'text')) ?>
					<?php echo CHtml::error($form, 'username'); ?>
				</div>
			</div>
			<div class="item row1">
				<div class="left">
					<?php echo CHtml::activeLabel($form,'password'); ?>
				</div>
				<div class="right">
					<?php echo CHtml::activePasswordField($form,'password', array('class'=>'text')); ?>
				</div>
			</div>
		</div>

		<div class="buttons">
			<a class="icon button" href="javascript:void(0);" onclick="$('form').submit()">
				<com:Icon size="16" name="login" text="core.login" />
				<span><?php echo Yii::t('core', 'login'); ?></span>
			</a>
			<input type="submit" value="<?php echo Yii::t('core', 'login'); ?>" style="display: none" />
		</div>

		<?php echo CHtml::closeTag('form'); ?>
	</div>

</div>