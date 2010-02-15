<div id="killProcessDialog" title="<?php echo Yii::t('core', 'killProcess'); ?>" style="display: none">
	<?php echo Yii::t('core', 'doYouReallyWantToKillSelectedProcesses'); ?>
</div>

<div class="list">
	<table class="list addCheckboxes" id="processes">
		<colgroup>
			<col class="checkbox" />
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
				<th><input type="checkbox" /></th>
				<th></th>
				<th><?php echo Yii::t('core', 'id'); ?></th>
				<th><?php echo Yii::t('core', 'user'); ?></th>
				<th><?php echo Yii::t('core', 'host'); ?></th>
				<th><?php echo Yii::t('core', 'schema'); ?></th>
				<th><?php echo Yii::t('core', 'command'); ?></th>
				<th><?php echo Yii::t('core', 'time'); ?></th>
				<th><?php echo Yii::t('core', 'status'); ?></th>
				<th><?php echo Yii::t('core', 'query'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($processes AS $process) { ?>
				<tr id="processes_<?php echo $process['Id']; ?>">
					<td>
						<input type="checkbox" name="processes[]" value="<?php echo $process['Id']; ?>" />
					</td>
					<td>
						<a href="javascript:void(0);" onclick="informationProcesses.killProcess('<?php echo $process['Id']; ?>');">
							<?php echo Html::icon('delete', 16, false, 'core.kill'); ?>
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

	<div class="buttonContainer">
		<div class="left">
			<div class="withSelected">
				<span class="icon">
					<?php echo Html::icon('arrow_turn_090'); ?>
					<span><?php echo Yii::t('core', 'withSelected'); ?></span>
				</span>
				<a class="icon button" href="javascript:void(0);" onclick="informationProcesses.killProcesses();">
					<?php echo Html::icon('delete'); ?>
					<span><?php echo Yii::t('core', 'kill'); ?></span>
				</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
<?php // @todo (rponudic) check if this still works with 100s of processes? isn't this too slow? '?>
//setTimeout('reload()', 5000);
setTimeout(function() {
	informationGeneral.setup();
	informationProcesses.setup();
}, 500);
breadCrumb.set([
	{
		icon: 'process',
		href: 'javascript:chive.goto(\'information/processes\')',
		text: '<?php echo Yii::t('core', 'processes'); ?>'
	}
]);
</script>