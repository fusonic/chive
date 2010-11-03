<?php CHtml::generateRandomIdPrefix(); ?>
<script type="text/javascript">
var idPrefix = '<?php echo CHtml::$idPrefix; ?>';
</script>

<?php echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		<?php echo Yii::t('core', ($user->isNewRecord ? 'addUser' : 'editUser')); ?>
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
						<span><?php echo Yii::t('core', 'anyUser'); ?></span>
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
						<span><?php echo Yii::t('core', 'anyHost'); ?></span>
					</a>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo CHtml::activeLabel($user, 'plainPassword'); ?>
				</td>
				<td colspan="2">
					<?php echo CHtml::activeTextField($user, 'plainPassword'); ?>
					<?php if(!$user->isNewRecord) { ?>
						<br />
						<?php echo CHtml::checkBox('User[keepPw]', !isset($_POST['User']['plainPassword'])); ?>
						<?php echo CHtml::label(Yii::t('core', 'keepPassword'), 'User_keepPw'); ?>
					<?php } ?>
				</td>
			</tr>
		</tbody>
	</table>
	<div style="overflow: hidden">
		<fieldset style="float: left">
			<legend><?php echo Yii::t('core', 'data'); ?></legend>
			<?php foreach(array_keys(User::getAllGlobalPrivileges('data')) AS $priv) { ?>
				<?php echo CHtml::checkBox('User[GlobalPrivileges][' . $priv . ']', $user->checkGlobalPrivilege($priv)); ?>
				<?php echo CHtml::label($priv, 'User_GlobalPrivileges_' . $priv); ?><br />
			<?php } ?>
		</fieldset>
		<fieldset style="float: left; margin-left: 10px">
			<legend><?php echo Yii::t('core', 'structure'); ?></legend>
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
	<?php echo Html::submitFormArea(); ?>
</form>

<script type="text/javascript">
setTimeout(function() {
	privilegesUserForm.create();
}, 500);
</script>