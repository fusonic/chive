/*
 * Chive - web based MySQL database management
 * Copyright (C) 2010 Fusonic GmbH
 *
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */

var privilegesUsers = {
	
	// Add user
	addUser: function()
	{
		$('#users').appendForm(baseUrl + '/privileges/userActions/create');
	},
	
	// Edit user
	editUser: function(id, domId)
	{
		$('#users_' + domId).appendForm(baseUrl + '/privileges/users/'
			+ encodeURIComponent(id) + '/update');
	},
	
	// Drop user
	dropUsers: function()
	{
		if($('#users input[name="users[]"]:checked').length > 0) 
		{
			$('#dropUsersDialog').dialog("open");
		}
	},
	dropUser: function(id)
	{
		$('#users input[type="checkbox"]').attr('checked', false).change();
		$('#users input[type="checkbox"][value="' + id + '"]').attr('checked', true).change();
		privilegesUsers.dropUsers();
	},
	
	// Setup dialogs
	setup: function()
	{
		/*
		 * Drop schemata
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		}; 
		buttons[lang.get('core', 'yes')] = function() 
		{
			// Collect ids
			var ids = [];
			$('#users input[name="users[]"]:checked').each(function() {
				ids.push($(this).val());
			});
			
			// Do drop request
			$.post(baseUrl + '/privileges/userActions/drop', {
				'users': ids
			}, AjaxResponse.handle);
			
			$(this).dialog('close');
		}; 
		$('#dropUsersDialog').dialog({
			buttons: buttons	
		});
	}
	
};