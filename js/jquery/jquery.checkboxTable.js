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
	
	$.fn.addCheckboxes = function(options) {
		
		var options = $.extend($.fn.addCheckboxes.defaultSettings, options);
		
		return this.each(function() {
			
			var tableObj = $(this);
			
			if(!this.rows)
			{
				return tableObj;
			}
			
			var tbodyObj = $(this.tBodies[0]);
			
			if(options.selectableRows)
			{
				tbodyObj.click(function(e, switchChecked) {
					var formObj = $(e.target).closest('form');
					if(formObj.length == 0)
					{
						if(e.target.tagName == 'INPUT')
						{
							var isChecked = e.target.checked;
							if(switchChecked)
							{
								isChecked = !isChecked;
							}
							
							var checkedBoxes = bodyBoxes.filter('input[checked]').length;
							if(switchChecked)
							{
								if(isChecked)
								{
									checkedBoxes++;
								}
								else
								{
									checkedBoxes--;
								}
							}
	
							// Set head checkbox
							headBoxes.each(function() {
								this.checked = checkedBoxes == bodyBoxes.length;
							}); 
							
							// Set row class
							if(isChecked)
							{
								$(e.target).closest('tr').addClass("selected");
							}
							else
							{
								$(e.target).closest('tr').removeClass("selected");
							}
						}
						else if(e.target.tagName != 'INPUT' && e.target.tagName != 'A' && e.target.parentNode.tagName != 'A')
						{
							$(e.target).closest('tr').find('input[type="checkbox"]').trigger('click', true);
						}
					}
				});
			}
			
			var search = [];
			
			if(this.tHead)
				search.push(this.tHead);
			
			if(this.tFoot)
				search.push(this.tFoot);
			
			var headBoxes = $(search).find('input[type="checkbox"]');
			var bodyBoxes = tbodyObj.find('input[type="checkbox"]');
			
 			headBoxes.click(function(event) {
				var checked = this.checked;
				headBoxes.each(function() {
					this.checked = checked;
				});
				bodyBoxes.each(function() {
					this.checked = checked;
					if(this.checked)
					{
						$(this.parentNode.parentNode).addClass('selected');
					}
					else
					{
						$(this.parentNode.parentNode).removeClass('selected');
					}
				});
			});

			return tableObj;
		});
		
	}
	
	/*
	 * Settings
	 */
	
	$.fn.addCheckboxes.defaultSettings = {
		"selectableRows" : true
	};

})(jQuery);