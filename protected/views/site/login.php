<div id="languageDialog" title="<?php echo Yii::t('core', 'chooseLanguage'); ?>">
	<table>
		<tr>
		<?php if (count($languages) > 0 ) {?>
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
		<?php } else { ?>
			<td>
				<?php echo Yii::t('core', 'noOtherLanguagesAvailable'); ?>
			</td>
		<?php } ?>
		</tr>
	</table>
	<a href="http://www.chive-project.com" style="float:right; margin-top: 20px;">Help translating this project...</a>
</div>

<div id="themeDialog" title="<?php echo Yii::t('core', 'chooseTheme'); ?>">
	<table>
		<tr>
		<?php if (count($themes) > 0 ) {?>
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
		<?php } else { ?>
			<td>
				<?php echo Yii::t('core', 'noOtherThemesAvailable'); ?>
			</td>
	<?php } ?>
		</tr>
	</table>
</div>

<div id="login">
	<div id="login-logo">
		<img src="../images/logo-big.png" alt="chive" title="" />
	</div>

	<?php if($validBrowser) { ?>

		<?php echo CHtml::errorSummary($form, '', ''); ?>

		<div id="login-form">
			<?php echo CHtml::form(); ?>
			<div class="formItems non-floated" style="text-align: left;">
				<div class="item row1">
					<div class="left">
						<span class="icon">
							<?php echo CHtml::activeLabel($form,'host'); ?>
						</span>
					</div>
					<div class="right">
						<?php echo CHtml::activeTextField($form, 'host', array('class'=>'text')); ?>
					</div>
				</div>
				<div class="item row2">
					<div class="left" style="float: none;">
						<span class="icon">
							<?php echo CHtml::activeLabel($form,'username'); ?>
						</span>
					</div>
					<div class="right">
						<?php echo CHtml::activeTextField($form,'username', array('class'=>'text')) ?>
					</div>
				</div>
				<div class="item row1">
					<div class="left">
						<span class="icon">
							<?php echo CHtml::activeLabel($form, 'password'); ?>
						</span>
					</div>
					<div class="right">
						<?php echo CHtml::activePasswordField($form, 'password', array('class' => 'text', 'value' => '')); ?>
					</div>
				</div>
			</div>

			<div class="buttons">
				<a class="icon button primary" href="javascript:void(0);" onclick="$('form').submit()">
					<?php echo Html::icon('login', 16, false, 'core.login'); ?>
					<span><?php echo Yii::t('core', 'login'); ?></span>
				</a>
				<input type="submit" value="<?php echo Yii::t('core', 'login'); ?>" style="display: none" />
			</div>

			<?php echo CHtml::closeTag('form'); ?>
		</div>
	<?php } else { ?>
		<div id="login-form">
			<?php echo Yii::t('core', 'incompatibleBrowserWarning'); ?>
			<div style="margin-top: 10px">
				<a href="http://www.firefox.com">
					<img src="<?php echo BASEURL; ?>/images/browsers/firefox.jpg" alt="Mozilla Firefox" title="Mozilla Firefox" />
				</a>
				<a href="http://www.google.com/chrome">
					<img src="<?php echo BASEURL; ?>/images/browsers/chrome.jpg" alt="Google Chrome" title="Google Chrome" />
				</a>
				<a href="http://www.opera.com">
					<img src="<?php echo BASEURL; ?>/images/browsers/opera.jpg" alt="Opera" title="Opera" />
				</a>
				<a href="http://www.microsoft.com/windows/Internet-explorer/default.aspx">
					<img src="<?php echo BASEURL; ?>/images/browsers/internetexplorer.jpg" alt="Microsoft Internet Explorer" title="Microsoft Internet Explorer" />
				</a>
			</div>
		</div>
	<?php } ?>

</div>

<script type="text/javascript">
$(function() {
	$('#LoginForm_username').focus();
});
</script>