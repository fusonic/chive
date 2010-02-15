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

(function($) {	
	
	$.fn.editableTable = function(options) {
		
		//var options = $.extend($.fn.addCheckboxes.defaultSettings, options);
		
		return this.each(function() {
			
			var tableObj = $(this);
			
			if(!this.rows)
			{
				return tableObj;
			}
			
			var tbodyObj = $(this.tBodies[0]);
			
			tbodyObj.dblclick(function(e) {
				
				if(e.target.tagName == 'INPUT' || e.target.tagName == 'TEXTAREA')
				{
					return tableObj;
				}
				
				if(editing) {
					reset();
					//$('#form_' + editing).submit();
				}
					
				if(e.target.tagName == 'TD')
				{
					// @todo (rponudic) Make this a setting?
					if(e.target.cellIndex < 4)
						return;

					rowIndex = $(e.target).closest('tr').get(0).rowIndex-1;
					
					$(e.target).load(baseUrl + '/row/input', {
							
							schema:		schema,
							table:		table,
							column:		e.target.className,
							attributes:	JSON.stringify(keyData[rowIndex]),
							oldValue:	e.target.innerHTML,
							rowIndex:	rowIndex
							
					});
					
				}
				else
				{
					$(e.target).closest('td').trigger('dblclick');
				}
			});
			
			return tableObj;
		});
		
	}
	
	/*
	 * Settings
	 */
	
	$.fn.editableTable.defaultSettings = {
	};

})(jQuery);