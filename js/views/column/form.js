/*
 * Chive - web based MySQL database management
 * Copyright (C) 2010 Fusonic GmbH
 *
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */

var columnForm = {
	
	setup: function(idPrefix) 
	{
		
		var type = $('#' + idPrefix + 'Column_dataType').val();
		
		$('#' + idPrefix + 'settingSize')[dataType.check(type, dataType.SUPPORTS_SIZE) ? "show" : "hide" ]();
		$('#' + idPrefix + 'settingScale')[dataType.check(type, dataType.SUPPORTS_SCALE) ? "show" : "hide" ]();
		$('#' + idPrefix + 'settingValues')[dataType.check(type, dataType.SUPPORTS_VALUES) ? "show" : "hide" ]();
		$('#' + idPrefix + 'settingCollation')[dataType.check(type, dataType.SUPPORTS_COLLATION) ? "show" : "hide" ]();
		
		// Attributes
		$('#' + idPrefix + 'Column_attribute_1').attr('disabled', !dataType.check(type, dataType.SUPPORTS_UNSIGNED));
		$('#' + idPrefix + 'Column_attribute_2').attr('disabled', !dataType.check(type, dataType.SUPPORTS_UNSIGNED_ZEROFILL));
		$('#' + idPrefix + 'Column_attribute_3').attr('disabled', !dataType.check(type, dataType.SUPPORTS_ON_UPDATE_CURRENT_TIMESTAMP));
		
		// Indices
		$('#' + idPrefix + 'createIndex').attr('disabled', !dataType.check(type, dataType.SUPPORTS_INDEX));
		$('#' + idPrefix + 'createIndexUnique').attr('disabled', !dataType.check(type, dataType.SUPPORTS_UNIQUE));
		$('#' + idPrefix + 'createIndexFulltext').attr('disabled', !dataType.check(type, dataType.SUPPORTS_FULLTEXT));
		
		// Auto_increment
		if($('#' + idPrefix + 'createIndexPrimary').length == 1)
		{
			var isPrimary = $('#' + idPrefix + 'createIndexPrimary').attr('checked');
		}
		else
		{
			eval('var isPrimary = isPrimary' + idPrefix);
		}
		$('#' + idPrefix + 'Column_autoIncrement').attr('disabled', !(dataType.check(type, dataType.SUPPORTS_AUTO_INCREMENT) && isPrimary));
		
		$('#' + idPrefix + 'Column_COLUMN_DEFAULT').attr('disabled', $('#' + idPrefix + 'Column_autoIncrement').attr('checked'));
		$('#' + idPrefix + 'Column_isNullable').attr('disabled', $('#' + idPrefix + 'Column_autoIncrement').attr('checked'));
		
		if($('#' + idPrefix + 'Column_isNullable').attr('checked') && !$('#' + idPrefix + 'Column_isNullable').attr('disabled'))
		{
			$('#' + idPrefix + 'settingDefaultNullHint').show();
		}
		else
		{
			$('#' + idPrefix + 'settingDefaultNullHint').hide();
		}
		
	},
	
	create: function(idPrefix)
	{
		$('#' + idPrefix + 'Column_dataType').change(new Function('columnForm.setup("' + idPrefix + '")'));
		$('#' + idPrefix + 'Column_autoIncrement').change(new Function('columnForm.setup("' + idPrefix + '")'));
		$('#' + idPrefix + 'createIndexPrimary').change(new Function('columnForm.setup("' + idPrefix + '")'));
		$('#' + idPrefix + 'Column_isNullable').change(new Function('columnForm.setup("' + idPrefix + '")'));
		columnForm.setup(idPrefix);
	}
	
};