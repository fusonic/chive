$(document).ready(function() {
	var types = {
		numeric: ['bit', 'tinyint', 'bool', 'smallint', 'mediumint', 'int', 'bigint', 'float', 'double', 'decimal'],
		strings: ['char', 'varchar', 'tinytext', 'text', 'mediumtext', 'longtext', 'tinyblob', 'blob', 'mediumblob', 'longblob', 'binary', 'varbinary']
	};
	$('#' + idPrefix + 'Column_dataType').change(function() {
		
		var type = $(this).val();
		var isNumeric = $.inArray(type, types.numeric) > -1;
		var isString = $.inArray(type, types.strings) > -1;

		// Hide all datatype settings
		$('#' + idPrefix + 'dataTypeSet fieldset.datatypeSetting').hide();
		$('#' + idPrefix + 'dataTypeSet fieldset.datatypeSetting input').attr('disabled', true);
		$('#' + idPrefix + 'dataTypeSet fieldset.datatypeSetting select').attr('disabled', true);
		// Show datatype settings for this type
		$('#' + idPrefix + 'dataTypeSet fieldset.all').show();
		$('#' + idPrefix + 'dataTypeSet fieldset.all input').attr('disabled', false);
		$('#' + idPrefix + 'dataTypeSet fieldset.all select').attr('disabled', false);
		$('#' + idPrefix + 'dataTypeSet fieldset.' + type).show();
		$('#' + idPrefix + 'dataTypeSet fieldset.' + type + ' input').attr('disabled', false);
		$('#' + idPrefix + 'dataTypeSet fieldset.' + type + ' select').attr('disabled', false);
		
		// Auto_increment
		$('#' + idPrefix + 'Column_autoIncrement').attr('disabled', !isNumeric);

	});
	$('#' + idPrefix + 'Column_dataType').change();
});