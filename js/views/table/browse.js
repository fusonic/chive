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

var tableBrowse = {

    // Add column
    deleteRow: function(row){
        $('#browse input[type="checkbox"]').attr('checked', false).change();
        $('#browse input[type="checkbox"]').eq(row + 1).attr('checked', true).change();
        tableBrowse.deleteRows();
    },
    
    // Delete rows
    deleteRows: function(){
    
        var rows = tableBrowse.getSelectedIds();
        if (rows.length > 0) {
            $('#deleteRowDialog').dialog("open");
        }
    },
    
    // Export rows
    exportRows: function(){
        if ($('#browse input[name="browse[]"]:checked').length > 0) {
            console.log("implement export");
        }
    },
    
    // Edit row
    editRow: function(rowIndex){
        $('#browse tr').eq(rowIndex + 1).appendForm(baseUrl + '/row/edit', {
            attributes: JSON.stringify(keyData[rowIndex]),
            schema: schema,
            table: table
        });
    },
    
    // Get selected id's
    getSelectedIds: function(){
        var data = [];
        $('#browse input[name="browse[]"]').each(function(i, o){
            if ($(this).attr('checked')) {
                data.push(keyData[i]);
            }
        });
        return data;
    },
    
    // Setup
    setup: function(){
        /*
         * Delete row
         */
        var buttons = {};
        buttons[lang.get('core', 'no')] = function(){
            $(this).dialog('close');
        };
        buttons[lang.get('core', 'yes')] = function(){
            // Collect ids			
            var data = tableBrowse.getSelectedIds();
            
            // Do truncate request
            $.post(baseUrl + '/row/delete', {
                data: JSON.stringify(data),
                schema: schema,
                table: table
            }, AjaxResponse.handle);
            
            $(this).dialog('close');
        };
        $('#deleteRowDialog').dialog({
            buttons: buttons
        });
    }
    
};
