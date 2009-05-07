(function($) {
	
	$.fn.setupListFilter = function(inputObj) {
		
		return this.each(function() 
		{
			
			var list = $(this);
			var input = inputObj;
			var items = new Array();
			
			function selectResult(element) 
			{
				list.children("li").removeClass("listFilterSelected");
				if(element)
				{
					element.addClass("listFilterSelected");
				}
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
				else
				{
					doFilter();
				}
			}
			
			function moveUp()
			{
				var selectedResult = $(list.children("li.listFilterSelected:visible")[0]);
				var selectedNow = $(list.children("li:visible")[list.children("li:visible").length - 1]);
				if(selectedResult)
				{
					if(selectedResult.prevAll("li:visible").length > 0)
					{
						selectedNow = $(selectedResult.prevAll("li:visible")[0]);
					}
				}
				selectResult(selectedNow);
			}
			
			function moveDown()
			{
				var selectedResult = $(list.children("li.listFilterSelected:visible")[0]);
				var selectedNow = $(list.children("li:visible")[0]);
				if(selectedResult)
				{
					if(selectedResult.nextAll("li:visible").length > 0)
					{
						selectedNow = $(selectedResult.nextAll("li:visible")[0]);
					}
				}
				selectResult(selectedNow);
			}
			
			function performAction()
			{
				list.children("li.listFilterSelected:visible").children("a:first").each(function() {
					window.location.href = this.href;
				});
			}
			
			function doFilter() 
			{
				var searchString = input.val().toLowerCase();
				var selectedResult = $(list.children("li.listFilterSelected:visible")[0]);
				
				// Do filtering
				if(searchString == '')
				{
					for(var i = 0; i < items.length; i++)
					{
						items[i].obj.show();
					}
				}
				else
				{
					for(var i = 0; i < items.length; i++) 
					{
						if(items[i].text.indexOf(searchString) > -1)
						{
							items[i].obj.show();
						}
						else
						{
							items[i].obj.hide();
						}
					}
				}
				
				// Find new selected element
				var selectedNow = null;
				if(selectedResult)
				{
					if(selectedResult.is("li:visible"))
					{
						selectedNow = selectedResult;
					}
					else if(selectedResult.nextAll("li:visible").length > 0)
					{
						selectedNow = selectedResult.next("li:visible");
					}
				}
				selectResult(selectedNow);
			}
			
			
			/*
			 * Setup
			 */
			list.children("li").each(function() {
				var item = $(this);
				items.push({
					"text" : item.text().toLowerCase(),
					"obj" : item
				});
			});
			
			
			/*
			 * Bind event handlers
			 */
			input.keyup(keyUp);
			input.keydown(keyDown);
			input.blur(function() {
				selectResult(null); 
			});
			
			if(input.val() != '')
			{
				doFilter();
			}
			
		});
		
	}

})(jQuery);