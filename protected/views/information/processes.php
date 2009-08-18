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
<h2>Processes</h2>

<div id="killProcessDialog" title=" echo Yii::t('message', 'killProcess'); ?>" style="display: none">
	 echo Yii::t('message', 'doYouReallyWantToKillSelectedProcesses'); ?>
</div>

<div class="list">
	<table class="list addCheckboxes" id="processes">
		<colgroup>
			<col class="checkbox" />
			<col class="action" />
			<col />
			<col />
			<col />
			<col />
			<col />
			<col />
			<col />
			<col />
			<col />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" /></th>
				<th></th>
				<th> echo Yii::t('core', 'id'); ?></th>
				<th> echo Yii::t('core', 'user'); ?></th>
				<th> echo Yii::t('core', 'host'); ?></th>
				<th> echo Yii::t('database', 'schema'); ?></th>
				<th> echo Yii::t('database', 'command'); ?></th>
				<th> echo Yii::t('core', 'time'); ?></th>
				<th> echo Yii::t('core', 'status'); ?></th>
				<th> echo Yii::t('database', 'query'); ?></th>
			</tr>
		</thead>
		<tbody>
			 foreach($processes AS $process) { ?>
				<tr id="processes_ echo $process['Id']; ?>">
					<td>
						<input type="checkbox" name="processes[]" value=" echo $process['Id']; ?>" />
					</td>
					<td>
						<a href="javascript:void(0);" onclick="tableProcesses.killProcess(' echo $process['Id']; ?>');">
							<com:Icon name="delete" size="16" text="core.kill" />
						</a>
					</td>
					<td> echo $process['Id']; ?></td>
					<td> echo $process['User']; ?></td>
					<td> echo $process['Host']; ?></td>
					<td> echo $process['db']; ?></td>
					<td> echo $process['Command']; ?></td>
					<td> echo $process['Time']; ?></td>
					<td> echo $process['State']; ?></td>
					<td> echo $process['Info']; ?></td>
				</tr>
			 } ?>
		</tbody>
	</table>

	<div class="buttonContainer">
		<div class="left">
			<div class="withSelected">
				<span class="icon">
					<com:Icon name="arrow_turn_090" size="16" />
					<span> echo Yii::t('core', 'withSelected'); ?></span>
				</span>
				<a class="icon button" href="javascript:void(0);" onclick="tableProcesses.killProcesses();">
					<com:Icon name="delete" size="16" />
					<span> echo Yii::t('core', 'kill'); ?></span>
				</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
 // @todo (rponudic) check if this still works with 100s of processes? isn't this too slow? '?>
//setTimeout('reload()', 5000);
setTimeout(function() {
	informationProcesses.setup();
}, 500);
</script>