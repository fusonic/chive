/* ***** BEGIN LICENSE BLOCK *****
 * Version: MPL 1.1/GPL 2.0/LGPL 2.1
 *
 * The contents of this file are subject to the Mozilla Public License Version
 * 1.1 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * The Original Code is Ajax.org Code Editor (ACE).
 *
 * The Initial Developer of the Original Code is
 * Ajax.org B.V.
 * Portions created by the Initial Developer are Copyright (C) 2010
 * the Initial Developer. All Rights Reserved.
 *
 * Contributor(s):
 *      Fabian Jakobs <fabian AT ajax DOT org> (tomorrow theme)
 *      Fusonic GmbH (chive theme, based on tomorrow theme)
 *
 * Alternatively, the contents of this file may be used under the terms of
 * either the GNU General Public License Version 2 or later (the "GPL"), or
 * the GNU Lesser General Public License Version 2.1 or later (the "LGPL"),
 * in which case the provisions of the GPL or the LGPL are applicable instead
 * of those above. If you wish to allow use of your version of this file only
 * under the terms of either the GPL or the LGPL, and not to allow others to
 * use your version of this file under the terms of the MPL, indicate your
 * decision by deleting the provisions above and replace them with the notice
 * and other provisions required by the GPL or the LGPL. If you do not delete
 * the provisions above, a recipient may use your version of this file under
 * the terms of any one of the MPL, the GPL or the LGPL.
 *
 * ***** END LICENSE BLOCK ***** */

define('ace/theme/chive', ['require', 'exports', 'module' , 'ace/lib/dom'], function(require, exports, module) {

	exports.isDark = false;
	exports.cssClass = "ace-chive";
	exports.cssText = "\
.ace-chive .ace_editor {\
  border: 2px solid rgb(159, 159, 159);\
}\
\
.ace-chive .ace_editor.ace_focus {\
  border: 2px solid #327fbd;\
}\
\
.ace-chive .ace_gutter {\
  background-color: #F7F7F7;\
  color: #ccc;\
  border-right: 1px solid #ccc;\
}\
\
.ace-chive .ace_gutter-cell {\
  text-align: right;\
  min-width: 30px;\
  padding: 0px 5px;\
}\
\
.ace-chive .ace_print_margin {\
  width: 1px;\
  background: #e8e8e8;\
}\
\
.ace-chive .ace_scroller {\
  background-color: #F7F7F7;\
}\
\
.ace-chive .ace_text-layer {\
  cursor: text;\
  color: #444;\
}\
\
.ace-chive .ace_cursor {\
  border-left: 2px solid #AEAFAD;\
}\
\
.ace-chive .ace_cursor.ace_overwrite {\
  border-left: 0px;\
  border-bottom: 1px solid #AEAFAD;\
}\
 \
.ace-chive .ace_marker-layer .ace_selection {\
  background: #D6D6D6;\
}\
\
.ace-chive .ace_marker-layer .ace_step {\
  background: rgb(198, 219, 174);\
}\
\
.ace-chive .ace_marker-layer .ace_bracket {\
  margin: -1px 0 0 -1px;\
  border: 1px solid #D1D1D1;\
}\
\
.ace-chive .ace_marker-layer .ace_active_line {\
  background: #EFEFEF;\
}\
\
.ace-chive .ace_marker-layer .ace_selected_word {\
  border: 1px solid #D6D6D6;\
}\
       \
.ace-chive .ace_invisible {\
  color: #D1D1D1;\
}\
\
.ace-chive .ace_identifier {\
  color: #879EFA;\
}\
\
.ace-chive .ace_keyword, .ace-chive .ace_meta {\
  color:#60CA00;\
}\
\
.ace-chive .ace_keyword.ace_operator {\
  color:magenta;\
}\
\
.ace-chive .ace_constant.ace_language {\
  color:#F5871F;\
}\
\
.ace-chive .ace_constant.ace_numeric {\
  color:#F5871F;\
}\
\
.ace-chive .ace_constant.ace_other {\
  color:#666969;\
}\
\
.ace-chive .ace_invalid {\
  color:#FFFFFF;\
background-color:#C82829;\
}\
\
.ace-chive .ace_invalid.ace_deprecated {\
  color:#FFFFFF;\
background-color:#8959A8;\
}\
\
.ace-chive .ace_support.ace_constant {\
  color:#F5871F;\
}\
\
.ace-chive .ace_fold {\
    background-color: #4271AE;\
    border-color: #4D4D4C;\
}\
\
.ace-chive .ace_support.ace_function {\
  color:#4271AE;\
}\
\
.ace-chive .ace_storage {\
  color:#8959A8;\
}\
\
.ace-chive .ace_storage.ace_type,  .ace-chive .ace_support.ace_type{\
  color:#8959A8;\
}\
\
.ace-chive .ace_variable {\
  color:#4271AE;\
}\
\
.ace-chive .ace_variable.ace_parameter {\
  color:#F5871F;\
}\
\
.ace-chive .ace_string {\
  color:#879EFA;\
}\
\
.ace-chive .ace_string.ace_regexp {\
  color:#879EFA;\
}\
\
.ace-chive .ace_comment {\
  color:#8E908C;\
}\
\
.ace-chive .ace_variable {\
  color:#C82829;\
}\
\
.ace-chive .ace_meta.ace_tag {\
  color:#C82829;\
}\
\
.ace-chive .ace_entity.ace_other.ace_attribute-name {\
  color:#C82829;\
}\
\
.ace-chive .ace_entity.ace_name.ace_function {\
  color:#4271AE;\
}\
\
.ace-chive .ace_markup.ace_underline {\
    text-decoration:underline;\
}\
\
.ace-chive .ace_markup.ace_heading {\
  color:#718C00;\
}";

	var dom = require("../lib/dom");
	dom.importCssString(exports.cssText, exports.cssClass);
});