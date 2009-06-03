<h2>Character sets</h2>

<?php foreach($charsets AS $charset) { ?>
	<div class="list">
		<table class="list">
			<colgroup>
				<col />
				<col style="width: 200px;" />
			</colgroup>
			<thead>
				<tr>
					<th colspan="2"><?php echo $charset['Description']; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($charset['collations'] AS $collation) { ?>
					<tr>
						<td><?php echo $collation['Collation']; ?></td>
						<td><?php echo $collation['Sortlen']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php } ?>