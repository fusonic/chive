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
<table class="list">
	<colgroup>
		<col style="width: 200px;"></col>
		<col></col>
	</colgroup>
	<thead>
		<tr>
			<th colspan="2">Server information</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Host</td>
			<td> echo Yii::app()->user->host; ?></td>
		</tr>
		<tr>
			<td>MySQL server version</td>
			<td> echo Yii::app()->db->getServerVersion(); ?></td>
		</tr>
		<tr>
			<td>MySQL client version</td>
			<td> echo Yii::app()->db->getClientVersion(); ?></td>
		</tr>
		<tr>
			<td>User</td>
			<td> echo Yii::app()->user->name; ?>@ echo Yii::app()->user->host; ?></td>
		</tr>
		<tr>
			<td>Webserver</td>
			<td> echo $_SERVER['SERVER_SOFTWARE']; ?></td>
		</tr>
	</tbody>
</table>