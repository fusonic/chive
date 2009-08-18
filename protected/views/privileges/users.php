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
<div id="dropUsersDialog" title=" echo Yii::t('database', 'dropUsers'); ?>" style="display: none">
	 echo Yii::t('database', 'doYouReallyWantToDropUsers'); ?>
</div>

<div class="list">

	<div class="buttonContainer">

		<div class="left">
			 $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" onclick="privilegesUsers.addUser()" class="icon button">
				<com:Icon name="add" size="16" />
				<span> echo Yii::t('database', 'addUser'); ?></span>
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
				<th> echo $sort->link('User'); ?></th>
				<th> echo $sort->link('Host'); ?></th>
				<th> echo Yii::t('core', 'password'); ?></th>
				<th colspan="4"> echo Yii::t('database', 'privileges'); ?></th>
			</tr>
		</thead>
		<tbody>
			 foreach($users as $user) { ?>
				<tr id="users_ echo $user->getDomId(); ?>">
					<td>
						<input type="checkbox" name="users[]" value=" echo '\'' . $user->User . '\'@\'' . $user->Host . '\''; ?>" />
					</td>
					<td>
						 echo ($user->User ? $user->User : '%'); ?>
					</td>
					<td>
						 echo $user->Host; ?>
					</td>
					<td>
						 echo Yii::t('core', ($user->Password ? 'yes' : 'no')) ?>
					</td>
					<td>
						 echo implode(', ', $user->getGlobalPrivileges()); ?>
					</td>
					<td>
						<a href="#privileges/users/ echo urlencode($user->User ? $user->User : '%'); ?>/ echo urlencode($user->Host ? $user->Host : ' '); ?>/schemata" class="icon">
							<com:Icon name="database" size="16" text="database.schemaSpecificPrivileges" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="privilegesUsers.editUser(' echo $user->getDomId(); ?>', ' echo $user->User; ?>', ' echo $user->Host; ?>')" class="icon">
							<com:Icon name="edit" size="16" text="core.edit" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="privilegesUsers.dropUser(' echo $user->User; ?>', ' echo $user->Host; ?>')" class="icon">
							<com:Icon name="delete" size="16" text="database.drop" />
						</a>
					</td>
				</tr>
			 } ?>
		</tbody>
	</table>

	<div class="buttonContainer">
		<div class="left withSelected">
			<span class="icon">
				<img height="16" width="16" alt="unknown" src="/dublin/trunk/images/icons/fugue/16/arrow_turn_090.png" title="unknown" class="icon icon16 icon_arrow_turn_090"/>				<span>With selected: </span>
			</span>
			<a class="icon button" href="javascript:void(0)" onclick="privilegesUsers.dropUsers()">
				<com:Icon name="delete" size="16" />
				<span> echo Yii::t('database', 'drop'); ?></span>
			</a>
		</div>
		<div class="right">
			<a href="javascript:void(0)" onclick="privilegesUsers.addUser()" class="icon button">
				<com:Icon name="add" size="16" />
				<span> echo Yii::t('database', 'addUser'); ?></span>
			</a>
		</div>
	</div>
</div>

<script type="text/javascript">
setTimeout(function() {
	privilegesUsers.setup();
}, 500);
</script>