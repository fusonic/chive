<div id="dropSchemaPrivilegesDialog" title="<?php echo Yii::t('core', 'dropSchemaSpecificPrivileges'); ?>" style="display: none">
	<?php echo Yii::t('core', 'doYouReallyWantToDropSchemaSpecificPrivileges'); ?>
</div>

<div class="list">

	<div class="buttonContainer">

		<div class="left">
			<?php $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" onclick="privilegesUserSchemata.addSchemaPrivilege()" class="icon button">
				<?php echo Html::icon('add'); ?>
				<span><?php echo Yii::t('core', 'addSchemaSpecificPrivileges'); ?></span>
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
				<th colspan="4"><?php echo Yii::t('core', 'privileges'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(count($schemata) < 1) { ?>
				<tr>
					<td class="noEntries" colspan="14">
						<?php echo Yii::t('core', 'noSchemaSpecificPrivileges'); ?>
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
						<span class="icon">
							<?php echo Html::icon('table', 16, true, 'core.tableSpecificPrivileges'); ?>
						</span>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="privilegesUserSchemata.editSchemaPrivilege('<?php echo $schema->Db; ?>')" class="icon">
							<?php echo Html::icon('edit', 16, false, 'core.edit'); ?>
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="privilegesUserSchemata.dropSchemaPrivilege('<?php echo $schema->Db; ?>')" class="icon">
							<?php echo Html::icon('delete', 16, false, 'core.drop'); ?>
						</a>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>

	<div class="buttonContainer">
		<div class="left withSelected">
			<span class="icon">
				<?php echo Html::icon('arrow_turn_090'); ?>
				<span><?php echo Yii::t('core', 'withSelected'); ?></span>
			</span>
			<a class="icon button" href="javascript:void(0)" onclick="privilegesUserSchemata.dropSchemaPrivileges()">
				<?php echo Html::icon('delete'); ?>
				<span><?php echo Yii::t('core', 'drop'); ?></span>
			</a>
		</div>
		<div class="right">
			<a href="javascript:void(0)" onclick="privilegesUserSchemata.addSchemaPrivilege()" class="icon button">
				<?php echo Html::icon('add'); ?>
				<span><?php echo Yii::t('core', 'addSchemaSpecificPrivileges'); ?></span>
			</a>
		</div>
	</div>
</div>

<script type="text/javascript">
setTimeout(function() {
	privilegesUserSchemata.id = '<?php echo base64_encode($user . '@' . $host); ?>';
	privilegesUserSchemata.user = '<?php echo $user; ?>';
	privilegesUserSchemata.host = '<?php echo $host; ?>';
	privilegesUserSchemata.setup();
}, 500);
breadCrumb.set([
	{
		icon: 'privileges',
		href: 'javascript:chive.goto(\'privileges/users\')',
		text: '<?php echo Yii::t('core', 'privileges'); ?>'
	},
	{
		icon: 'user',
		href: 'javascript:chive.goto(\'privileges/users/<?php echo base64_encode($user . '@' . $host); ?>/schemata\')',
		text: '<?php echo ($user ? $user : '%'); ?>@<?php echo ($host ? $host : ' '); ?>'
	}
]);
</script>