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
							var checkedBoxes = bodyBoxes.filter('input[checked]').length;
	
							// Set head checkbox
							headBoxes.each(function() {
								this.checked = checkedBoxes == bodyBoxes.length;
							}); 
							
							// Set row class
							if(e.target.checked)
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