var globalExport = {
	
	view: function()
	{
		$('#Export_action').val('view');
		
		// Send form with ajax
		$('#Export').ajaxSubmit({
			success: function(responseText)
			{
				AjaxResponse.handle(responseText);
				$('div.ui-layout-center').html(responseText);
				init();
			}
		});
	},
	
	save: function()
	{
		$('#Export_action').val('save');
		$('#Export').submit();
	},
	
	setup: function()
	{
		// Hide all settings divs and show the first
		$('#exporterSettings>div').hide();
		$('#exporterSettings>div:first').show();
		
		// Setup click handlers for checkboxes
		$('#exporterType>input').click(function() {
			$('#exporterSettings>div').hide();
			$('#exporterSettings_' + this.value).show();
		});
	}
	
};