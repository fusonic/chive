<?php foreach($variables AS $name => $variable) { ?>
	<div class="list" style="width: 50%">
		<table class="list">
			<colgroup>
				<col style="width: 50%" />
				<col />
			</colgroup>
			<thead>
				<tr>
					<th colspan="2"><?php echo ucfirst($name); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($variable AS $key=>$value) { ?>
					<tr>
						<td><?php echo $key; ?></td>
						<td><?php echo $value; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php } ?>

<script type="text/javascript">
breadCrumb.set([
	{
		icon: 'variable',
		href: 'javascript:chive.goto(\'information/variables\')',
		text: '<?php echo Yii::t('core', 'variables'); ?>'
	}
]);
</script>