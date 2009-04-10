(function($) {	
	
	$.fn.addCheckboxes = function(name, options) {
		
		var options = $.extend($.fn.addCheckboxes.defaultSettings, options);
		
		return this.each(function() {
			
			var tableObj = $(this);
			
			if(!this.rows)
				return tableObj;
				
			tableObj.find("colGroup").prepend("<col class=\"checkbox\" />");
			
			tableObj.find("tr").each(function() {
				
				var rowObject = $(this);
				
				if(rowObject.hasClass("noCheckboxes"))
				{
					rowObject.children("td")[0].colSpan++;
					return;
				}
				
				// Find first cell in this row
				var firstCellElement = $(this).find("th, td")[0];
				var firstCellObject = $(firstCellElement);
				
				// Detect wether we are in the head or not
				var isHead = firstCellElement.tagName == "TH";
				
				// Create the new cell
				var cellElement = document.createElement(firstCellElement.tagName);
				var cellObject = $(cellElement);
				cellObject.css("width", "10px");
				
				// Create checkbox
				var checkboxElement = document.createElement("input");
				var checkboxObject = $(checkboxElement);
				checkboxObject.attr("type", "checkbox");
				if(!isHead) {
					checkboxObject.attr("name", name + "[]");
					checkboxObject.attr("value", rowObject.attr("id").replace(name + "_", ""));
				}
					
				// Configure checkbox actions
				if(isHead) {
					
					checkboxObject.change(function() {
						
						tableObj.find("tr").find("td input[type='checkbox']:first, th input[type='checkbox']:first").attr("checked", this.checked);
						
						if(this.checked)
							tableObj.find("tr").addClass("selected");
						else
							tableObj.find("tr").removeClass("selected");
							
					});
					
				}
				else {
					
					checkboxObject.change(function(event) {
						
						var allBoxes = tableObj.find("tr").find("td input:first[type='checkbox']").length;
						var checkedBoxes = tableObj.find("tr").find("td input:first[type='checkbox'][checked]").length;
						
						// Set head checkbox
						var headCheckboxObject = tableObj.find("tr").find("th input[type='checkbox']:first").attr("checked", checkedBoxes == allBoxes); 
						
						// Set row class
						if(this.checked)
							$(this).parents("tr").addClass("selected");
						else
							$(this).parents("tr").removeClass("selected");
							
						if(options.selectableRows)
							event.stopPropagation();
						
					});
					
					if(options.selectableRows)
						rowObject.find("a").click(function(event) {
							event.stopPropagation();
						});
					
				}

				// Add row click action
				if(options.selectableRows)
					rowObject.find("td").click(function() {
						checkboxObject.click();
						checkboxObject.change();
					});
				
				// Append checkbox to cell
				cellObject.append(checkboxObject);
				
				// Append cell to table row
				firstCellObject.before(cellObject);

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
