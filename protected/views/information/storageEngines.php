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
<h2> echo Yii::t('database', 'storageEngines'); ?></h2>

<div class="list">
	<table class="list selectable">
		<colgroup>
			<col />
			<col />
			<col class="action" />
		</colgroup>
		<tbody>
			 foreach($engines AS $engine) { ?>
				<tr style="cursor: pointer" onclick="informationStorageEngines.showDetails(' echo $engine['Engine']; ?>')">
					<td> echo $engine['Engine']; ?></td>
					<td> echo $engine['Comment']; ?></td>
					<td>
						<com:Icon name="search" text="core.showDetails" title="core.showDetails" />
					</td>
				</tr>
				<tr id=" echo $engine['Engine']; ?>Infos" class="noSwitch info" style="display: none">
					<td colspan="3">
						<div class="info" style="display: none">
							Detailled information goes here ....
						</div>
					</td>
				</tr>
			 } ?>
		</tbody>
	</table>
</div>