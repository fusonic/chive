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

var tableForm = {
	
	setup: function(idPrefix)
	{
		var engine = $('#' + idPrefix + 'Table_ENGINE').val();
		
		$('#' + idPrefix + 'Table_optionPackKeys').attr('disabled', !storageEngine.check(engine, storageEngine.SUPPORTS_PACK_KEYS));
		$('#' + idPrefix + 'Table_optionDelayKeyWrite').attr('disabled', !storageEngine.check(engine, storageEngine.SUPPORTS_DELAY_KEY_WRITE));
		$('#' + idPrefix + 'Table_optionChecksum').attr('disabled', !storageEngine.check(engine, storageEngine.SUPPORTS_CHECKSUM));
	},
	
	create: function(idPrefix)
	{
		$('#' + idPrefix + 'Table_ENGINE').change(new Function('tableForm.setup("' + idPrefix + '")'));
		tableForm.setup(idPrefix);
	}
	
};