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
<h2>Server variables</h2>

 foreach($variables AS $name => $variable) { ?>
	<div class="list" style="width: 50%">
		<table class="list">
			<colgroup>
				<col style="width: 50%" />
				<col />
			</colgroup>
			<thead>
				<tr>
					<th colspan="2"> echo $name; ?></th>
				</tr>
			</thead>
			<tbody>
				 foreach($variable AS $key=>$value) { ?>
					<tr>
						<td> echo $key; ?></td>
						<td> echo $value; ?></td>
					</tr>
				 } ?>
			</tbody>
		</table>
	</div>
 } ?>