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
			var allBoxes = tbodyObj.find('input[type="checkbox"]').length;
			
			if(options.selectableRows)
			{
				tbodyObj.click(function(e) {
					if(e.target.tagName != 'INPUT' && e.target.tagName != 'A' && e.target.parentNode.tagName != 'A')
					{
						$(e.target).closest('tr').find('input[type="checkbox"]').click().change();
					}
				});
			}
				
			tableObj.find('tr').each(function() {
				
				var rowObj = $(this);
				var isHead = this.parentNode.tagName != 'TBODY';
				var checkboxObject = rowObj.find('input[type="checkbox"]');
					
				// Configure checkbox actions
				if(isHead) {
					
					checkboxObject.change(function() {
						var checked = this.checked;
						var checkboxes = tableObj.find("input[type='checkbox']").each(function() {
							this.checked = checked;
						});
						if(checked)
						{
							tableObj.children('tbody').children('tr').addClass('selected');
						}
						else
						{
							tableObj.children('tbody').children('tr').removeClass('selected');
						}
					});
					
				}
				else {
					
					checkboxObject.change(function(event) {
						var checkedBoxes = tableObj.find("tbody input[type='checkbox'][checked]").length;
						
						// Set head checkbox
						tableObj.find("th input[type='checkbox']").each(function() {
							this.checked = checkedBoxes == allBoxes;
						}); 
						
						// Set row class
						if(this.checked)
						{
							rowObj.addClass("selected");
						}
						else
						{
							rowObj.removeClass("selected");
						}
					});

				}

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