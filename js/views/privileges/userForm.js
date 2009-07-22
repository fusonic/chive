var privilegesUserForm = {
	
	setup: function(idPrefix)
	{
		$('#' + idPrefix + 'User_plainPassword').attr('disabled', $('#' + idPrefix + 'User_keepPw').get(0).checked);
	},
	
	create: function()
	{
		$('#' + idPrefix + 'User_keepPw').change(new Function('privilegesUserForm.setup("' + idPrefix + '")'));
		privilegesUserForm.setup(idPrefix);
	}
	
};