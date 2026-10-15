<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Code to be executed after the plugin's database scheme has been installed is defined here.
 *
 * @package    mod_istudy
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/user/profile/definelib.php');

/**
 * Post installation procedure
 *
 * @see upgrade_plugins_modules()
 */
 
function xmldb_istudy_install() {	

        // Create the 4 categories - Student Profile, Fall Courses, Spring Courses, and Summer Courses.
        $category_info = new stdClass();
        $category_info->id = null;
        $category_info->name = 'iStudy - Student Profile';
        $category_info->sortorder = 1;
        profile_save_category($category_info);
        $category_profileid = $category_info->id;
        $category_info->id = null;
        $category_info->name = 'iStudy - Fall Courses';
        $category_info->sortorder = 2;
        profile_save_category($category_info);        
        $category_fallid = $category_info->id;
        $category_info->id = null;
        $category_info->name = 'iStudy - Spring Courses';
        $category_info->sortorder = 3;
        profile_save_category($category_info);        
        $category_springid = $category_info->id;
        $category_info->id = null;
        $category_info->name = 'iStudy - Summer Courses';
        $category_info->sortorder = 4;
        profile_save_category($category_info);        
        $category_summerid = $category_info->id;
        profile_reorder_categories();

        // iStudy - Student Profile Information
        // Breakdown of function - save_profile_field('menu type', 'name', 'description', 'category id', 'sort order', 'defaultdata', 'param1', 'param2');

        // Create the first 3 menus.
        $whichteacher_menu = "Student\nTeacher\nAdministrator\nParent";
        $advisor_menu = "None\nThere are no advisors";
        $distancelearning_menu = "Yes\nNo";

        // Update the graduation year menu - range and set default year.
        $graduation_year = new stdClass();
        $currentyear = date('Y');
        $currentmonth = date('m');
        // If current in first semester, the end of the school year will be next year.
        if ($currentmonth > 6) {
                $currentyear = $currentyear +1;
        }
        $graduation_year->defaultdata = $currentyear;
        $year_range = '';
        for ($i = ($currentyear - 2); $i <= ($currentyear + 10); $i++) {
                $year_range = $year_range . $i . "\n";
        }
        $graduation_year->menu = custom_trim($year_range);

        // Copy the list of city-state-zip code to the appropriate drop-down menu.
        $city_menu = "None\nMadera, CA 93636\nMadera, CA 93637\nMadera, CA 93638\nMadera, CA 93639";
        
        // Update settings for birthday.
        $end_year = date('Y');
        $start_year = $end_year - 23;
        $date_of_birth = new stdClass();
        $date_of_birth->startyear = $start_year;
        $date_of_birth->endyear = $end_year;

        // Update settings for start date.
        $end_year = date('Y') + 6;
        $start_year = $end_year - 13;
        $start_date = new stdClass();
        $start_date->startyear = $start_year;
        $start_date->end_year = $end_year;

        // Update settings for end date.
        $end_date = new stdClass();
        $end_date->startyear = $start_year;
        $end_date->endyear = $end_year;
        // Update settings for anticipated date.
        $anticipated_date = new stdClass();
        $anticipated_date->startyear = $start_year;
        $anticipated_date->endyear = $end_year;
        
        // The student profile menus start here.
        save_profile_field('menu', 'whichteacher', 'User Status (Default: Student)', $category_profileid, 1, 'Student', $whichteacher_menu, null);
        save_profile_field('menu', 'advisor', 'Advisor (If STUDENT chosen above, choose advisor)', $category_profileid, 2, 'None', $advisor_menu, null);
        save_profile_field('text', 'schoolid', 'Student ID', $category_profileid, 3, '', 30, 2048);
        save_profile_field('menu', 'distancelearningonly', 'Distance learning for all classes)', $category_profileid, 4, 'No', $distancelearning_menu, null);
        save_profile_field('text', 'middlename', 'Middle Name', $category_profileid, 5, '', 30, 2048);
        save_profile_field('text', 'otherlastname', 'Other Last Name', $category_profileid, 6, '', 30, 2048);
        save_profile_field('text', 'gpa', 'G.P.A.', $category_profileid, 7, '', 30, 2048);
        save_profile_field('text', 'creditsearned', 'Credits Earned', $category_profileid, 8, '', 30, 2048);
        save_profile_field('text', 'creditsneeded', 'Credits Needed', $category_profileid, 9, '', 30, 2048);
        save_profile_field('menu', 'graduationyear', 'Graduation Year', $category_profileid, 10,  $graduation_year->defaultdata, $graduation_year->menu, null);
        save_profile_field('text', 'address', 'Street Address', $category_profileid, 11, '', 30, 2048);
        save_profile_field('menu', 'city', 'City, State Zip', $category_profileid, 12, 'None', $city_menu, null);
        save_profile_field('datetime', 'dob', 'Date of Birth', $category_profileid, 13, '0', $date_of_birth->startyear, $date_of_birth->endyear);
        save_profile_field('text', 'parentname', 'Parent Name', $category_profileid, 14, '', 30, 2048);
        save_profile_field('text', 'homephone', 'Home/Cell Phone #', $category_profileid, 15, '', 30, 2048);
        save_profile_field('text', 'daytimephone', 'Daytime Phone #', $category_profileid, 16, '', 30, 2048);
        save_profile_field('text', 'studentcell', 'Student Cell #', $category_profileid, 17, '', 30, 2048);
        save_profile_field('text', 'parentemail', 'Parent Email', $category_profileid, 18, '', 30, 2048);
        save_profile_field('text', 'previousschool', 'Previous School', $category_profileid, 19, '', 30, 2048);
        save_profile_field('datetime', 'startdate', 'Start Date', $category_profileid, 20, '0', $start_date->startyear, $date_of_birth->endyear);
        save_profile_field('datetime', 'enddate', 'End Date', $category_profileid, 21, '0',  $end_date->startyear, $end_date->endyear);
        save_profile_field('text', 'occupationaltraining', 'Occuplational Training (G2)', $category_profileid, 22, '', 30, 2048);
        save_profile_field('checkbox', 'goal1', 'Goal: Furman High School Diploma (230 credits)', $category_profileid, 23, 0, null, null);
        save_profile_field('checkbox', 'goal2', 'Goal: Madera Adult School (180 credits)', $category_profileid, 24, 0, null, null);
        save_profile_field('checkbox', 'goal3', 'Goal: Transfer to MSH/MSHS/MTHS', $category_profileid, 25, 0, null, null);
        save_profile_field('text', 'goal4', 'Goal: Other', $category_profileid, 26, '', 30, 2048);
        save_profile_field('datetime', 'anticipateddate', 'Anticipated Date', $category_profileid, 27, '0', $anticipated_date->startyear, $anticipated_date->endyear);
        save_profile_field('checkbox', 'eval1', 'Means of Evaluation: Tests', $category_profileid, 28, 0, null, null);
        save_profile_field('checkbox', 'eval2', 'Means of Evaluation: Written Work', $category_profileid, 29, 0, null, null);
        save_profile_field('checkbox', 'eval3', 'Means of Evaluation: Demonstration', $category_profileid, 30, 0, null, null);
        save_profile_field('checkbox', 'eval4', 'Means of Evaluation: Oral Presentation', $category_profileid, 31, 0, null, null);
        save_profile_field('text', 'eval5', 'Means of Evaluation: Other', $category_profileid, 32, '', 30, 2048);
        save_profile_field('text', 'mannerofmeeting', 'Manner of Meeting', $category_profileid, 33, '', 30, 2048);

        // iStudy - Fall Courses

        // Create menus - course menu and course credits.
        $course_list = "None\nNo courses available yet";
        $credits_list = "0\n5\n4\n3\n2\n1\n4.5\n3.5\n2.5\n1.5\n0.5";

        // Monday sessions
        save_profile_field('menu', 'fallmondaysession1', 'Monday - Session 1 -> Subject/Teacher/Section Number', $category_fallid, 1, 'None', $course_list, null);
        save_profile_field('menu', 'fallmondaycreditssession1', '--------- Session 1 -> Credits Needed', $category_fallid, 2, '0', $credits_list, null);
        save_profile_field('menu', 'fallmondaysession2', 'Monday - Session 2', $category_fallid, 3, 'None', $course_list, null);
        save_profile_field('menu', 'fallmondaycreditssession2', '--------- Session 2 -> Credits Needed', $category_fallid, 4, '0', $credits_list, null);
        save_profile_field('menu', 'fallmondaysession3', 'Monday - Session 3', $category_fallid, 5, 'None', $course_list, null);
        save_profile_field('menu', 'fallmondaycreditssession3', '--------- Session 3 -> Credits Needed', $category_fallid,6, '0', $credits_list, null);
        save_profile_field('menu', 'fallmondaysession4', 'Monday - Session 4', $category_fallid, 7, 'None', $course_list, null);
        save_profile_field('menu', 'fallmondaycreditssession4', '--------- Session 4 -> Credits Needed', $category_fallid, 8, '0', $credits_list, null);
        save_profile_field('menu', 'fallmondaysession5', 'Monday - Session 5', $category_fallid, 9, 'None', $course_list, null);
        save_profile_field('menu', 'fallmondaycreditssession5', '--------- Session 5 -> Credits Needed', $category_fallid, 10, '0', $credits_list, null);
        save_profile_field('menu', 'fallmondaysession6', 'Monday - Session 6', $category_fallid, 11, 'None', $course_list, null);
        save_profile_field('menu', 'fallmondaycreditssession6', '--------- Session 6 -> Credits Needed', $category_fallid, 12, '0', $credits_list, null);
        save_profile_field('menu', 'fallmondaysession7', 'Monday - Session 7', $category_fallid, 13, 'None', $course_list, null);
        save_profile_field('menu', 'fallmondaycreditssession7', '--------- Session 7 -> Credits Needed', $category_fallid, 14, '0', $credits_list, null);
        save_profile_field('menu', 'fallmondaysession8', 'Monday - Session 8', $category_fallid, 15, 'None', $course_list, null);
        save_profile_field('menu', 'fallmondaycreditssession8', '--------- Session 8 -> Credits Needed', $category_fallid, 16, '0', $credits_list, null);

        // Tuesday sessions
        save_profile_field('menu', 'falltuesdaysession1', 'Tuesday - Session 1 -> Subject/Teacher/Section Number', $category_fallid, 17, 'None', $course_list, null);
        save_profile_field('menu', 'falltuesdaycreditssession1', '--------- Session 1 -> Credits Needed', $category_fallid, 18, '0', $credits_list, null);
        save_profile_field('menu', 'falltuesdaysession2', 'Tuesday - Session 2', $category_fallid, 19, 'None', $course_list, null);
        save_profile_field('menu', 'falltuesdaycreditssession2', '--------- Session 2 -> Credits Needed', $category_fallid, 20, '0', $credits_list, null);
        save_profile_field('menu', 'falltuesdaysession3', 'Tuesday - Session 3', $category_fallid, 21, 'None', $course_list, null);
        save_profile_field('menu', 'falltuesdaycreditssession3', '--------- Session 3 -> Credits Needed', $category_fallid, 22, '0', $credits_list, null);
        save_profile_field('menu', 'falltuesdaysession4', 'Tuesday - Session 4', $category_fallid, 23, 'None', $course_list, null);
        save_profile_field('menu', 'falltuesdaycreditssession4', '--------- Session 4 -> Credits Needed', $category_fallid, 24, '0', $credits_list, null);
        save_profile_field('menu', 'falltuesdaysession5', 'Tuesday - Session 5', $category_fallid, 25, 'None', $course_list, null);
        save_profile_field('menu', 'falltuesdaycreditssession5', '--------- Session 5 -> Credits Needed', $category_fallid, 26, '0', $credits_list, null);
        save_profile_field('menu', 'falltuesdaysession6', 'Tuesday - Session 6', $category_fallid, 27, 'None', $course_list, null);
        save_profile_field('menu', 'falltuesdaycreditssession6', '--------- Session 6 -> Credits Needed', $category_fallid, 28, '0', $credits_list, null);
        save_profile_field('menu', 'falltuesdaysession7', 'Tuesday - Session 7', $category_fallid, 29, 'None', $course_list, null);
        save_profile_field('menu', 'falltuesdaycreditssession7', '--------- Session 7 -> Credits Needed', $category_fallid, 30, '0', $credits_list, null);
        save_profile_field('menu', 'falltuesdaysession8', 'Tuesday - Session 8', $category_fallid, 31, 'None', $course_list, null);
        save_profile_field('menu', 'falltuesdaycreditssession8', '--------- Session 8 -> Credits Needed', $category_fallid, 32, '0', $credits_list, null);

        // Wednesday sessions
        save_profile_field('menu', 'fallwednesdaysession1', 'Wednesday - Session 1 -> Subject/Teacher/Section Number', $category_fallid, 33, 'None', $course_list, null);
        save_profile_field('menu', 'fallwednesdaycreditssession1', '--------- Session 1 -> Credits Needed', $category_fallid, 34, '0', $credits_list, null);
        save_profile_field('menu', 'fallwednesdaysession2', 'Wednesday - Session 2', $category_fallid, 35, 'None', $course_list, null);
        save_profile_field('menu', 'fallwednesdaycreditssession2', '--------- Session 2 -> Credits Needed', $category_fallid, 36, '0', $credits_list, null);
        save_profile_field('menu', 'fallwednesdaysession3', 'Wednesday - Session 3', $category_fallid, 37, 'None', $course_list, null);
        save_profile_field('menu', 'fallwednesdaycreditssession3', '--------- Session 3 -> Credits Needed', $category_fallid, 38, '0', $credits_list, null);
        save_profile_field('menu', 'fallwednesdaysession4', 'Wednesday - Session 4', $category_fallid, 39, 'None', $course_list, null);
        save_profile_field('menu', 'fallwednesdaycreditssession4', '--------- Session 4 -> Credits Needed', $category_fallid, 40, '0', $credits_list, null);
        save_profile_field('menu', 'fallwednesdaysession5', 'Wednesday - Session 5', $category_fallid, 41, 'None', $course_list, null);
        save_profile_field('menu', 'fallwednesdaycreditssession5', '--------- Session 5 -> Credits Needed', $category_fallid, 42, '0', $credits_list, null);
        save_profile_field('menu', 'fallwednesdaysession6', 'Wednesday - Session 6', $category_fallid, 43, 'None', $course_list, null);
        save_profile_field('menu', 'fallwednesdaycreditssession6', '--------- Session 6 -> Credits Needed', $category_fallid, 44, '0', $credits_list, null);
        save_profile_field('menu', 'fallwednesdaysession7', 'Wednesday - Session 7', $category_fallid, 45, 'None', $course_list, null);
        save_profile_field('menu', 'fallwednesdaycreditssession7', '--------- Session 7 -> Credits Needed', $category_fallid, 46, '0', $credits_list, null);
        save_profile_field('menu', 'fallwednesdaysession8', 'Wednesday - Session 8', $category_fallid, 47, 'None', $course_list, null);
        save_profile_field('menu', 'fallwednesdaycreditssession8', '--------- Session 8 -> Credits Needed', $category_fallid, 48, '0', $credits_list, null);

        // Thursday sessions
        save_profile_field('menu', 'fallthursdaysession1', 'Thursday - Session 1 -> Subject/Teacher/Section Number', $category_fallid, 49, 'None', $course_list, null);
        save_profile_field('menu', 'fallthursdaycreditssession1', '--------- Session 1 -> Credits Needed', $category_fallid, 50, '0', $credits_list, null);
        save_profile_field('menu', 'fallthursdaysession2', 'Thursday - Session 2', $category_fallid, 51, 'None', $course_list, null);
        save_profile_field('menu', 'fallthursdaycreditssession2', '--------- Session 2 -> Credits Needed', $category_fallid, 52, '0', $credits_list, null);
        save_profile_field('menu', 'fallthursdaysession3', 'Thursday - Session 3', $category_fallid, 53, 'None', $course_list, null);
        save_profile_field('menu', 'fallthursdaycreditssession3', '--------- Session 3 -> Credits Needed', $category_fallid, 54, '0', $credits_list, null);
        save_profile_field('menu', 'fallthursdaysession4', 'Thursday - Session 4', $category_fallid, 55, 'None', $course_list, null);
        save_profile_field('menu', 'fallthursdaycreditssession4', '--------- Session 4 -> Credits Needed', $category_fallid, 56, '0', $credits_list, null);
        save_profile_field('menu', 'fallthursdaysession5', 'Thursday - Session 5', $category_fallid, 57, 'None', $course_list, null);
        save_profile_field('menu', 'fallthursdaycreditssession5', '--------- Session 5 -> Credits Needed', $category_fallid, 58, '0', $credits_list, null);
        save_profile_field('menu', 'fallthursdaysession6', 'Thursday - Session 6', $category_fallid, 59, 'None', $course_list, null);
        save_profile_field('menu', 'fallthursdaycreditssession6', '--------- Session 6 -> Credits Needed', $category_fallid, 60, '0', $credits_list, null);
        save_profile_field('menu', 'fallthursdaysession7', 'Thursday - Session 7', $category_fallid, 61, 'None', $course_list, null);
        save_profile_field('menu', 'fallthursdaycreditssession7', '--------- Session 7 -> Credits Needed', $category_fallid, 62, '0', $credits_list, null);
        save_profile_field('menu', 'fallthursdaysession8', 'Thursday - Session 8', $category_fallid, 63, 'None', $course_list, null);
        save_profile_field('menu', 'fallthursdaycreditssession8', '--------- Session 8 -> Credits Needed', $category_fallid, 64, '0', $credits_list, null);

        // Friday sessions
        save_profile_field('menu', 'fallfridaysession1', 'Friday - Session 1 -> Subject/Teacher/Section Number', $category_fallid, 65, 'None', $course_list, null);
        save_profile_field('menu', 'fallfridaycreditssession1', '--------- Session 1 -> Credits Needed', $category_fallid, 66, '0', $credits_list, null);
        save_profile_field('menu', 'fallfridaysession2', 'Friday - Session 2', $category_fallid, 67, 'None', $course_list, null);
        save_profile_field('menu', 'fallfridaycreditssession2', '--------- Session 2 -> Credits Needed', $category_fallid, 68, '0', $credits_list, null);
        save_profile_field('menu', 'fallfridaysession3', 'Friday - Session 3', $category_fallid, 69, 'None', $course_list, null);
        save_profile_field('menu', 'fallfridaycreditssession3', '--------- Session 3 -> Credits Needed', $category_fallid, 70, '0', $credits_list, null);
        save_profile_field('menu', 'fallfridaysession4', 'Friday - Session 4', $category_fallid, 71, 'None', $course_list, null);
        save_profile_field('menu', 'fallfridaycreditssession4', '--------- Session 4 -> Credits Needed', $category_fallid, 72, '0', $credits_list, null);
        save_profile_field('menu', 'fallfridaysession5', 'Friday - Session 5', $category_fallid, 73, 'None', $course_list, null);
        save_profile_field('menu', 'fallfridaycreditssession5', '--------- Session 5 -> Credits Needed', $category_fallid, 74, '0', $credits_list, null);
        save_profile_field('menu', 'fallfridaysession6', 'Friday - Session 6', $category_fallid, 75, 'None', $course_list, null);
        save_profile_field('menu', 'fallfridaycreditssession6', '--------- Session 6 -> Credits Needed', $category_fallid, 76, '0', $credits_list, null);
        save_profile_field('menu', 'fallfridaysession7', 'Friday - Session 7', $category_fallid, 77, 'None', $course_list, null);
        save_profile_field('menu', 'fallfridaycreditssession7', '--------- Session 7 -> Credits Needed', $category_fallid, 78, '0', $credits_list, null);
        save_profile_field('menu', 'fallfridaysession8', 'Friday - Session 8', $category_fallid, 79, 'None', $course_list, null);
        save_profile_field('menu', 'fallfridaycreditssession8', '--------- Session 8 -> Credits Needed', $category_fallid, 80, '0', $credits_list, null);

        
        // iStudy - Spring Courses

        // Monday sessions
        save_profile_field('menu', 'springmondaysession1', 'Monday - Session 1 -> Subject/Teacher/Section Number', $category_springid, 1, 'None', $course_list, null);
        save_profile_field('menu', 'springmondaycreditssession1', '--------- Session 1 -> Credits Needed', $category_springid, 2, '0', $credits_list, null);
        save_profile_field('menu', 'springmondaysession2', 'Monday - Session 2', $category_springid, 3, 'None', $course_list, null);
        save_profile_field('menu', 'springmondaycreditssession2', '--------- Session 2 -> Credits Needed', $category_springid, 4, '0', $credits_list, null);
        save_profile_field('menu', 'springmondaysession3', 'Monday - Session 3', $category_springid, 5, 'None', $course_list, null);
        save_profile_field('menu', 'springmondaycreditssession3', '--------- Session 3 -> Credits Needed', $category_springid,6, '0', $credits_list, null);
        save_profile_field('menu', 'springmondaysession4', 'Monday - Session 4', $category_springid, 7, 'None', $course_list, null);
        save_profile_field('menu', 'springmondaycreditssession4', '--------- Session 4 -> Credits Needed', $category_springid, 8, '0', $credits_list, null);
        save_profile_field('menu', 'springmondaysession5', 'Monday - Session 5', $category_springid, 9, 'None', $course_list, null);
        save_profile_field('menu', 'springmondaycreditssession5', '--------- Session 5 -> Credits Needed', $category_springid, 10, '0', $credits_list, null);
        save_profile_field('menu', 'springmondaysession6', 'Monday - Session 6', $category_springid, 11, 'None', $course_list, null);
        save_profile_field('menu', 'springmondaycreditssession6', '--------- Session 6 -> Credits Needed', $category_springid, 12, '0', $credits_list, null);
        save_profile_field('menu', 'springmondaysession7', 'Monday - Session 7', $category_springid, 13, 'None', $course_list, null);
        save_profile_field('menu', 'springmondaycreditssession7', '--------- Session 7 -> Credits Needed', $category_springid, 14, '0', $credits_list, null);
        save_profile_field('menu', 'springmondaysession8', 'Monday - Session 8', $category_springid, 15, 'None', $course_list, null);
        save_profile_field('menu', 'springmondaycreditssession8', '--------- Session 8 -> Credits Needed', $category_springid, 16, '0', $credits_list, null);

        // Tuesday sessions
        save_profile_field('menu', 'springtuesdaysession1', 'Tuesday - Session 1 -> Subject/Teacher/Section Number', $category_springid, 17, 'None', $course_list, null);
        save_profile_field('menu', 'springtuesdaycreditssession1', '--------- Session 1 -> Credits Needed', $category_springid, 18, '0', $credits_list, null);
        save_profile_field('menu', 'springtuesdaysession2', 'Tuesday - Session 2', $category_springid, 19, 'None', $course_list, null);
        save_profile_field('menu', 'springtuesdaycreditssession2', '--------- Session 2 -> Credits Needed', $category_springid, 20, '0', $credits_list, null);
        save_profile_field('menu', 'springtuesdaysession3', 'Tuesday - Session 3', $category_springid, 21, 'None', $course_list, null);
        save_profile_field('menu', 'springtuesdaycreditssession3', '--------- Session 3 -> Credits Needed', $category_springid, 22, '0', $credits_list, null);
        save_profile_field('menu', 'springtuesdaysession4', 'Tuesday - Session 4', $category_springid, 23, 'None', $course_list, null);
        save_profile_field('menu', 'springtuesdaycreditssession4', '--------- Session 4 -> Credits Needed', $category_springid, 24, '0', $credits_list, null);
        save_profile_field('menu', 'springtuesdaysession5', 'Tuesday - Session 5', $category_springid, 25, 'None', $course_list, null);
        save_profile_field('menu', 'springtuesdaycreditssession5', '--------- Session 5 -> Credits Needed', $category_springid, 26, '0', $credits_list, null);
        save_profile_field('menu', 'springtuesdaysession6', 'Tuesday - Session 6', $category_springid, 27, 'None', $course_list, null);
        save_profile_field('menu', 'springtuesdaycreditssession6', '--------- Session 6 -> Credits Needed', $category_springid, 28, '0', $credits_list, null);
        save_profile_field('menu', 'springtuesdaysession7', 'Tuesday - Session 7', $category_springid, 29, 'None', $course_list, null);
        save_profile_field('menu', 'springtuesdaycreditssession7', '--------- Session 7 -> Credits Needed', $category_springid, 30, '0', $credits_list, null);
        save_profile_field('menu', 'springtuesdaysession8', 'Tuesday - Session 8', $category_springid, 31, 'None', $course_list, null);
        save_profile_field('menu', 'springtuesdaycreditssession8', '--------- Session 8 -> Credits Needed', $category_springid, 32, '0', $credits_list, null);

        // Wednesday sessions
        save_profile_field('menu', 'springwednesdaysession1', 'Wednesday - Session 1 -> Subject/Teacher/Section Number', $category_springid, 33, 'None', $course_list, null);
        save_profile_field('menu', 'springwednesdaycreditssession1', '--------- Session 1 -> Credits Needed', $category_springid, 34, '0', $credits_list, null);
        save_profile_field('menu', 'springwednesdaysession2', 'Wednesday - Session 2', $category_springid, 35, 'None', $course_list, null);
        save_profile_field('menu', 'springwednesdaycreditssession2', '--------- Session 2 -> Credits Needed', $category_springid, 36, '0', $credits_list, null);
        save_profile_field('menu', 'springwednesdaysession3', 'Wednesday - Session 3', $category_springid, 37, 'None', $course_list, null);
        save_profile_field('menu', 'springwednesdaycreditssession3', '--------- Session 3 -> Credits Needed', $category_springid, 38, '0', $credits_list, null);
        save_profile_field('menu', 'springwednesdaysession4', 'Wednesday - Session 4', $category_springid, 39, 'None', $course_list, null);
        save_profile_field('menu', 'springwednesdaycreditssession4', '--------- Session 4 -> Credits Needed', $category_springid, 40, '0', $credits_list, null);
        save_profile_field('menu', 'springwednesdaysession5', 'Wednesday - Session 5', $category_springid, 41, 'None', $course_list, null);
        save_profile_field('menu', 'springwednesdaycreditssession5', '--------- Session 5 -> Credits Needed', $category_springid, 42, '0', $credits_list, null);
        save_profile_field('menu', 'springwednesdaysession6', 'Wednesday - Session 6', $category_springid, 43, 'None', $course_list, null);
        save_profile_field('menu', 'springwednesdaycreditssession6', '--------- Session 6 -> Credits Needed', $category_springid, 44, '0', $credits_list, null);
        save_profile_field('menu', 'springwednesdaysession7', 'Wednesday - Session 7', $category_springid, 45, 'None', $course_list, null);
        save_profile_field('menu', 'springwednesdaycreditssession7', '--------- Session 7 -> Credits Needed', $category_springid, 46, '0', $credits_list, null);
        save_profile_field('menu', 'springwednesdaysession8', 'Wednesday - Session 8', $category_springid, 47, 'None', $course_list, null);
        save_profile_field('menu', 'springwednesdaycreditssession8', '--------- Session 8 -> Credits Needed', $category_springid, 48, '0', $credits_list, null);

        // Thursday sessions
        save_profile_field('menu', 'springthursdaysession1', 'Thursday - Session 1 -> Subject/Teacher/Section Number', $category_springid, 49, 'None', $course_list, null);
        save_profile_field('menu', 'springthursdaycreditssession1', '--------- Session 1 -> Credits Needed', $category_springid, 50, '0', $credits_list, null);
        save_profile_field('menu', 'springthursdaysession2', 'Thursday - Session 2', $category_springid, 51, 'None', $course_list, null);
        save_profile_field('menu', 'springthursdaycreditssession2', '--------- Session 2 -> Credits Needed', $category_springid, 52, '0', $credits_list, null);
        save_profile_field('menu', 'springthursdaysession3', 'Thursday - Session 3', $category_springid, 53, 'None', $course_list, null);
        save_profile_field('menu', 'springthursdaycreditssession3', '--------- Session 3 -> Credits Needed', $category_springid, 54, '0', $credits_list, null);
        save_profile_field('menu', 'springthursdaysession4', 'Thursday - Session 4', $category_springid, 55, 'None', $course_list, null);
        save_profile_field('menu', 'springthursdaycreditssession4', '--------- Session 4 -> Credits Needed', $category_springid, 56, '0', $credits_list, null);
        save_profile_field('menu', 'springthursdaysession5', 'Thursday - Session 5', $category_springid, 57, 'None', $course_list, null);
        save_profile_field('menu', 'springthursdaycreditssession5', '--------- Session 5 -> Credits Needed', $category_springid, 58, '0', $credits_list, null);
        save_profile_field('menu', 'springthursdaysession6', 'Thursday - Session 6', $category_springid, 59, 'None', $course_list, null);
        save_profile_field('menu', 'springthursdaycreditssession6', '--------- Session 6 -> Credits Needed', $category_springid, 60, '0', $credits_list, null);
        save_profile_field('menu', 'springthursdaysession7', 'Thursday - Session 7', $category_springid, 61, 'None', $course_list, null);
        save_profile_field('menu', 'springthursdaycreditssession7', '--------- Session 7 -> Credits Needed', $category_springid, 62, '0', $credits_list, null);
        save_profile_field('menu', 'springthursdaysession8', 'Thursday - Session 8', $category_springid, 63, 'None', $course_list, null);
        save_profile_field('menu', 'springthursdaycreditssession8', '--------- Session 8 -> Credits Needed', $category_springid, 64, '0', $credits_list, null);

        // Friday sessions
        save_profile_field('menu', 'springfridaysession1', 'Friday - Session 1 -> Subject/Teacher/Section Number', $category_springid, 65, 'None', $course_list, null);
        save_profile_field('menu', 'springfridaycreditssession1', '--------- Session 1 -> Credits Needed', $category_springid, 66, '0', $credits_list, null);
        save_profile_field('menu', 'springfridaysession2', 'Friday - Session 2', $category_springid, 67, 'None', $course_list, null);
        save_profile_field('menu', 'springfridaycreditssession2', '--------- Session 2 -> Credits Needed', $category_springid, 68, '0', $credits_list, null);
        save_profile_field('menu', 'springfridaysession3', 'Friday - Session 3', $category_springid, 69, 'None', $course_list, null);
        save_profile_field('menu', 'springfridaycreditssession3', '--------- Session 3 -> Credits Needed', $category_springid, 70, '0', $credits_list, null);
        save_profile_field('menu', 'springfridaysession4', 'Friday - Session 4', $category_springid, 71, 'None', $course_list, null);
        save_profile_field('menu', 'springfridaycreditssession4', '--------- Session 4 -> Credits Needed', $category_springid, 72, '0', $credits_list, null);
        save_profile_field('menu', 'springfridaysession5', 'Friday - Session 5', $category_springid, 73, 'None', $course_list, null);
        save_profile_field('menu', 'springfridaycreditssession5', '--------- Session 5 -> Credits Needed', $category_springid, 74, '0', $credits_list, null);
        save_profile_field('menu', 'springfridaysession6', 'Friday - Session 6', $category_springid, 75, 'None', $course_list, null);
        save_profile_field('menu', 'springfridaycreditssession6', '--------- Session 6 -> Credits Needed', $category_springid, 76, '0', $credits_list, null);
        save_profile_field('menu', 'springfridaysession7', 'Friday - Session 7', $category_springid, 77, 'None', $course_list, null);
        save_profile_field('menu', 'springfridaycreditssession7', '--------- Session 7 -> Credits Needed', $category_springid, 78, '0', $credits_list, null);
        save_profile_field('menu', 'springfridaysession8', 'Friday - Session 8', $category_springid, 79, 'None', $course_list, null);
        save_profile_field('menu', 'springfridaycreditssession8', '--------- Session 8 -> Credits Needed', $category_springid, 80, '0', $credits_list, null);


        // iStudy - Summer Courses

        // Monday sessions
        save_profile_field('menu', 'summermondaysession1', 'Monday - Session 1 -> Subject/Teacher/Section Number', $category_summerid, 1, 'None', $course_list, null);
        save_profile_field('menu', 'summermondaycreditssession1', '--------- Session 1 -> Credits Needed', $category_summerid, 2, '0', $credits_list, null);
        save_profile_field('menu', 'summermondaysession2', 'Monday - Session 2', $category_summerid, 3, 'None', $course_list, null);
        save_profile_field('menu', 'summermondaycreditssession2', '--------- Session 2 -> Credits Needed', $category_summerid, 4, '0', $credits_list, null);
        save_profile_field('menu', 'summermondaysession3', 'Monday - Session 3', $category_summerid, 5, 'None', $course_list, null);
        save_profile_field('menu', 'summermondaycreditssession3', '--------- Session 3 -> Credits Needed', $category_summerid,6, '0', $credits_list, null);
        save_profile_field('menu', 'summermondaysession4', 'Monday - Session 4', $category_summerid, 7, 'None', $course_list, null);
        save_profile_field('menu', 'summermondaycreditssession4', '--------- Session 4 -> Credits Needed', $category_summerid, 8, '0', $credits_list, null);
        save_profile_field('menu', 'summermondaysession5', 'Monday - Session 5', $category_summerid, 9, 'None', $course_list, null);
        save_profile_field('menu', 'summermondaycreditssession5', '--------- Session 5 -> Credits Needed', $category_summerid, 10, '0', $credits_list, null);
        save_profile_field('menu', 'summermondaysession6', 'Monday - Session 6', $category_summerid, 11, 'None', $course_list, null);
        save_profile_field('menu', 'summermondaycreditssession6', '--------- Session 6 -> Credits Needed', $category_summerid, 12, '0', $credits_list, null);
        save_profile_field('menu', 'summermondaysession7', 'Monday - Session 7', $category_summerid, 13, 'None', $course_list, null);
        save_profile_field('menu', 'summermondaycreditssession7', '--------- Session 7 -> Credits Needed', $category_summerid, 14, '0', $credits_list, null);
        save_profile_field('menu', 'summermondaysession8', 'Monday - Session 8', $category_summerid, 15, 'None', $course_list, null);
        save_profile_field('menu', 'summermondaycreditssession8', '--------- Session 8 -> Credits Needed', $category_summerid, 16, '0', $credits_list, null);

        // Tuesday sessions
        save_profile_field('menu', 'summertuesdaysession1', 'Tuesday - Session 1 -> Subject/Teacher/Section Number', $category_summerid, 17, 'None', $course_list, null);
        save_profile_field('menu', 'summertuesdaycreditssession1', '--------- Session 1 -> Credits Needed', $category_summerid, 18, '0', $credits_list, null);
        save_profile_field('menu', 'summertuesdaysession2', 'Tuesday - Session 2', $category_summerid, 19, 'None', $course_list, null);
        save_profile_field('menu', 'summertuesdaycreditssession2', '--------- Session 2 -> Credits Needed', $category_summerid, 20, '0', $credits_list, null);
        save_profile_field('menu', 'summertuesdaysession3', 'Tuesday - Session 3', $category_summerid, 21, 'None', $course_list, null);
        save_profile_field('menu', 'summertuesdaycreditssession3', '--------- Session 3 -> Credits Needed', $category_summerid, 22, '0', $credits_list, null);
        save_profile_field('menu', 'summertuesdaysession4', 'Tuesday - Session 4', $category_summerid, 23, 'None', $course_list, null);
        save_profile_field('menu', 'summertuesdaycreditssession4', '--------- Session 4 -> Credits Needed', $category_summerid, 24, '0', $credits_list, null);
        save_profile_field('menu', 'summertuesdaysession5', 'Tuesday - Session 5', $category_summerid, 25, 'None', $course_list, null);
        save_profile_field('menu', 'summertuesdaycreditssession5', '--------- Session 5 -> Credits Needed', $category_summerid, 26, '0', $credits_list, null);
        save_profile_field('menu', 'summertuesdaysession6', 'Tuesday - Session 6', $category_summerid, 27, 'None', $course_list, null);
        save_profile_field('menu', 'summertuesdaycreditssession6', '--------- Session 6 -> Credits Needed', $category_summerid, 28, '0', $credits_list, null);
        save_profile_field('menu', 'summertuesdaysession7', 'Tuesday - Session 7', $category_summerid, 29, 'None', $course_list, null);
        save_profile_field('menu', 'summertuesdaycreditssession7', '--------- Session 7 -> Credits Needed', $category_summerid, 30, '0', $credits_list, null);
        save_profile_field('menu', 'summertuesdaysession8', 'Tuesday - Session 8', $category_summerid, 31, 'None', $course_list, null);
        save_profile_field('menu', 'summertuesdaycreditssession8', '--------- Session 8 -> Credits Needed', $category_summerid, 32, '0', $credits_list, null);

        // Wednesday sessions
        save_profile_field('menu', 'summerwednesdaysession1', 'Wednesday - Session 1 -> Subject/Teacher/Section Number', $category_summerid, 33, 'None', $course_list, null);
        save_profile_field('menu', 'summerwednesdaycreditssession1', '--------- Session 1 -> Credits Needed', $category_summerid, 34, '0', $credits_list, null);
        save_profile_field('menu', 'summerwednesdaysession2', 'Wednesday - Session 2', $category_summerid, 35, 'None', $course_list, null);
        save_profile_field('menu', 'summerwednesdaycreditssession2', '--------- Session 2 -> Credits Needed', $category_summerid, 36, '0', $credits_list, null);
        save_profile_field('menu', 'summerwednesdaysession3', 'Wednesday - Session 3', $category_summerid, 37, 'None', $course_list, null);
        save_profile_field('menu', 'summerwednesdaycreditssession3', '--------- Session 3 -> Credits Needed', $category_summerid, 38, '0', $credits_list, null);
        save_profile_field('menu', 'summerwednesdaysession4', 'Wednesday - Session 4', $category_summerid, 39, 'None', $course_list, null);
        save_profile_field('menu', 'summerwednesdaycreditssession4', '--------- Session 4 -> Credits Needed', $category_summerid, 40, '0', $credits_list, null);
        save_profile_field('menu', 'summerwednesdaysession5', 'Wednesday - Session 5', $category_summerid, 41, 'None', $course_list, null);
        save_profile_field('menu', 'summerwednesdaycreditssession5', '--------- Session 5 -> Credits Needed', $category_summerid, 42, '0', $credits_list, null);
        save_profile_field('menu', 'summerwednesdaysession6', 'Wednesday - Session 6', $category_summerid, 43, 'None', $course_list, null);
        save_profile_field('menu', 'summerwednesdaycreditssession6', '--------- Session 6 -> Credits Needed', $category_summerid, 44, '0', $credits_list, null);
        save_profile_field('menu', 'summerwednesdaysession7', 'Wednesday - Session 7', $category_summerid, 45, 'None', $course_list, null);
        save_profile_field('menu', 'summerwednesdaycreditssession7', '--------- Session 7 -> Credits Needed', $category_summerid, 46, '0', $credits_list, null);
        save_profile_field('menu', 'summerwednesdaysession8', 'Wednesday - Session 8', $category_summerid, 47, 'None', $course_list, null);
        save_profile_field('menu', 'summerwednesdaycreditssession8', '--------- Session 8 -> Credits Needed', $category_summerid, 48, '0', $credits_list, null);

        // Thursday sessions
        save_profile_field('menu', 'summerthursdaysession1', 'Thursday - Session 1 -> Subject/Teacher/Section Number', $category_summerid, 49, 'None', $course_list, null);
        save_profile_field('menu', 'summerthursdaycreditssession1', '--------- Session 1 -> Credits Needed', $category_summerid, 50, '0', $credits_list, null);
        save_profile_field('menu', 'summerthursdaysession2', 'Thursday - Session 2', $category_summerid, 51, 'None', $course_list, null);
        save_profile_field('menu', 'summerthursdaycreditssession2', '--------- Session 2 -> Credits Needed', $category_summerid, 52, '0', $credits_list, null);
        save_profile_field('menu', 'summerthursdaysession3', 'Thursday - Session 3', $category_summerid, 53, 'None', $course_list, null);
        save_profile_field('menu', 'summerthursdaycreditssession3', '--------- Session 3 -> Credits Needed', $category_summerid, 54, '0', $credits_list, null);
        save_profile_field('menu', 'summerthursdaysession4', 'Thursday - Session 4', $category_summerid, 55, 'None', $course_list, null);
        save_profile_field('menu', 'summerthursdaycreditssession4', '--------- Session 4 -> Credits Needed', $category_summerid, 56, '0', $credits_list, null);
        save_profile_field('menu', 'summerthursdaysession5', 'Thursday - Session 5', $category_summerid, 57, 'None', $course_list, null);
        save_profile_field('menu', 'summerthursdaycreditssession5', '--------- Session 5 -> Credits Needed', $category_summerid, 58, '0', $credits_list, null);
        save_profile_field('menu', 'summerthursdaysession6', 'Thursday - Session 6', $category_summerid, 59, 'None', $course_list, null);
        save_profile_field('menu', 'summerthursdaycreditssession6', '--------- Session 6 -> Credits Needed', $category_summerid, 60, '0', $credits_list, null);
        save_profile_field('menu', 'summerthursdaysession7', 'Thursday - Session 7', $category_summerid, 61, 'None', $course_list, null);
        save_profile_field('menu', 'summerthursdaycreditssession7', '--------- Session 7 -> Credits Needed', $category_summerid, 62, '0', $credits_list, null);
        save_profile_field('menu', 'summerthursdaysession8', 'Thursday - Session 8', $category_summerid, 63, 'None', $course_list, null);
        save_profile_field('menu', 'summerthursdaycreditssession8', '--------- Session 8 -> Credits Needed', $category_summerid, 64, '0', $credits_list, null);

        // Friday sessions
        save_profile_field('menu', 'summerfridaysession1', 'Friday - Session 1 -> Subject/Teacher/Section Number', $category_summerid, 65, 'None', $course_list, null);
        save_profile_field('menu', 'summerfridaycreditssession1', '--------- Session 1 -> Credits Needed', $category_summerid, 66, '0', $credits_list, null);
        save_profile_field('menu', 'summerfridaysession2', 'Friday - Session 2', $category_summerid, 67, 'None', $course_list, null);
        save_profile_field('menu', 'summerfridaycreditssession2', '--------- Session 2 -> Credits Needed', $category_summerid, 68, '0', $credits_list, null);
        save_profile_field('menu', 'summerfridaysession3', 'Friday - Session 3', $category_summerid, 69, 'None', $course_list, null);
        save_profile_field('menu', 'summerfridaycreditssession3', '--------- Session 3 -> Credits Needed', $category_summerid, 70, '0', $credits_list, null);
        save_profile_field('menu', 'summerfridaysession4', 'Friday - Session 4', $category_summerid, 71, 'None', $course_list, null);
        save_profile_field('menu', 'summerfridaycreditssession4', '--------- Session 4 -> Credits Needed', $category_summerid, 72, '0', $credits_list, null);
        save_profile_field('menu', 'summerfridaysession5', 'Friday - Session 5', $category_summerid, 73, 'None', $course_list, null);
        save_profile_field('menu', 'summerfridaycreditssession5', '--------- Session 5 -> Credits Needed', $category_summerid, 74, '0', $credits_list, null);
        save_profile_field('menu', 'summerfridaysession6', 'Friday - Session 6', $category_summerid, 75, 'None', $course_list, null);
        save_profile_field('menu', 'summerfridaycreditssession6', '--------- Session 6 -> Credits Needed', $category_summerid, 76, '0', $credits_list, null);
        save_profile_field('menu', 'summerfridaysession7', 'Friday - Session 7', $category_summerid, 77, 'None', $course_list, null);
        save_profile_field('menu', 'summerfridaycreditssession7', '--------- Session 7 -> Credits Needed', $category_summerid, 78, '0', $credits_list, null);
        save_profile_field('menu', 'summerfridaysession8', 'Friday - Session 8', $category_summerid, 79, 'None', $course_list, null);
        save_profile_field('menu', 'summerfridaycreditssession8', '--------- Session 8 -> Credits Needed', $category_summerid, 80, '0', $credits_list, null);
}

/**
 * Add a new profile field.
 * @param array|stdClass $data from the add/edit profile field form
 */
function save_profile_field($data_type, $shortname, $full_name, $category_id, $sort_order, $default_value, $parameter1, $parameter2) {
        global $DB;
        $data = new stdClass();

        // Create a default menu data type.   
        $data->shortname = $shortname;
        $data->name = $full_name;     
        $data->datatype = $data_type;
        $data->description = 'iStudy';
        $data->descriptionformat = 0;
        $data->categoryid = $category_id;
        $data->sortorder = $sort_order;
        $data->required = 0;
        $data->locked = 1;
        $data->visible = 0;
        $data->forceunique = 0;
        $data->signup = 0;
        $data->defaultdata = $default_value;
        $data->defaultdataformat = 0;
        $data->param1 = $parameter1;
        $data->param2 = $parameter2;
        if ($data_type == 'text') {
                $data->param3 = 0;
                $data->param4 = '';
                $data->param5 = '';
        }
        else {
                $data->param3 = null;
                $data->param4 = null;
                $data->param5 = null;
        }  
        $data = define_save_preprocess($data); // Hook for child classes.
        $data_id = $DB->insert_record('user_info_field', $data);
        $field = $DB->get_record('user_info_field', array('id' => $data_id));
        \core\event\user_info_field_created::create_from_field($field)->trigger();
        profile_purge_user_fields_cache();
        profile_reorder_fields();
}

/**
 * Preprocess data from the add/edit profile field form before it is saved.
 *
 * This method is a hook for the child classes to overwrite.
 *
 * @param array|stdClass $data from the add/edit profile field form
 * @return array|stdClass processed data object
 */
function define_save_preprocess($data) {
        // Do nothing - overwrite if necessary.
        return $data;
}

// **Get the user_info_field id to use in searching user_info_data
function get_fieldid($item_name) {
        global $DB;
        $item = $DB->get_record('user_info_field', ['shortname' => $item_name], 'id');
return $item->id;
}

// This function will trim a string, but will return '' if the $value is not a string.
function custom_trim(?string $value) {
return trim($value ?? '');
}


/**
 * Post installation recovery procedure
 *
 * @see upgrade_plugins_modules()
 */
function xmldb_istudy_install_recovery() {
}