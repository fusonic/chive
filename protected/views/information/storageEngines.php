<h2><?php echo Yii::t('database', 'storageEngines'); ?></h2>

<div class="list">
	<table class="list">
		<tbody>
			<?php foreach($engines AS $engine) { ?>
				<tr>
					<td><?php echo $engine['Engine']; ?></td>
					<td><?php echo $engine['Comment']; ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>