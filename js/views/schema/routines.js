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

var schemaRoutines = {

    // Add procedure
    addProcedure: function(){
        $('#routines').appendForm(baseUrl + '/schema/' + schema + '/routineAction/create?type=procedure');
    },
    
    // Add function
    addFunction: function(){
        $('#routines').appendForm(baseUrl + '/schema/' + schema + '/routineAction/create?type=function');
    },
    
    // Edit routine
    editRoutine: function(routine){
        $('#routines_' + routine).appendForm(baseUrl + '/schema/' + schema + '/routines/' + routine + '/update');
    },
    
    // Drop view
    dropRoutine: function(routine){
        $('#routines input[type="checkbox"]').attr('checked', false).change();
        $('#routines input[type="checkbox"][value="' + routine + '"]').attr('checked', true).change();
        schemaRoutines.dropRoutines();
    },
    dropRoutines: function(){
        var routines = schemaRoutines.getSelectedIds();
        var ulObj = $('#dropRoutinesDialog ul');
        
        ulObj.html('');
        
        if (routines.length > 0) {
        
        
            for (var i = 0; i < routines.length; i++) {
                ulObj.append('<li>' + routines[i] + '</li>');
            }
            $('#dropRoutinesDialog').dialog("open");
        }
  
    },
    
    // Get selected id's
    getSelectedIds: function(){
        var ids = [];
        $('#routines input[name="routines[]"]:checked').each(function(){
            ids.push($(this).val());
        });
        return ids;
    },
    
    // Setup dialogs
    setupDialogs: function(){
        /*
         * Setup drop view dialog
         */
        var buttons = {};
        buttons[lang.get('core', 'no')] = function(){
            $(this).dialog('close');
        };
        buttons[lang.get('core', 'yes')] = function(){
            // Collect ids
            var ids = schemaRoutines.getSelectedIds();
            
            // Do drop request
            $.post(baseUrl + '/schema/' + schema + '/routineAction/drop', {
                'routines': ids
            }, AjaxResponse.handle);
            
            $(this).dialog('close');
        };
        $('div.ui-dialog>div[id="dropRoutinesDialog"]').remove();
        $('#dropRoutinesDialog').dialog({
            buttons: buttons
        });
        
    }
    
};
