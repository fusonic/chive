<table class="list">
	<colgroup>
		<col style="width: 200px;"></col>
		<col></col>
	</colgroup>
	<thead>
		<tr>
			<th colspan="2">Server information</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Host</td>
			<td><?php echo Yii::app()->user->host; ?></td>
		</tr>
		<tr>
			<td>MySQL server version</td>
			<td><?php echo Yii::app()->db->getServerVersion(); ?></td>
		</tr>
		<tr>
			<td>MySQL client version</td>
			<td><?php echo Yii::app()->db->getClientVersion(); ?></td>
		</tr>
		<tr>
			<td>User</td>
			<td><?php echo Yii::app()->user->name; ?>@<?php echo Yii::app()->user->host; ?></td>
		</tr>
		<tr>
			<td>Webserver</td>
			<td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
		</tr>
	</tbody>
</table>