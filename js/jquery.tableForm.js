(function($) {	
	
	$.fn.appendForm = function(url)
	{
		
		return this.each(function() {
			
			if(this.tagName == "TABLE")
			{
				return $(this).children("tbody tr:last").appendForm(url);
			}
			else if(this.tagName != "TR")
			{
				return $(this);
			}
		
			var obj = $(this);
			var id = "form_" + url.replace(/[^\w]/g, "_");		
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
			
			var tr = document.createElement("tr");
			var td = document.createElement("td");
			var div = document.createElement("div");
			var trObj = $(tr);
			var tdObj = $(td);
			var divObj = $(div);
			
			trObj.addClass("noCheckboxes");
			trObj.addClass("form");
			trObj.attr("id", id);
			
			tdObj.attr("colspan", tableColumns);
			
			divObj.css("display", "none");
			
			divObj.appendTo(tdObj);
			tdObj.appendTo(trObj);
			trObj.insertAfter(obj);
			
			
			/*
			 * Fetch contents
			 */
			
			tdObj.load(url, function() {
				divObj.slideDown();
				$('div.ui-layout-center').animate({
					scrollTop: trObj.offset().top + $('div.ui-layout-center').scrollTop() - $('div.ui-layout-north').outerHeight()
				}, 1000);
			});
			
			
			/*
			 * Return self
			 */
			
			return $(this);
		
		});
		
	}

})(jQuery);
