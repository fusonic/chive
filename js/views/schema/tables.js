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

var schemaTables = {
	
	// Add table
	addTable: function()
	{
		$('#tables').appendForm(baseUrl + '/schema/' + schema + '/tableAction/create');
	},
	
	// Edit table
	editTable: function(table)
	{
		$('#tables_' + table).appendForm(baseUrl + '/schema/' + schema + '/tables/' + table + '/update');
	},
	
	// Drop table
	dropTable: function(table)
	{
		$('#tables input[type="checkbox"]').attr('checked', false).change();
		$('#tables input[type="checkbox"][value="' + table + '"]').attr('checked', true).change();
		schemaTables.dropTables();
	},
	dropTables: function()
	{
		var tables = schemaTables.getSelectedIds();
		if(tables.length > 0)
		{
			var ulObj = $('#dropTablesDialog ul');
			ulObj.html('');
			for(var i = 0; i < tables.length; i++)
			{
				ulObj.append('<li>' + tables[i] + '</li>');
			}
			$('#dropTablesDialog').dialog("open");
		}
		
	},

	runTableOperation: function(operationType)
	{
		var ids = schemaTables.getSelectedIds();

		if (ids.length == 0)
			return;

		var querystring = operationType + " TABLE ";
		var first = true;
		$.each(ids, function(index, value) {
			if (!first) {
				querystring += ",";
			}
			querystring += "`" + value + "`";
			first = false;
		});

		chive.goto("sql", { query: querystring });
	},
	
	// Truncate table
	truncateTable: function(table)
	{
		$('#tables input[type="checkbox"]').attr('checked', false).change();
		$('#tables input[type="checkbox"][value="' + table + '"]').attr('checked', true).change();
		schemaTables.truncateTables();
	},
	truncateTables: function()
	{
		var tables = schemaTables.getSelectedIds();
		if(tables.length > 0)
		{
			var ulObj = $('#truncateTablesDialog ul');
			ulObj.html('');
			for(var i = 0; i < tables.length; i++)
			{
				ulObj.append('<li>' + tables[i] + '</li>');
			}
			$('#truncateTablesDialog').dialog("open");
		}
	},
	
	// Get selected id's
	getSelectedIds: function()
	{
		var ids = [];
		$('#tables input[name="tables[]"]:checked').each(function() {
			ids.push($(this).val());
		});
		return ids;		
	},
	
	// Setup dialogs
	setupDialogs: function()
	{
		/*
		 * Setup drop table dialog
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		}; 
		buttons[lang.get('core', 'yes')] = function() 
		{
			// Collect ids
			var ids = schemaTables.getSelectedIds();
			
			// Do drop request
			$.post(baseUrl + '/schema/' + schema + '/tableAction/drop', {
				'tables': ids
			}, AjaxResponse.handle);
			
			$(this).dialog('close');
		}; 
		$('div.ui-dialog>div[id="dropTablesDialog"]').remove();
		$('#dropTablesDialog').dialog({
			buttons: buttons	
		});
		
		/*
		 * Setup truncate table dialog
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		}; 
		buttons[lang.get('core', 'yes')] = function() 
		{
			// Collect ids
			var ids = schemaTables.getSelectedIds();
			
			// Do truncate request
			$.post(baseUrl + '/schema/' + schema + '/tableAction/truncate', {
				'tables': ids
			}, AjaxResponse.handle);
			
			$(this).dialog('close');
		}; 
		$('div.ui-dialog>div[id="truncateTablesDialog"]').remove();
		$('#truncateTablesDialog').dialog({
			buttons: buttons		
		});
	}
};