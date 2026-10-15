<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Provides code to be executed during the module uninstallation
 *
 * @see uninstall_plugin()
 *
 * @package    mod_istudy
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/user/profile/definelib.php');

/**
 * Custom uninstallation procedure
 */
function xmldb_istudy_uninstall() {
    global $DB;

    // Delete the iStudy user profile fields in the user accounts (user_info_data) and from user_info_field table.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'whichteacher']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'advisor']);
    profile_delete_field($field_id->id);
   
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'schoolid']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'distancelearningonly']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'middlename']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'otherlastname']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'gpa']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'creditsearned']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'creditsneeded']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'graduationyear']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'address']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'city']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'dob']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'parentname']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'homephone']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'daytimephone']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'studentcell']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'parentemail']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'previousschool']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'startdate']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'enddate']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'occupationaltraining']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'goal1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'goal2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'goal3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'goal4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'anticipateddate']);
    profile_delete_field($field_id->id); 
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'eval1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'eval2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'eval3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'eval4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'evaluationother']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'mannerofmeeting']);
    profile_delete_field($field_id->id);

    // Delete the FALL Courses user profile fields in the user accounts (user_info_data) and from user_info_field table.
    // Monday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaysession8']);
    profile_delete_field($field_id->id);
    // Tuesday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaysession8']);
    profile_delete_field($field_id->id);
    // Wednesday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaysession8']);
    profile_delete_field($field_id->id);
    // Thursday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaysession8']);
    profile_delete_field($field_id->id);
    // Friday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaysession8']);
    profile_delete_field($field_id->id); 
    // Delete the FALL Course Credits user profile fields in the user accounts (user_info_data) and from user_info_field table.
     // Monday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallmondaycreditssession8']);
    profile_delete_field($field_id->id);
    // Tuesday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'falltuesdaycreditssession8']);
    profile_delete_field($field_id->id);
    // Wednesday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallwednesdaycreditssession8']);
    profile_delete_field($field_id->id);
    // Thursday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallthursdaycreditssession8']);
    profile_delete_field($field_id->id);
    // Friday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'fallfridaycreditssession8']);
    profile_delete_field($field_id->id);

    // Delete the SPRING Courses user profile fields in the user accounts (user_info_data) and from user_info_field table.
    // Monday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaysession8']);
    profile_delete_field($field_id->id);
    // Tuesday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaysession8']);
    profile_delete_field($field_id->id);
    // Wednesday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaysession8']);
    profile_delete_field($field_id->id);
    // Thursday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaysession8']);
    profile_delete_field($field_id->id);
    // Friday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaysession8']);
    profile_delete_field($field_id->id);
    // Delete the SPRING Course Credits user profile fields in the user accounts (user_info_data) and from user_info_field table.
     // Monday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springmondaycreditssession8']);
    profile_delete_field($field_id->id);
    // Tuesday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springtuesdaycreditssession8']);
    profile_delete_field($field_id->id);
    // Wednesday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springwednesdaycreditssession8']);
    profile_delete_field($field_id->id);
    // Thursday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springthursdaycreditssession8']);
    profile_delete_field($field_id->id);
    // Friday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'springfridaycreditssession8']);
    profile_delete_field($field_id->id);

    // Delete the SUMMER Courses user profile fields in the user accounts (user_info_data) and from user_info_field table.
    // Monday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaysession8']);
    profile_delete_field($field_id->id);
    // Tuesday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaysession8']);
    profile_delete_field($field_id->id);
    // Wednesday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaysession8']);
    profile_delete_field($field_id->id);
    // Thursday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaysession8']);
    profile_delete_field($field_id->id);
    // Friday courses are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaysession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaysession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaysession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaysession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaysession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaysession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaysession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaysession8']);
    profile_delete_field($field_id->id);
    // Delete the SUMMER Course Credits user profile fields in the user accounts (user_info_data) and from user_info_field table.
     // Monday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summermondaycreditssession8']);
    profile_delete_field($field_id->id);
    // Tuesday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summertuesdaycreditssession8']);
    profile_delete_field($field_id->id);
    // Wednesday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerwednesdaycreditssession8']);
    profile_delete_field($field_id->id);
    // Thursday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerthursdaycreditssession8']);
    profile_delete_field($field_id->id);
    // Friday course credits are deleted here.
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaycreditssession1']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaycreditssession2']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaycreditssession3']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaycreditssession4']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaycreditssession5']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaycreditssession6']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaycreditssession7']);
    profile_delete_field($field_id->id);
    $field_id = $DB->get_record('user_info_field', ['shortname'=>'summerfridaycreditssession8']);
    profile_delete_field($field_id->id); 
    
    // Delete the categories for the user profile fields.
    $category_id = $DB->get_record('user_info_category', ['name'=>'iStudy - Student Profile']);
    profile_delete_category($category_id->id);
    $category_id = $DB->get_record('user_info_category', ['name'=>'iStudy - Fall Courses']);
    profile_delete_category($category_id->id);
    $category_id = $DB->get_record('user_info_category', ['name'=>'iStudy - Spring Courses']);
    profile_delete_category($category_id->id);
    $category_id = $DB->get_record('user_info_category', ['name'=>'iStudy - Summer Courses']);
    profile_delete_category($category_id->id);
    
    return true;
}