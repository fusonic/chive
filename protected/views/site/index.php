<h2>Welcome, <?php echo Yii::app()->user->name; ?>!</h2>



<table class="list" style="float: left; width: 50%; margin-right: 10px;">
	<colgroup>
		<col style="width: 200px;"></col>
		<col></col>
	</colgroup>
	<thead>
		<tr>
			<th colspan="2">
				<span class="icon">
					<?php echo Html::icon('rss'); ?>
					<span>Project news</span>
				</span>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>21.4.2009</td>
			<td>Project started and got released under GPL!</td>
		</tr>
		<tr>
			<td>21.4.2009</td>
			<td>Project started and got released under GPL!</td>
		</tr>
		<tr>
			<td>21.4.2009</td>
			<td>Project started and got released under GPL!</td>
		</tr>
		<tr>
			<td>21.4.2009</td>
			<td>Project started and got released under GPL!</td>
		</tr>
	</tbody>
</table>

<table class="list" style="float: left; width: 49%;">
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