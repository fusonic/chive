<div id="dropSchemaPrivilegesDialog" title="<?php echo Yii::t('database', 'dropSchemaSpecificPrivileges'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToDropSchemaSpecificPrivileges'); ?>
</div>

<div class="list">

	<div class="buttonContainer">

		<div class="left">
			<?php $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" onclick="privilegesUserSchemata.addSchemaPrivilege()" class="icon button">
				<com:Icon name="add" size="16" />
				<span><?php echo Yii::t('database', 'addSchemaSpecificPrivileges'); ?></span>
			</a>
		</div>
	</div>

	<div class="clear"></div>

	<table id="schemata" class="list addCheckboxes selectable">
		<colgroup>
			<col class="checkbox" />
			<col />
			<col />
			<col class="action" />
			<col class="action" />
			<col class="action" />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" /></th>
				<th><?php echo $sort->link('Schema'); ?></th>
				<th colspan="4"><?php echo Yii::t('database', 'privileges'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(count($schemata) < 1) { ?>
				<tr>
					<td class="noEntries" colspan="14">
						<?php echo Yii::t('database', 'noSchemaSpecificPrivileges'); ?>
					</td>
				</tr>
			<?php } ?>
			<?php foreach($schemata as $schema) { ?>
				<tr id="schemata_<?php echo $schema->Db; ?>">
					<td>
						<input type="checkbox" name="schemata[]" value="<?php echo $schema->Db; ?>" />
					</td>
					<td>
						<?php echo $schema->Db; ?>
					</td>
					<td>
						<?php echo implode(', ', $schema->getPrivileges()); ?>
					</td>
					<td>
						<a href="#privileges/users/<?php echo urlencode($schema->User ? $schema->User : '%'); ?>/<?php echo urlencode($schema->Host); ?>/schemata/<?php echo urlencode($schema->Db); ?>/tables" class="icon">
							<com:Icon name="table" size="16" text="database.tableSpecificPrivileges" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="privilegesUserSchemata.editSchemaPrivilege('<?php echo $schema->Db; ?>')" class="icon">
							<com:Icon name="edit" size="16" text="core.edit" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="privilegesUserSchemata.dropSchemaPrivilege('<?php echo $schema->Db; ?>')" class="icon">
							<com:Icon name="delete" size="16" text="database.drop" />
						</a>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>

	<div class="buttonContainer">
		<div class="left withSelected">
			<span class="icon">
				<img height="16" width="16" alt="unknown" src="/dublin/trunk/images/icons/fugue/16/arrow_turn_090.png" title="unknown" class="icon icon16 icon_arrow_turn_090"/>				<span>With selected: </span>
			</span>
			<a class="icon button" href="javascript:void(0)" onclick="privilegesUserSchemata.dropSchemaPrivileges()">
				<com:Icon name="delete" size="16" />
				<span><?php echo Yii::t('database', 'drop'); ?></span>
			</a>
		</div>
		<div class="right">
			<a href="javascript:void(0)" onclick="privilegesUserSchemata.addSchemaPrivilege()" class="icon button">
				<com:Icon name="add" size="16" />
				<span><?php echo Yii::t('database', 'addSchemaSpecificPrivileges'); ?></span>
			</a>
		</div>
	</div>
</div>

<script type="text/javascript">
setTimeout(function() {
	privilegesUserSchemata.user = '<?php echo $user; ?>';
	privilegesUserSchemata.host = '<?php echo $host; ?>';
	privilegesUserSchemata.setup();
}, 500);
breadCrumb.set([
	{
		icon: 'privileges',
		href: 'javascript:chive.goto(\'privileges/users\')',
		text: '<?php echo Yii::t('database', 'privileges'); ?>'
	},
	{
		icon: 'user',
		href: 'javascript:chive.goto(\'privileges/users/<?php echo urlencode($user ? $user : '%'); ?>/<?php echo urlencode($host ? $host : ' '); ?>/schemata\')',
		text: '<?php echo ($user ? $user : '%'); ?>@<?php echo ($host ? $host : ' '); ?>'
	}
]);
</script>