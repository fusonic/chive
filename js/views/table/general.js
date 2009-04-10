/*
 * Truncate table
 */

function truncateTable(_schema, _table)
{
	schema = _schema;
	table = _table;
	$('#truncateTableDialog').dialog('open');	
}

/*
 * Drop table
 */

function dropTable(_schema, _table)
{
	schema = _schema;
	table = _table;
	
	$('#dropTableDialog').dialog('open');	
}

/*
 * Setup dialogs
 */
	
$(document).ready(function() {

	$('#truncateTableDialog').dialog({
		modal: true,
		resizable: false,
		autoOpen: false,
		buttons: {
			'No': function() {
				$(this).dialog('close');
			},
			'Yes': function() {
				// Do truncate request
				$.post(baseUrl + '/database/' + schema + '/tables/' + table + '/truncate', {
					table: table,
					schema: schema,
				}, function() {
					window.location.href = baseUrl + '/database/' + schema;
				});
				
				$(this).dialog('close');
			}
		}		
	});
	
	$('#dropTableDialog').dialog({
		modal: true,
		resizable: false,
		autoOpen: false,
		buttons: {
			'No': function() {
				$(this).dialog('close');
			},
			'Yes': function() {
				// Do drop request
				$.post(baseUrl + '/database/' + schema + '/tables/' + table + '/drop', {
					table: table,
					schema: schema,
				}, function() {
					handleAjaxRequest(responseText);
					window.location.href = baseUrl + '/database/' + schema;
				});
				
				$(this).dialog('close');
			}
		}		
	});
	
});

