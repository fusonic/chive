<h2><?php echo Yii::t('database', 'storageEngines'); ?></h2>
<table class="list">
	<tbody>
		<?php foreach($engines AS $engine) { ?>
			<tr onclick="$(this).next().toggle();" style="cursor: pointer;">
				<td><?php echo $engine['Engine']; ?></td>
				<td><?php echo $engine['Comment']; ?></td>
			</tr>
			<tr style="display: none;">
				<td colspan="2">
					here you can add some special information about common storage engines
					<?php
						// @todo (rponudic) complete this page with additional information about storage engine
					?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>


