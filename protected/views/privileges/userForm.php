<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
 * 
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */
 CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3); ?>
<script type="text/javascript">
var idPrefix = ' echo CHtml::$idPrefix; ?>';
</script>

 echo CHtml::form('', 'post', array('id' => CHtml::$idPrefix)); ?>
	<h1>
		 echo Yii::t('database', ($user->isNewRecord ? 'addUser' : 'editUser')); ?>
	</h1>
	 echo CHtml::errorSummary($user, false); ?>
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
					 echo CHtml::activeLabel($user, 'User'); ?>
				</td>
				<td colspan="3">
					 echo CHtml::activeTextField($user, 'User'); ?>
					<a href="javascript:void(0)" onclick="$('# echo CHtml::$idPrefix; ?>User_User').val('')" class="button">
						<span> echo Yii::t('database', 'anyUser'); ?></span>
					</a>
				</td>
			</tr>
			<tr>
				<td>
					 echo CHtml::activeLabel($user, 'Host'); ?>
				</td>
				<td colspan="3">
					 echo CHtml::activeTextField($user, 'Host'); ?>
					<a href="javascript:void(0)" onclick="$('# echo CHtml::$idPrefix; ?>User_Host').val('%')" class="button">
						<span> echo Yii::t('database', 'anyHost'); ?></span>
					</a>
				</td>
			</tr>
			<tr>
				<td>
					 echo CHtml::activeLabel($user, 'plainPassword'); ?>
				</td>
				<td colspan="2">
					 if($user->isNewRecord) { ?>
						 echo CHtml::activeTextField($user, 'plainPassword'); ?>
					 } else { ?>
						 echo CHtml::activeTextField($user, 'plainPassword'); ?>
						 echo CHtml::checkBox('User[keepPw]', !isset($_POST['User']['plainPassword'])); ?>
						 echo CHtml::label(Yii::t('core', 'keep'), 'User_plainPassword'); ?>
					 } ?>
				</td>
			</tr>
		</tbody>
	</table>
	<div style="overflow: hidden">
		<fieldset style="float: left">
			<legend> echo Yii::t('database', 'data'); ?></legend>
			 foreach(array_keys(User::getAllGlobalPrivileges('data')) AS $priv) { ?>
				 echo CHtml::checkBox('User[GlobalPrivileges][' . $priv . ']', $user->checkGlobalPrivilege($priv)); ?>
				 echo CHtml::label($priv, 'User_GlobalPrivileges_' . $priv); ?><br />
			 } ?>
		</fieldset>
		<fieldset style="float: left; margin-left: 10px">
			<legend> echo Yii::t('database', 'structure'); ?></legend>
			 foreach(array_keys(User::getAllGlobalPrivileges('structure')) AS $priv) { ?>
				 echo CHtml::checkBox('User[GlobalPrivileges][' . $priv . ']', $user->checkGlobalPrivilege($priv)); ?>
				 echo CHtml::label($priv, 'User_GlobalPrivileges_' . $priv); ?><br />
			 } ?>
		</fieldset>
		<fieldset style="float: left; margin-left: 10px">
			<legend> echo Yii::t('core', 'administration'); ?></legend>
			 foreach(array_keys(User::getAllGlobalPrivileges('administration')) AS $priv) { ?>
				 echo CHtml::checkBox('User[GlobalPrivileges][' . $priv . ']', $user->checkGlobalPrivilege($priv)); ?>
				 echo CHtml::label($priv, 'User_GlobalPrivileges_' . $priv); ?><br />
			 } ?>
		</fieldset>
	</div>
	<div class="buttonContainer">
		<a href="javascript:void(0)" onclick="$('# echo CHtml::$idPrefix; ?>').submit()" class="icon button">
			<com:Icon name="save" size="16" />
			<span> echo Yii::t('action', 'save'); ?></span>
		</a>
		<a href="javascript:void(0)" onclick="$('# echo CHtml::$idPrefix; ?>').slideUp(500, function() { $(this).parents('tr').remove(); })" class="icon button">
			<com:Icon name="delete" size="16" />
			<span> echo Yii::t('action', 'cancel'); ?></span>
		</a>
	</div>
</form>

<script type="text/javascript">
setTimeout(function() {
	privilegesUserForm.create();
}, 500);
</script>