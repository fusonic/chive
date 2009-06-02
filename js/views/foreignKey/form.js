var foreignKeyForm = {
	
	/*
	 * Setup form
	 */
	setup: function(idPrefix)
	{
		var val = $('#' + idPrefix + 'ForeignKey_references').val();
		$('#' + idPrefix + 'ForeignKey_onDelete').attr('disabled', val == '');
		$('#' + idPrefix + 'ForeignKey_onUpdate').attr('disabled', val == '');
	},
	
	create: function(idPrefix)
	{	
		foreignKeyForm.setup(idPrefix);
		$('#' + idPrefix + 'ForeignKey_references').change(new Function('foreignKeyForm.setup("' + idPrefix + '")'));
	}
	
};