<h2><?php echo Yii::t('core', 'Welcome'); ?>, <?php echo Yii::app()->user->name; ?>!</h2>


<table class="list" style="float: left; width: 49%;">
	<colgroup>
		<col style="width: 200px;"></col>
		<col></col>
	</colgroup>
	<thead>
		<tr>
			<th colspan="2"><?php echo Yii::t('core', 'serverInformation'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo Yii::t('core', 'host'); ?></td>
			<td><?php echo Yii::app()->user->host; ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('core', 'mysqlServerVersion'); ?></td>
			<td><?php echo Yii::app()->db->getServerVersion(); ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('core', 'mysqlClientVersion'); ?></td>
			<td><?php echo Yii::app()->db->getClientVersion(); ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('core', 'user'); ?></td>
			<td><?php echo Yii::app()->user->name; ?>@<?php echo Yii::app()->user->host; ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('core', 'webserver'); ?></td>
			<td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
		</tr>
		<tr>
			<td><?php echo Yii::t('core', 'chiveVersion'); ?></td>
			<td><?php echo Yii::app()->params->version; ?></td>
		</tr>
	</tbody>
</table>

<?php if(ConfigUtil::getUrlFopen() && count($entries) > 0) { ?>
	<table class="list" style="float: left; width: 50%; margin-left: 10px;">
		<colgroup>
			<col style="width: 200px;"></col>
			<col></col>
			<col style="width: 20px;"></col>
		</colgroup>
		<thead>
			<tr>
				<th colspan="3">
					<span class="icon">
						<?php echo CHtml::link(Html::icon('rss'), 'http://feeds.launchpad.net/chive/announcements.atom'); ?>
						<span><?php echo Yii::t('core', 'projectNews'); ?></span>
					</span>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php $i = 1; ?>
			<?php foreach($entries AS $entry) { // Limit entries ?>
				<?php if ($i > 5) break;?>
				<tr class="noSwitch">
					<td><?php echo (string)$formatter->formatDateTime(strtotime($entry->published)); ?></td>
					<td><?php echo (string)$entry->title; ?></td>
					<td>
						<a href="javascript:void(0);" onclick="$(this).parent().parent().next().toggle();">
							<?php echo Html::icon('search', 16, false, 'core.showDetails'); ?>
						</a>
					</td>
				</tr>
				<tr style="display: none;">
					<td colspan="3">
						<?php echo $entry->content; ?>
					</td>
				</tr>
				<?php $i++; ?>
			<?php } ?>
		</tbody>
	</table>
<?php } ?>
