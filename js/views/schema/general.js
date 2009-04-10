/*
 * Drop table
 */

function dropSchema(_schema)
{
	schema = _schema;
	$('#dropSchemaDialog').dialog('open');
}

/*
 * Setup dialogs
 */
	
$(document).ready(function() {

	$('#dropSchemaDialog').dialog({
		modal: true,
		resizable: false,
		autoOpen: false,
		buttons: {
			'No': function() {
				$(this).dialog('close');
			},
			'Yes': function() {
				// Do truncate request
				$.post(baseUrl + '/schemata/drop', {
					schema: schema
				}, function() {
					window.location.href = baseUrl;
				});
				
				$(this).dialog('close');
			}
		}		
	});
	
});