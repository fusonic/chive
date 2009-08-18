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
<div id="dropSchemaPrivilegesDialog" title=" echo Yii::t('database', 'dropSchemaSpecificPrivileges'); ?>" style="display: none">
	 echo Yii::t('database', 'doYouReallyWantToDropSchemaSpecificPrivileges'); ?>
</div>

<div class="list">

	<div class="buttonContainer">

		<div class="left">
			 $this->widget('LinkPager', array('pages' => $pages)); ?>
		</div>
		<div class="right">
			<a href="javascript:void(0)" onclick="privilegesUserSchemata.addSchemaPrivilege()" class="icon button">
				<com:Icon name="add" size="16" />
				<span> echo Yii::t('database', 'addSchemaSpecificPrivileges'); ?></span>
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
				<th> echo $sort->link('Schema'); ?></th>
				<th colspan="4"> echo Yii::t('database', 'privileges'); ?></th>
			</tr>
		</thead>
		<tbody>
			 if(count($schemata) < 1) { ?>
				<tr>
					<td class="noEntries" colspan="14">
						 echo Yii::t('database', 'noSchemaSpecificPrivileges'); ?>
					</td>
				</tr>
			 } ?>
			 foreach($schemata as $schema) { ?>
				<tr id="schemata_ echo $schema->Db; ?>">
					<td>
						<input type="checkbox" name="schemata[]" value=" echo $schema->Db; ?>" />
					</td>
					<td>
						 echo $schema->Db; ?>
					</td>
					<td>
						 echo implode(', ', $schema->getPrivileges()); ?>
					</td>
					<td>
						<a href="#privileges/users/ echo urlencode($schema->User ? $schema->User : '%'); ?>/ echo urlencode($schema->Host); ?>/schemata/ echo urlencode($schema->Db); ?>/tables" class="icon">
							<com:Icon name="table" size="16" text="database.tableSpecificPrivileges" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="privilegesUserSchemata.editSchemaPrivilege(' echo $schema->Db; ?>')" class="icon">
							<com:Icon name="edit" size="16" text="core.edit" />
						</a>
					</td>
					<td>
						<a href="javascript:void(0)" onclick="privilegesUserSchemata.dropSchemaPrivilege(' echo $schema->Db; ?>')" class="icon">
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
			<a class="icon button" href="javascript:void(0)" onclick="privilegesUserSchemata.dropSchemaPrivileges()">
				<com:Icon name="delete" size="16" />
				<span> echo Yii::t('database', 'drop'); ?></span>
			</a>
		</div>
		<div class="right">
			<a href="javascript:void(0)" onclick="privilegesUserSchemata.addSchemaPrivilege()" class="icon button">
				<com:Icon name="add" size="16" />
				<span> echo Yii::t('database', 'addSchemaSpecificPrivileges'); ?></span>
			</a>
		</div>
	</div>
</div>

<script type="text/javascript">
setTimeout(function() {
	privilegesUserSchemata.user = ' echo $user; ?>';
	privilegesUserSchemata.host = ' echo $host; ?>';
	privilegesUserSchemata.setup();
}, 500);
</script>