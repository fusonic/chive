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
	
	$.fn.reloadListFilter = function(inputObj) {
		
		return this.each(function()
		{
			var func = $(this).data('listFilterSetup');
			if($.isFunction(func))
			{
				func();
			}
			else
			{
				$(this).setupListFilter(inputObj);
			}
		});
		
	}
	
	$.fn.setupListFilter = function(inputObj) {
		
		return this.each(function() 
		{
			
			var keyBindingDone = false;
			var list = $(this);
			var input = inputObj;
			var inputElement = inputObj.get(0);
			var items = new Array();
			var selectedItem = null;
			
			function selectResult(element) 
			{
				if(selectedItem)
				{
					selectedItem.removeClass('listFilterSelected');
				}
				if(element)
				{
					element.addClass('listFilterSelected');
				}
				selectedItem = element;
			}
			
			function keyDown(e)
			{
				if(e.keyCode == 40) // Down arrow
				{
					moveDown();
				}
				else if(e.keyCode == 38) // Up arrow
				{
					moveUp();
				}
			}
			
			function keyUp(e) 
			{
				if(e.keyCode == 13) // Enter
				{
					performAction();
				}
				else if(e.keyCode != 40 && e.keyCode != 38)
				{
					doFilter();
				}
			}
			
			function moveUp()
			{
				var visibleLi = list.children('li:visible');
				var selectedNow;
				if(selectedItem)
				{
					var previousLi = selectedItem.prevAll('li:visible');
					if(previousLi.length > 0)
					{
						selectedNow = $(previousLi[0]);
					}
					else
					{
						selectedNow = $(visibleLi[visibleLi.length - 1]);
					}
				}
				else
				{
					selectedNow = $(visibleLi[visibleLi.length - 1]);
				}
				selectResult(selectedNow);
			}
			
			function moveDown()
			{
				var visibleLi = list.children('li:visible');
				var selectedNow;
				if(selectedItem)
				{
					var nextLi = selectedItem.nextAll('li:visible');
					if(nextLi.length > 0)
					{
						selectedNow = $(nextLi[0]);
					}
					else
					{
						selectedNow = $(visibleLi[0]);
					}
				}
				else
				{
					selectedNow = $(visibleLi[0]);
				}
				selectResult(selectedNow);
			}
			
			function performAction()
			{
				if(selectedItem)
				{
					selectedItem.children("a:first").each(function() {
						window.location.href = this.href;
					});
				}
			}
			
			function doFilter() 
			{
				var searchString = inputElement.value.toLowerCase();
				
				// Do filtering
				if(searchString == '')
				{
					for(var i = 0; i < items.length; i++)
					{
						items[i][1].show();
					}
				}
				else
				{
					for(var i = 0; i < items.length; i++) 
					{
						if(items[i][0].indexOf(searchString) > -1)
						{
							items[i][1].show();
						}
						else
						{
							items[i][1].hide();
						}
					}
				}
				
				// Find new selected element
				var selectedNow = null;
				if(selectedItem)
				{
					if(selectedItem.is('li:visible'))
					{
						selectedNow = selectedItem;
					}
					else if(selectedItem.nextAll('li:visible').length > 0)
					{
						selectedNow = selectedItem.next('li:visible');
					}
				}
				selectResult(selectedNow);
			}
			
			function setup()
			{
				items = [];
				list.children("li").not(".template").each(function() {
					var item = $(this);
					items.push([
						item.text().trim().toLowerCase(),
						item
					]);
				});
				
				/*
				 * Bind event handlers
				 */
				if(!keyBindingDone)
				{
					input.keyup(keyUp);
					input.keydown(keyDown);
					input.blur(function() {
						selectResult(null); 
					});
					
					keyBindingDone = true;
				}
				
				if(input.val() != '')
				{
					doFilter();
				}
			}
			
			setup();
			list.data('listFilterSetup', setup);
			
		});
		
	}

})(jQuery);