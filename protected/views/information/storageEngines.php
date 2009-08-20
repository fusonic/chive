<div class="list">
	<table class="list selectable">
		<colgroup>
			<col />
			<col />
			<col class="action" />
		</colgroup>
		<tbody>
			<?php foreach($engines AS $engine) { ?>
				<tr style="cursor: pointer" onclick="informationStorageEngines.showDetails('<?php echo $engine['Engine']; ?>')">
					<td><?php echo $engine['Engine']; ?></td>
					<td><?php echo $engine['Comment']; ?></td>
					<td>
						<?php echo Html::icon('search', 16, false, 'core.showDetails'); ?>
					</td>
				</tr>
				<tr id="<?php echo $engine['Engine']; ?>Infos" class="noSwitch info" style="display: none">
					<td colspan="3">
						<div class="info" style="display: none">
							Detailled information goes here ....
						</div>
					</td>
				</tr>
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