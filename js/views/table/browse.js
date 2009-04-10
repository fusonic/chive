function deleteRow(_row) 
{
	_row.hide();
	return;

	$.post(baseUrl + '/database/' + schema + '/tables/' + table + '/truncate', {
		table: table,
		schema: schema,
	}, function() {
		window.location.href = baseUrl + '/database/' + schema;
	});
	
}
