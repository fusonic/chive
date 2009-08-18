<div id="dropUsersDialog" title="<?php echo Yii::t('database', 'dropUsers'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToDropUsers'); ?>
</div>

<div class="list">

	<div class="buttonContainer">

		<div class="left">
			<?php $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" onclick="privilegesUsers.addUser()" class="icon button">
				<com:Icon name="add" size="16" />
				<span><?php echo Yii::t('database', 'addUser'); ?></span>
			</a>
		</div>
	</div>

	<div class="clear"></div>

	<table id="users" class="list addCheckboxes selectable">
		<colgroup>
			<col class="checkbox" />
			<col />
			<col />
			<col />
			<col />
			<col class="action" />
			<col class="action" />
			<col class="action" />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" /></th>
				<th><?php echo $sort->link('User'); ?></th>
				<th><?php echo $sort->link('Host'); ?></th>
				<th><?php echo Yii::t('core', 'password'); ?></th>
				<th colspan="4"><?php echo Yii::t('database', 'privileges'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($users as $user) { ?>
				<tr id="users_<?php echo $user->getDomId(); ?>">
					<td>
						<input type="checkbox" name="users[]" value="<?php echo '\'' . $user->User . '\'@\'' . $user->Host . '\''; ?>" />
					</td>
					<td>
						<?php echo ($user->User ? $user->User : '%'); ?>
					</td>
					<td>
						<?php echo $user->Host; ?>
					</td>
					<td>
						<?php echo Yii::t('core', ($user->Password ? 'yes' : 'no')) ?>
					</td>
					<td>
						<?php echo implode(', ', $user->getGlobalPrivileges()); ?>
					</td>
					<td>
						<a href="javascript:chive.goto('privileges/users/<?php echo urlencode($user->User ? $user->User : '%'); ?>/<?php echo urlencode($user->Host ? $user->Host : ' '); ?>/schemata')" class="icon">
							<com:Icon name="database" size="16" text="database.schemaSpecificPrivileges" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="privilegesUsers.editUser('<?php echo $user->getDomId(); ?>', '<?php echo $user->User; ?>', '<?php echo $user->Host; ?>')" class="icon">
							<com:Icon name="edit" size="16" text="core.edit" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="privilegesUsers.dropUser('<?php echo $user->User; ?>', '<?php echo $user->Host; ?>')" class="icon">
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
			<a class="icon button" href="javascript:void(0)" onclick="privilegesUsers.dropUsers()">
				<com:Icon name="delete" size="16" />
				<span><?php echo Yii::t('database', 'drop'); ?></span>
			</a>
		</div>
		<div class="right">
			<a href="javascript:void(0)" onclick="privilegesUsers.addUser()" class="icon button">
				<com:Icon name="add" size="16" />
				<span><?php echo Yii::t('database', 'addUser'); ?></span>
			</a>
		</div>
	</div>
</div>

<script type="text/javascript">
setTimeout(function() {
	privilegesUsers.setup();
}, 500);
breadCrumb.set([
	{
		icon: 'privileges',
		href: 'javascript:chive.goto(\'privileges/users\')',
		text: '<?php echo Yii::t('database', 'privileges'); ?>'
	}
]);
</script>