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
				
				if(editing)
					return;
					
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
					
					editing = true;
						
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