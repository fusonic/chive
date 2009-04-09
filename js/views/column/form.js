$(document).ready(function() {
	var types = {
		numeric: ['bit', 'tinyint', 'bool', 'smallint', 'mediumint', 'int', 'bigint', 'float', 'double', 'decimal'],
		strings: ['char', 'varchar', 'tinytext', 'text', 'mediumtext', 'longtext', 'tinyblob', 'blob', 'mediumblob', 'longblob', 'binary', 'varbinary']
	};
	$('#' + idPrefix + 'Column_DATA_TYPE').change(function() {
		var type = $(this).val();
		var isNumeric = $.inArray(type, types.numeric) > -1;
		var isString = $.inArray(type, types.strings) > -1;

		// Hide all datatype fieldsets
		$('#' + idPrefix + 'dataTypeSet fieldset.datatypeSetting').hide();

		// Show datatype fieldsets
		if(isString)
		{
			$('#' + idPrefix + 'dataTypeSet fieldset.stringSetting').show();
		}
		if(isNumeric)
		{
			$('#' + idPrefix + 'dataTypeSet fieldset.numericSetting').show();
		}
		$('#' + idPrefix + 'dataTypeSet fieldset.' + type + 'Setting').show();

	});
	$('#' + idPrefix + 'Column_DATA_TYPE').change();
});