<h2>Character sets</h2>
<?php foreach($charactersets AS $characterset) { ?>
	<table class="list">
		<thead>
			<tr>
				<th colspan="2"><?php echo $characterset['Description']; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($characterset['collations'] AS $collation) { ?>
				<tr>
					<td><?php echo $collation['Collation']; ?></td>
					<td><?php echo $collation['Sortlen']; ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<br/>
<?php } ?>


