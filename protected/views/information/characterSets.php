<h2>Character sets</h2>

<?php foreach($charsets AS $charset) { ?>
	<div class="list" style="width: 50%">
		<table class="list">
			<colgroup>
				<col class="collation" />
				<col />
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
						<td><?php echo Collation::getDefinition($collation['Collation'], false); ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php } ?>