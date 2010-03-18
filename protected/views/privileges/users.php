<div id="dropUsersDialog" title="<?php echo Yii::t('core', 'dropUsers'); ?>" style="display: none">
	<?php echo Yii::t('core', 'doYouReallyWantToDropUsers'); ?>
</div>

<div class="list">

	<div class="buttonContainer">

		<div class="left">
			<?php $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" onclick="privilegesUsers.addUser()" class="icon button">
				<?php echo Html::icon('add'); ?>
				<span><?php echo Yii::t('core', 'addUser'); ?></span>
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
				<th colspan="4"><?php echo Yii::t('core', 'privileges'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($users as $user) { ?>
				<tr id="users_<?php echo $user->getDomId(); ?>">
					<td>
						<input type="checkbox" name="users[]" value="<?php echo $user->getId(); ?>" />
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
						<?php echo Html::ajaxLink('privileges/users/' . $user->getId() . '/schemata', array('class' => 'icon')); ?>
							<?php echo Html::icon('database', 16, false, 'core.schemaSpecificPrivileges'); ?>
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="privilegesUsers.editUser('<?php echo $user->getId(); ?>', '<?php echo $user->getDomId(); ?>')" class="icon">
							<?php echo Html::icon('edit', 16, false, 'core.edit'); ?>
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="privilegesUsers.dropUser('<?php echo $user->getId(); ?>')" class="icon">
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
			<a href="javascript:void(0)" onclick="privilegesUsers.dropUsers()" class="icon button">
				<?php echo Html::icon('delete'); ?>
				<span><?php echo Yii::t('core', 'drop'); ?></span>
			</a>
		</div>
		<div class="right">
			<a href="javascript:void(0)" onclick="privilegesUsers.addUser()" class="icon button">
				<?php echo Html::icon('add'); ?>
				<span><?php echo Yii::t('core', 'addUser'); ?></span>
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
		text: '<?php echo Yii::t('core', 'privileges'); ?>'
	}
]);
</script>