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

var privilegesUserSchemata = {
	
	id: null,
	user: null,
	host: null,
	
	// Add schema specific privilege
	addSchemaPrivilege: function()
	{
		$('#schemata').appendForm(baseUrl + '/privileges/users/'
			+ encodeURIComponent(privilegesUserSchemata.id) + '/schemaActions/create');
	},
	
	// Edit schema specific privilege
	editSchemaPrivilege: function(schema)
	{
		$('#schemata_' + schema).appendForm(baseUrl + '/privileges/users/'
			+ encodeURIComponent(privilegesUserSchemata.id) + '/schemata/'
			+ encodeURIComponent(schema) + '/update');
	},
	
	// Drop schema privileges
	dropSchemaPrivileges: function()
	{
		if($('#schemata input[name="schemata[]"]:checked').length > 0) 
		{
			$('#dropSchemaPrivilegesDialog').dialog("open");
		}
	},
	dropSchemaPrivilege: function(schema)
	{
		$('#schemata input[type="checkbox"]').attr('checked', false).change();
		$('#schemata input[type="checkbox"][value="' + schema + '"]').attr('checked', true).change();
		privilegesUserSchemata.dropSchemaPrivileges();
	},
	
	// Setup dialogs
	setup: function()
	{
		/*
		 * Drop schema privileges
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
			$('#schemata input[name="schemata[]"]:checked').each(function() {
				ids.push($(this).val());
			});

			// Do drop request
			$.post(baseUrl + '/privileges/users/'
				+ encodeURIComponent(privilegesUserSchemata.id) + '/schemaActions/drop', {
				'schemata': ids
			}, AjaxResponse.handle);
			$(this).dialog('close');
		}; 
		$('#dropSchemaPrivilegesDialog').dialog({
			buttons: buttons	
		});
	}
	
};