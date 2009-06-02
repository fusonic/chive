<h2>Status information</h2>
<?php foreach($status AS $name=>$variable) { ?>
	<table class="list">
		<colgroup>
			<col />
			<col style="width: 200px;" />
		</colgroup>
		<thead>
			<tr>
				<th colspan="2"><?php echo $name; ?></th>
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
	<br/>
<?php } ?>


