<h2>Processes</h2>

<div id="killProcessDialog" title="<?php echo Yii::t('message', 'killProcess'); ?>" style="display: none">
	<?php echo Yii::t('message', 'doYouReallyWantToKillSelectedProcesses'); ?>
</div>

<table class="list addCheckboxes" id="processes">
	<colgroup>
			<col class="action" />
			<col />
			<col />
			<col />
			<col />
			<col />
			<col />
			<col />
			<col />
			<col />
	</colgroup>
	<thead>
		<tr>
			<th></th>
			<th><?php echo Yii::t('core', 'id'); ?></th>
			<th><?php echo Yii::t('core', 'user'); ?></th>
			<th><?php echo Yii::t('core', 'host'); ?></th>
			<th><?php echo Yii::t('database', 'schema'); ?></th>
			<th><?php echo Yii::t('database', 'command'); ?></th>
			<th><?php echo Yii::t('core', 'time'); ?></th>
			<th><?php echo Yii::t('core', 'status'); ?></th>
			<th><?php echo Yii::t('database', 'query'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($processes AS $process) { ?>
			<tr id="processes_<?php echo $process['Id']; ?>">
				<td>
					<a href="javascript:void(0);" onclick="tableProcesses.killProcess('<?php echo $process['Id']; ?>');">
						<com:Icon name="delete" size="16" text="core.kill" />
					</a>
				</td>
				<td><?php echo $process['Id']; ?></td>
				<td><?php echo $process['User']; ?></td>
				<td><?php echo $process['Host']; ?></td>
				<td><?php echo $process['db']; ?></td>
				<td><?php echo $process['Command']; ?></td>
				<td><?php echo $process['Time']; ?></td>
				<td><?php echo $process['State']; ?></td>
				<td><?php echo $process['Info']; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<div class="withSelected">
	<span class="icon">
		<com:Icon name="arrow_turn_090" size="16" />
		<span><?php echo Yii::t('core', 'withSelected'); ?></span>
	</span>
	<a class="icon" href="javascript:void(0);" onclick="tableProcesses.killProcesses();">
		<com:Icon name="delete" size="16" />
		<span><?php echo Yii::t('core', 'kill'); ?></span>
	</a>
</div>

<script type="text/javascript">
	<?php // @todo (rponudic) check if this still works with 100s of processes? isn't this too slow? '?>
	//setTimeout('reload()', 5000);
</script>

<script type="text/javascript">
tableProcesses.setupDialogs();
</script>


