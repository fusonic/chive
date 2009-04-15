(function($) {
	
	$.fn.setupListFilter = function(inputObj) {
		
		return this.each(function() 
		{
			
			var list = $(this);
			var input = inputObj;
			var items = new Array();
			var moveTimeout = null;
			
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
					moveDown(200);
				}
				else if(e.keyCode == 38) // Up arrow
				{
					moveUp(200);
				}
			}
			
			function keyUp(e) 
			{
				window.clearTimeout(moveTimeout);
				if(e.keyCode == 13) // Enter
				{
					performAction();
				}
				else
				{
					doFilter();
				}
			}
			
			function moveUp(_timeout)
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
				if(!_timeout)
					_timeout = 40;				
				moveTimeout = window.setTimeout(moveUp, _timeout);
			}
			
			function moveDown(_timeout)
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
				if(!_timeout)
					_timeout = 40;				
				moveTimeout = window.setTimeout(moveDown, _timeout);
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
				items.push({
					"text" : $(this).text().toLowerCase(),
					"obj" : $(this)
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
			
			doFilter();
			
		});
		
	}

})(jQuery);