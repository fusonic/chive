<div class="list">
	<table class="list selectable">
		<colgroup>
			<col />
			<col />
			<col class="action" />
		</colgroup>
		<tbody>
			<?php foreach($engines AS $engine) { ?>
				<?php $variables = $engine->getVariablesWithValues(); ?>
				<tr style="cursor: pointer" onclick="informationStorageEngines.showDetails('<?php echo $engine->Engine; ?>')">
					<td><?php echo $engine->Engine; ?></td>
					<td><?php echo $engine->Comment; ?></td>
					<td>
						<?php if(count($variables) > 0) { ?>
							<?php echo Html::icon('search', 16, false, 'core.showDetails'); ?>
						<?php } ?>
					</td>
				</tr>
				<?php if(count($variables) > 0) { ?>
					<tr id="<?php echo $engine['Engine']; ?>Infos" class="noSwitch info" style="display: none">
						<td colspan="3">
							<div class="info" style="display: none">
								<table>
									<colgroup>
										<col style="width: 300px" />
										<col style="width: 100px" />
									</colgroup>
									<tbody>
										<?php $i = 0; ?>
										<?php foreach($variables AS $key => $value) { ?>
											<tr <?php if($i > 0) { ?>style="border-top: solid 1px #3B5998"<?php } ?>>
												<td style="padding: 2px 0px"><?php echo $key; ?></td>
												<td style="padding: 2px 0px"><?php echo $value; ?></td>
											</tr>
											<?php $i++; ?>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
breadCrumb.set([
	{
		icon: 'engine',
		href: 'javascript:chive.goto(\'information/storageEngines\')',
		text: '<?php echo Yii::t('core', 'storageEngines'); ?>'
	}
]);
</script>