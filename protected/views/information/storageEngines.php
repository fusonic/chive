<h2><?php echo Yii::t('database', 'storageEngines'); ?></h2>

<div class="list">
	<table class="list">
		<colgroup>
			<col />
			<col />
			<col class="action" />
		</colgroup>
		<tbody>
			<?php foreach($engines AS $engine) { ?>
				<tr>
					<td><?php echo $engine['Engine']; ?></td>
					<td><?php echo $engine['Comment']; ?></td>
					<td>
						<a href="javascript:void(0)" onclick="informationStorageEngines.showDetails('<?php echo $engine['Engine']; ?>')">
							<com:Icon name="search" text="core.showDetails" title="core.showDetails" />
						</a>
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