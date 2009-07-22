var privilegesUsers = {
	
	// Add user
	addUser: function()
	{
		$('#users').appendForm(baseUrl + '/privileges/userActions/create');
	},
	
	// Edit user
	editUser: function(id, user, host)
	{
		$('#users_' + id).appendForm(baseUrl + '/privileges/userActions/update?user=' + user + '&host=' + host);
	},
	
	// Drop user
	dropUsers: function()
	{
		if($('#users input[name="users[]"]:checked').length > 0) 
		{
			$('#dropUsersDialog').dialog("open");
		}
	},
	dropUser: function(user, host)
	{
		$('#users input[type="checkbox"]').attr('checked', false).change();
		$('#users input[type="checkbox"][value="\'' + user + '\'@\'' + host + '\'"]').attr('checked', true).change();
		privilegesUsers.dropUsers();
	},
	
	// Setup dialogs
	setup: function()
	{
		/*
		 * Drop schemata
		 */
		var buttons = {};
		buttons[lang.get('core', 'no')] = function() 
		{
			$(this).dialog('close');
		}; 
		buttons[lang.get('core', 'yes')] = function() 
		{
			// Collect ids
			var ids = [];
			$('#users input[name="users[]"]:checked').each(function() {
				ids.push($(this).val());
			});
			
			// Do drop request
			$.post(baseUrl + '/privileges/userActions/drop', {
				'users[]': ids
			}, AjaxResponse.handle);
			
			$(this).dialog('close');
		}; 
		$('#dropUsersDialog').dialog({
			buttons: buttons	
		});
	}
	
};