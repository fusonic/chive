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
var globalBrowse = {
	
	// Add column
	deleteRow: function(row)
	{
		$('#browse input[type="checkbox"]').attr('checked', false).change();
		$('#browse input[type="checkbox"]').eq(row+1).attr('checked', true).change();
		tableBrowse.deleteRows();
	},
	
	deleteRows: function()
	{
		if($('#browse input[name="browse[]"]:checked').length > 0) 
		{
			$('#deleteRowDialog').dialog("open");
		}
	},
	
	exportRows: function() 
	{
		if($('#browse input[name="browse[]"]:checked').length > 0) 
		{
			selectedKeyData = new Array();
			
			$('#browse input[name="browse[]"]:checked').each(function() {
				selectedKeyData.push(keyData[this.value.match(/\d+/)]);
			});
			
			navigateTo(baseUrl + '/schema/' + schema + '#tables/' + table + '/row/export', { 
				data	: 	JSON.stringify(selectedKeyData),
				schema	: 	schema,
				table	: 	table
			});
		}
	},
	
	editRow: function(rowIndex) 
	{
		
		$('#browse tr').eq(rowIndex+1).appendForm(baseUrl + '/row/edit', {
			attributes: 	JSON.stringify(keyData[rowIndex]),
			schema: 		schema,
			table:			table
		});
		
	},
	
	insertAsNewRow: function(rowIndex, table)
	{
		chive.goto('tables/' + table + '/insert', {
			attributes:	 JSON.stringify(keyData[rowIndex]),
			schema:		 schema,
			table:			table
		});
	},
	
	setup: function() 
	{
	
		/*
		 * Setup dialog
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() {
			$(this).dialog('close');
		};
		buttons[lang.get('core', 'yes')] = function() {
			// Collect ids
			var data = [];
			$('#browse input[name="browse[]"]').each(function(i,o) {
				if($(this).attr('checked')) {
					data.push(keyData[i]);
				}
			});
			
			// Do truncate request
			$.post(baseUrl + '/row/delete', {
				data	: 	JSON.stringify(data),
				schema	: 	schema,
				table	: 	table
			}, AjaxResponse.handle);
			
			$(this).dialog('close');
		};
		
		$('#deleteRowDialog').dialog({
			modal: true,
			resizable: false,
			autoOpen: false,
			buttons: buttons	
		});
		
		$('#queryForm').ajaxForm({
			success: 	function(responseText)
			{
				if (!AjaxResponse.handle(responseText)) {
					var content = document.getElementById('content');
					response = '<div style="display: none">Thank\'s to InternetExplorer 8 which requires this dirty hack ...</div>' + responseText;
					content.innerHTML = responseText;
					var scripts = content.getElementsByTagName('script');
					for (var i = 0; i < scripts.length; i++) {
						$.globalEval(scripts[i].innerHTML);
					}
					init();
				}
			}
		});
		
		$('#queryForm').submit(function() {
			$('#query').select();
		});
		
		var ctrlClicked = false;
		
		$('#query').keydown(function(e) {
			
			if(e.which == 17) {
	    		ctrlClicked = true;
	    	}
	    	else if(e.ctrlKey && e.which == 13 && ctrlClicked) {
	    	
	    		e.preventDefault();
	        	e.stopPropagation();
	    		$("#queryForm").submit();
			}
		});
	},
	
	download: function(_url, _data) 
	{
		io = document.createElement('iframe');
		io.src = _url + (_data ? '?' + $.param(_data) : '');
		io.style.display = 'none';
		io = $(io);
		$('body').append(io);
		
		setTimeout(function() {
			io.remove();
		}, 5000);
		
	}
	
};