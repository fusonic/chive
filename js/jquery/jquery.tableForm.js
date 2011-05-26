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
	
	$.fn.appendForm = function(url, data, className)
	{
		
		if(!className)
		{
			className = 'form';
		}
		
		return this.each(function() {
			
			if(this.tagName == "TABLE")
			{
				return $(this).children("tbody").children("tr:last").appendForm(url);
			}
			else if(this.tagName != "TR")
			{
				return $(this);
			}
		
			var obj = $(this);
			var id = className + '_' + url.replace(/[^\w]/g, "_");		
			var tableObj = obj.parents("table:first");
			
			if($('#' + id).length > 0)
			{
				return;
			}
			
			
			/*
			 * Create element
			 */
			
			var tableColumns = 0;
			obj.children("td").each(function() {
				tableColumns += this.colSpan;
			});
			
			var tr = document.createElement('tr');
			var td = document.createElement('td');
			var div = document.createElement('div');
			var trObj = $(tr);
			var tdObj = $(td);
			var divObj = $(div);
			
			trObj.addClass('noCheckboxes');
			trObj.addClass(className);
			trObj.attr("id", id);
			
			tdObj.attr("colspan", tableColumns);
			
			divObj.addClass(className);
			divObj.html('<span class="icon"><img src="' + baseUrl + '/images/loading.gif" alt="' + lang.get('core', 'loading') + '" class="icon icon16" /> <span>' + lang.get('core', 'loading') + '...</span></span>');
			
			divObj.appendTo(tdObj);
			tdObj.appendTo(trObj);
			trObj.insertAfter(obj);
			
			
			/*
			 * Fetch contents
			 */
			
			var setAjaxForms = function() {
				var forms = divObj.children('form');
				forms.submit(function(event) {
					$(this).parent().block({css: null, overlayCss: null, message: null});
				});
				forms.ajaxForm({
					success: function(response, statusText) {
						if(!AjaxResponse.handle(response, statusText))
						{
							divObj.html(response);
							setAjaxForms();	
						}
					}
				});
			};
			
			divObj.load(url, data, function() {
				divObj.slideDown(500);
				setAjaxForms();
				divObj.find('input:first').select();
			});
			
			var targetOffset = obj.position().top + $('#content').scrollTop();
			$('#content').animate({scrollTop: targetOffset}, 500);
			
			
			/*
			 * Return self
			 */
			
			return $(this);
		
		});
		
	}

})(jQuery);
