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
<h2>Character sets</h2>

 foreach($charsets AS $charset) { ?>
	<div class="list" style="width: 50%">
		<table class="list">
			<colgroup>
				<col class="collation" />
				<col />
			</colgroup>
			<thead>
				<tr>
					<th colspan="2"> echo $charset['Description']; ?></th>
				</tr>
			</thead>
			<tbody>
				 foreach($charset['collations'] AS $collation) { ?>
					<tr>
						<td> echo $collation['Collation']; ?></td>
						<td> echo Collation::getDefinition($collation['Collation'], false); ?></td>
					</tr>
				 } ?>
			</tbody>
		</table>
	</div>
 } ?>