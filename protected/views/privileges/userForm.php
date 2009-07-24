<?php CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3); ?>
<script type="text/javascript">
var idPrefix = '<?php echo CHtml::$idPrefix; ?>';
</script>

<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		<?php echo Yii::t('database', ($user->isNewRecord ? 'addUser' : 'editUser')); ?>
	</h1>
	<?php echo CHtml::errorSummary($user, false); ?>
	<table class="form">
		<colgroup>
			<col class="col1"/>
			<col class="col2" />
			<col class="col3" />
			<col style="width: 100px" />
		</colgroup>
		<tbody>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($user, 'User'); ?>
				</td>
				<td colspan="3">
					<?php echo CHtml::activeTextField($user, 'User'); ?>
					<a href="javascript:void(0)" onclick="$('#<?php echo CHtml::$idPrefix; ?>User_User').val('')" class="button">
						<span><?php echo Yii::t('database', 'anyUser'); ?></span>
					</a>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($user, 'Host'); ?>
				</td>
				<td colspan="3">
					<?php echo CHtml::activeTextField($user, 'Host'); ?>
					<a href="javascript:void(0)" onclick="$('#<?php echo CHtml::$idPrefix; ?>User_Host').val('%')" class="button">
						<span><?php echo Yii::t('database', 'anyHost'); ?></span>
					</a>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($user, 'plainPassword'); ?>
				</td>
				<td colspan="2">
					<?php if($user->isNewRecord) { ?>
						<?php echo CHtml::activeTextField($user, 'plainPassword'); ?>
					<?php } else { ?>
						<?php echo CHtml::activeTextField($user, 'plainPassword'); ?>
						<?php echo CHtml::checkBox('User[keepPw]', !isset($_POST['User']['plainPassword'])); ?>
						<?php echo CHtml::label(Yii::t('core', 'keep'), 'User_plainPassword'); ?>
					<?php } ?>
				</td>
			</tr>
		</tbody>
	</table>
	<div style="overflow: hidden">
		<fieldset style="float: left">
			<legend><?php echo Yii::t('database', 'data'); ?></legend>
			<?php foreach(array_keys(User::getAllGlobalPrivileges('data')) AS $priv) { ?>
				<?php echo CHtml::checkBox('User[GlobalPrivileges][' . $priv . ']', $user->checkGlobalPrivilege($priv)); ?>
				<?php echo CHtml::label($priv, 'User_GlobalPrivileges_' . $priv); ?><br />
			<?php } ?>
		</fieldset>
		<fieldset style="float: left; margin-left: 10px">
			<legend><?php echo Yii::t('database', 'structure'); ?></legend>
			<?php foreach(array_keys(User::getAllGlobalPrivileges('structure')) AS $priv) { ?>
				<?php echo CHtml::checkBox('User[GlobalPrivileges][' . $priv . ']', $user->checkGlobalPrivilege($priv)); ?>
				<?php echo CHtml::label($priv, 'User_GlobalPrivileges_' . $priv); ?><br />
			<?php } ?>
		</fieldset>
		<fieldset style="float: left; margin-left: 10px">
			<legend><?php echo Yii::t('core', 'administration'); ?></legend>
			<?php foreach(array_keys(User::getAllGlobalPrivileges('administration')) AS $priv) { ?>
				<?php echo CHtml::checkBox('User[GlobalPrivileges][' . $priv . ']', $user->checkGlobalPrivilege($priv)); ?>
				<?php echo CHtml::label($priv, 'User_GlobalPrivileges_' . $priv); ?><br />
			<?php } ?>
		</fieldset>
	</div>
	<div class="buttonContainer">
		<a href="javascript:void(0)" onclick="$('#<?php echo CHtml::$idPrefix; ?>').submit()" class="icon button">
			<com:Icon name="save" size="16" />
			<span><?php echo Yii::t('action', 'save'); ?></span>
		</a>
		<a href="javascript:void(0)" onclick="$('#<?php echo CHtml::$idPrefix; ?>').slideUp(500, function() { $(this).parents('tr').remove(); })" class="icon button">
			<com:Icon name="delete" size="16" />
			<span><?php echo Yii::t('action', 'cancel'); ?></span>
		</a>
	</div>
</form>

<script type="text/javascript">
setTimeout(function() {
	privilegesUserForm.create();
}, 500);
</script>