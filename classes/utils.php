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
 * @package    mod_istudy
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_istudy;

use stdClass;

/**
 * Class utils
 *
 * @package    mod_istudy
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class utils {

    // This function will trim a string, but will return '' if the $value is not a string.
    public static function custom_trim(?string $value) {
        return trim($value ?? '');
    }

    // Get the Aeries section number of the advisor, day, and session.
    public static function get_section_number($advisorid, $d, $j) {
        global $DB;

        $section_info = new stdClass();
        $sql = "SELECT '*' FROM {istudycoursenum_sections} WHERE teacher_id = " . $advisorid . " AND day_of_week = " . $d . " AND session = " . $j;
        $params = null;
        if ($DB->record_exists_sql($sql, $params)) {
            $sql = "SELECT * FROM {istudycoursenum_sections} WHERE teacher_id = " . $advisorid . " AND day_of_week = " . $d . " AND session = " . $j;
            $params = null;
            $strictness = IGNORE_MULTIPLE;
            $section_info = $DB->get_record_sql($sql, $params, $strictness);
        }
        // This is a new section number.
        else {
            $section_info->id = -1;
            $section_info->timemodified = time();
            $section_info->teacher_id = $advisorid;
            $section_info->day_of_week = $d;
            $section_info->session = $j;
            $section_info->section_number = '';
        }
        return $section_info;
    }

    // **Replace the dash in the teacher name with a comma and space
    public static function put_comma_in_name($myteacher) {
        $postion = strrpos($myteacher,"-");
        $name_with_dash = substr($myteacher, $postion);
        $name_with_comma = ', ' . substr($name_with_dash,1);
        $new_name = str_replace($name_with_dash,$name_with_comma,$myteacher);
        return $new_name;
    }

    // Remove extra characters from the string
    public static function remove_extra_symbols($snip) {
        $snip = str_replace("'", "’", $snip); // remove apostrophe
        $snip = str_replace("\t", "", $snip); // remove tabs
        $snip = str_replace("\n", "", $snip); // remove new lines
        $snip = str_replace("\r", "", $snip); // remove carriage returns
        $snip = str_replace("/", "", $snip); // remove / character - used in drop down menus
        return $snip;
    }

    // **Get the user_info_field id to use in searching user_info_data (moodle - user profile fields).
    public static function get_fieldid($item_name) {
        global $DB;
        $item = $DB->get_record('user_info_field', ['shortname' => $item_name], 'id');
        return $item->id;
    }

    // **Search user_info_data for student data using the student id and user_info_field id (moodle - user profile fields).
    public static function get_field_data($student_id, $itemid) {
        global $DB;
        if ($DB->record_exists('user_info_data', ['userid'=>$student_id, 'fieldid'=>$itemid])) {
            $search_results = $DB->get_record('user_info_data', ['userid'=>$student_id, 'fieldid'=>$itemid], 'data');
            return $search_results->data;
        }
        else {
            return '';
        }
    }

    // Get a complete course list of avaialable courses.
    public static function get_master_course_list() {
        global $DB;
        // Get a list of the teachers and the courses they can teach.
        $sql = "SELECT * FROM {istudycourses_teachers} WHERE active = true ORDER By subject ASC, teacher_name ASC";
        $params = null;
        $allcourses = $DB->get_records_sql($sql, $params);
        $count = 0;
        foreach ($allcourses as $this_course) {
            $count++;
            $courselist[$count] = $this_course->subject . '/' . $this_course->teacher_name . '/' . $this_course->section;
        }
        if ($count > 0) {
            // Sort the list by the name of the course/teacher name/course #.
            for ($i=1; $i<=$count; $i++){
                $val = $courselist[$i];
                $j = $i-1;
                while($j>=1 && $courselist[$j] > $val){
                    $courselist[$j+1] = $courselist[$j];
                    $j--;
                }
                $courselist[$j+1] = $val;
            }
            $courselist[0] = $count;
            return $courselist;
        }
        else {
            $courselist[0] = 0;
            return $courselist;
        }
    }

    // Break up the string and return the course info - subject, teacher, section.
    public static function get_mycourse($mycourse) {
        $course_info = new \stdClass();
        $course_info->subject = strstr($mycourse, '/', true);
        $mycourse = strstr($mycourse, "/");
        $mycourse = substr($mycourse, 1);
        $course_info->teacher = strstr($mycourse, '/', true);
        $mycourse = strstr($mycourse, "/");
        $mycourse = substr($mycourse, 1);
        $course_info->section = utils::custom_trim($mycourse);
        return $course_info;
    }

    // Update the student profile options for the student agreement (city list, goals options, evaluations options, and default date options).
    public static function update_studentprofile_options() {
        $submission = new stdClass();
        // Updated city list.
        $final_list = "None";
        $count = 0;
        // Create the list of city-state-zip code.
        $temp_list = get_config('mod_istudy','agreementcity');
        while(strlen($temp_list) > 0) {
            // Get the next course on the list
            $citytemp = utils::custom_trim(strstr($temp_list, "\n", true));
            // The last course on the list - doesn't end with "\n".
            if (strlen($citytemp) == 0) {
                if (strlen(utils::custom_trim($temp_list)) > 0) {
                    $final_list = $final_list . "\n" . utils::custom_trim($temp_list);
                    $count++;
                }
                $temp_list = '';
            }
            if (strlen($temp_list) > 0) {
                if (strlen(utils::custom_trim($citytemp)) > 0) {
                    $final_list = $final_list . "\n" . utils::custom_trim($citytemp);
                    $count++;
                }
                $temp_list = strstr($temp_list, "\n");
                $temp_list = substr($temp_list, 1);
            }
        }
        if ($count == 0) {
            $final_list = "None\nMadera, CA 93636\nMadera, CA 93637\nMadera, CA 93638\nMadera, CA 93639";
        }
        // Save the updated city list.
        $submission = utils::get_fieldinfo('city');
        $submission->param1 = $final_list;
        utils::update_profile_field($submission);

        // Update goal1.
        $temp_goal_name = utils::custom_trim(get_config('mod_istudy','agreementgoal1'));
        if(strlen($temp_goal_name) == 0) {
            $temp_goal_name = "Furman High School Diploma (230 credits)";
        }
        // Save the updated goal1.
        $submission = utils::get_fieldinfo('goal1');
        $submission->param1 = $temp_goal_name;
        utils::update_profile_field($submission);

        // Update goal2.
        $temp_goal_name = utils::custom_trim(get_config('mod_istudy','agreementgoal2'));
        if(strlen($temp_goal_name) == 0) {
            $temp_goal_name = "Madera Adult School (180 credits)";
        }
        // Save the updated goal2.
        $submission = utils::get_fieldinfo('goal2');
        $submission->param1 = $temp_goal_name;
        utils::update_profile_field($submission);

        // Update goal3.
        $temp_goal_name = utils::custom_trim(get_config('mod_istudy','agreementgoal3'));
        if(strlen($temp_goal_name) == 0) {
            $temp_goal_name = "Transfer to MSH/MSHS/MTHS";
        }
        // Save the updated goal3.
        $submission = utils::get_fieldinfo('goal3');
        $submission->param1 = $temp_goal_name;
        utils::update_profile_field($submission);

        // Updated evalation1 option.
        $temp_eval_name = utils::custom_trim(get_config('mod_istudy','agreementeval1'));
        if(strlen($temp_eval_name) == 0) {
            $temp_eval_name = "Tests";
        }
        // Save the updated evalation1 option.
        $submission = utils::get_fieldinfo('eval1');
        $submission->param1 = $temp_eval_name;
        utils::update_profile_field($submission);

        // Updated evalation2 option.
        $temp_eval_name = utils::custom_trim(get_config('mod_istudy','agreementeval2'));
        if(strlen($temp_eval_name) == 0) {
            $temp_eval_name = "Written Work";
        }
        // Save the updated evalation2 option.
        $submission = utils::get_fieldinfo('eval2');
        $submission->param1 = $temp_eval_name;
        utils::update_profile_field($submission);

        // Updated evalation3 option.
        $temp_eval_name = utils::custom_trim(get_config('mod_istudy','agreementeval3'));
        if(strlen($temp_eval_name) == 0) {
            $temp_eval_name = "Demonstration";
        }
        // Save the updated evalation3 option.
        $submission = utils::get_fieldinfo('eval3');
        $submission->param1 = $temp_eval_name;
        utils::update_profile_field($submission);

        // Updated evalation4 option.
        $temp_eval_name = utils::custom_trim(get_config('mod_istudy','agreementeval4'));
        if(strlen($temp_eval_name) == 0) {
            $temp_eval_name = "Oral Presentation";
        }
        // Save the updated evalation4 option.
        $submission = utils::get_fieldinfo('eval4');
        $submission->param1 = $temp_eval_name;
        utils::update_profile_field($submission);

        // Update graduationyear menu list.
        $currentyear = date('Y');
        $currentmonth = date('m');
        // If current in first semester, the end of the school year will be next year.
        if ($currentmonth > 6) {
            $currentyear = $currentyear +1;
        }
        $year_range = '';
        for ($i = ($currentyear - 2); $i <= ($currentyear + 10); $i++) {
            $year_range = $year_range . $i . "\n";
        }
        $submission = utils::get_fieldinfo('graduationyear');
        $submission->defaultdata = $currentyear;
        $submission->param1 = utils::custom_trim($year_range);
        utils::update_profile_field($submission);

        // Update settings for birthday.
        $end_year = date('Y');
        $start_year = $end_year - 23;
        $submission = utils::get_fieldinfo('dob');
        $submission->param1 = $start_year;
        $submission->param2 = $end_year;
        utils::update_profile_field($submission);

        // Update settings for start date.
        $end_year = date('Y') + 6;
        $start_year = $end_year - 13;
        $submission = utils::get_fieldinfo('startdate');
        $submission->param1 = $start_year;
        $submission->param2 = $end_year;
        utils::update_profile_field($submission);

        // Update settings for end date.
        $submission = utils::get_fieldinfo('enddate');
        $submission->param1 = $start_year;
        $submission->param2 = $end_year;
        utils::update_profile_field($submission);

        // Update settings for anticipated date.
        $submission = utils::get_fieldinfo('anticipateddate');
        $submission->param1 = $start_year;
        $submission->param2 = $end_year;
        utils::update_profile_field($submission);
    }

    // **Return all of the courses and info - moodle id, section, subject, and teacher.
    public static function get_course_list() {
        global $DB;

		$count = 0;
		$all_courses = new stdClass();
        $sql = "SELECT * FROM {istudycourse_hours} ORDER By subject ASC";
        $params = null;
        $all_courses = $DB->get_records_sql($sql, $params);
        // Add all of the courses and info to the list.
        foreach ($all_courses as $my_course) {
            $count++;
            $this_course[$count] = new \stdClass();
            $this_course[$count]->id = $my_course->id;
            $this_course[$count]->section = $my_course->section;
            $this_course[$count]->subject = $my_course->subject;
            $this_course[$count]->hour_value = $my_course->hour_value;
        }
        $this_course[0] = $count;
        return $this_course;
    }

    // Get the course number using the course moodle id.
    public static function get_course_section($courseid) {
        global $DB;

        $course_info = new stdClass();
        $sql = "SELECT * FROM {istudycourse_hours} WHERE id = " . $courseid;
        $params = null;
        $strictness = IGNORE_MULTIPLE;
        $course_info = $DB->get_record_sql($sql, $params, $strictness);
        return $course_info->section;
    }

    // Check if the course is taught by this teacher.
    public static function course_taught_by_teacher($this_specific_course, $this_specific_teacher) {
        global $DB;
        $table = 'istudycourses_teachers';
        $select = "section = :section AND teacherid = :teacherid ";
        $params =['section'=>$this_specific_course, 'teacherid'=>$this_specific_teacher];
        // If the course is active, return true.
        if ($active_status = $DB->get_record_select($table, $select, $params)) {
/*
        $sql = "SELECT * FROM {istudycourses_teachers} WHERE section = '" . $this_specific_course . "' AND teacherid = " . $this_specific_teacher;
        $params = null;
        $strictness = IGNORE_MULTIPLE;
        // Find out if the course is or is not taught by the teacher (true or false).
        if ($active_status = $DB->get_record_sql($sql, $params, $strictness)) {
*/
            return $active_status->active;
        }
        else {
            return false;
        }
    }

    // **Return a list of courses and the techers that teach them.
    public static function get_courses_and_teachers($teacher_list) {
        // Initialize list of courses
        $number_of_teachers = $teacher_list[0];
        $this_course = utils::get_course_list();
        $number_of_courses = $this_course[0];

        // Create the final course list with the teachers that teach them.
        for ($c = 1; $c <= $number_of_courses; $c++) {
            $this_specific_course = $this_course[$c]->section;
            for ($t = 1; $t <= $number_of_teachers; $t++) {
                $this_specific_teacher = $teacher_list[$t]->teacherid;
                 // This returns either true or false.
                $this_course[$c]->$t = utils::course_taught_by_teacher($this_specific_course, $this_specific_teacher);
            }
        }
        $this_course[0] = new stdClass();
        $this_course[0] = $number_of_courses;
        return $this_course;
    }

    // When adding a new course, check for duplicate course number and/or course name.
    public static function is_not_a_duplicate($course_number, $course_name, $this_course) {
        global $USER;
        $number_of_courses = $this_course[0];
        for ($i = 1; $i <= $number_of_courses; $i++) {
            if (($course_number == $this_course[$i]->section) OR (strtolower($course_name) == strtolower($this_course[$i]->subject))) {
                $USER->just_savedctv = 'DuplicateFound';
                return false;
            }
        }
        return true;
    }

    // When updating a course, check for duplicate course name.
    public static function is_not_a_duplicate_name($course_number, $course_name, $this_course) {
        global $USER;
        $number_of_courses = $this_course[0];
        for ($i = 1; $i <= $number_of_courses; $i++) {
            // If it's the same course, skip it.
            if ($course_number <> $this_course[$i]->section) {
                if (strtolower($course_name) == strtolower($this_course[$i]->subject)) {
                    $USER->just_savedctv = 'DuplicateFound';
                    return false;
                }
            }
        }
        return true;
    }

    // Update the student profile options - teacher list.
    public static function update_studentprofile_teachers() {
        $temp_list = "None";
        $temp_teachers = teacher_info::get_advisors_all_info();
        $count = $temp_teachers[0];
        for($i=1; $i<=$count; $i++) {
             $temp_list =  $temp_list . "\n" . $temp_teachers[$i]->lastname . "-" . $temp_teachers[$i]->firstname;
        }
        // Save the updated advisor list.
        $submission = utils::get_fieldinfo('advisor');
        $submission->param1 = $temp_list;
        utils::update_profile_field($submission);
    }

    // Update the teacher status to teacher or not a teacher.
    public static function update_teacher_status($teacherid, $user_status_label) {
        global $DB;
        $field_id = get_fieldid('whichteacher');
        $whichteacher_id = $DB->get_record('user_info_data', ['userid'=>$teacherid, 'fieldid'=>$field_id], '*');
        $submission = new stdClass();
        $submission->id = $whichteacher_id->id;
        $submission->userid = $teacherid;
        $submission->data = $user_status_label;
        $DB->update_record('user_info_data', $submission, false);
    }


    // Get a list of courses available for a day, asession, and a specific teacher - the list will not start with 'None'.
    public static function course_is_active($this_term, $courseid, $teacherid, $this_day, $this_session) {
        global $DB;

        $this_course = new stdClass();
        $table = 'istudymenu_' . $this_term;
        $select = "courseid = :courseid AND teacherid = :teacherid AND dayofweek = :dayofweek AND period = :period AND active = :active";
        $params =['courseid'=>$courseid, 'teacherid'=>$teacherid, 'dayofweek'=>$this_day, 'period'=>$this_session, 'active'=>true];
        // If the course is active, return true.
        if ($this_course = $DB->get_record_select($table, $select, $params)) {
            return $this_course->active;
        }
        return false;
    }

    // Find complete course name and info using the teacherid number
    public static function get_teacher_course_list($teacherid) {
        global $DB;
        // Load the courses that have teachers assigned to them.
        $sql = "SELECT * FROM {istudycourses_teachers} WHERE teacherid = " . $teacherid . " AND active = true ORDER By subject ASC";
        $params = null;
        $allcourses = $DB->get_records_sql($sql, $params);
        $count = 0;
        foreach ($allcourses as $this_course) {
            $count++;
            $courselist[$count] = new stdClass();
            $courselist[$count]->courseid = $this_course->section;
            $courselist[$count]->name = $this_course->subject . '/' . $this_course->teacher_name . '/' . $this_course->section;
        }
        if ($count > 0) {
            $courselist[0] = $count;
            return $courselist;
        }
        else {
            $courselist[0] = 0;
            return $courselist;
        }
    }

    // Use the day of the week and period to get a list of the courses being taught.
    public static function get_courses_by_session($semester, $dayofweek, $period) {
        global $DB;
        $sql = "SELECT * FROM {istudymenu_" . $semester . "} WHERE dayofweek = " . $dayofweek . " AND period = " . $period . " AND active = true ORDER By course_name ASC";
        $params = null;
        $allcourses = $DB->get_records_sql($sql, $params);
        return $allcourses;
    }

    // Get a list of the courses that are actually assigned to a teacher.
    public static function get_active_courses() {
        global $DB;
        $allcourses = new stdClass();
        // Load the courses that have teachers assigned to them.
        $sql = "SELECT * FROM {istudycourses_teachers}";
        $params = null;
        $allcourses = $DB->get_records_sql($sql, $params);
        foreach ($allcourses AS $thiscourse){
            $course_active_status[$thiscourse->section][ $thiscourse->teacherid] = $thiscourse->active;
        }
        return $course_active_status;
    }

    // Update all of the course dropdown menus for this semester.
    public static function update_dropdown_menus($semester) {
        $week_day[1] = 'monday';
		$week_day[2] = 'tuesday';
		$week_day[3] = 'wednesday';
		$week_day[4] = 'thursday';
		$week_day[5] = 'friday';
        $submission = new stdClass();
        $course_is_active = utils::get_active_courses();

        for ($d = 1; $d <= 5; $d++) {
			for ($period = 1; $period <= 8; $period++) {
				$count = 0;
                $this_course_list = "None";
                $courses_by_session = utils::get_courses_by_session($semester, $d, $period);
                foreach ($courses_by_session AS $my_course) {
                    if ($course_is_active[$my_course->courseid][$my_course->teacherid]) {
                        $count++;
                        $this_course_list = $this_course_list . "\n" . utils::custom_trim($my_course->course_name);
                    }
                }
                if ($count == 0) {
					$this_course_list = "None\nNo courses available yet";
				}
				// Save the course list for this day and session.
				$course_day_and_session = $semester . $week_day[$d] . 'session' . $period;
				$submission = utils::get_fieldinfo($course_day_and_session);
				$submission->param1 = $this_course_list;
                utils::update_profile_field($submission);
			}
		}
    }

    // Save the whether a course is being taugh for a specific day and period by a specific teacher (true or fales).
    public static function save_course_status($semester, $course_id, $adviosr_id, $d, $period, $course_included, $course_name) {
        global $DB, $OUTPUT;

        $submission = new stdClass();
        $submission->courseid = $course_id;
        $submission->teacherid = $adviosr_id;
        $submission->dayofweek = $d;
        $submission->period = $period;
        $submission->active = $course_included;
        $submission->course_name = $course_name;
        try {
            $transaction = $DB->start_delegated_transaction();
            $table = 'istudymenu_' . $semester;
            $select = "courseid = :courseid AND teacherid = :teacherid AND dayofweek = :dayofweek AND period = :period";
            $params =['courseid'=>$course_id, 'teacherid'=>$adviosr_id, 'dayofweek'=>$d, 'period'=>$period];
            // If the course is active, return true.
            if ($submissionid = $DB->get_record_select($table, $select, $params)) {
                $submission->id = $submissionid->id;
                $DB->update_record( $table, $submission);
            }
            else {
                $DB->insert_record( $table, $submission, false, false);
            }
            // Assuming that one of the inserts worked, we get to the following line.
            $transaction->allow_commit();
        } catch(Exception $e) {
            $transaction->rollback($e);
            $course_with_error = 'The entry for ' . $course_name . ' was not saved. An error was found in the assignment details.';
            echo $OUTPUT->notification($course_with_error);
        }
    }

    /**
     * Add a new profile field (from moodle core code).
     * @param array|stdClass $data from the add/edit profile field form
     */
    public static function update_profile_field($data) {
        global $DB, $CFG;
        require_once($CFG->dirroot.'/user/profile/definelib.php');

        $data = utils::define_save_preprocess($data); // Hook for child classes.
        $data_id = $DB->update_record('user_info_field', $data);
        $field = $DB->get_record('user_info_field', array('id' => $data_id));
        \core\event\user_info_field_created::create_from_field($field)->trigger();
        profile_purge_user_fields_cache();
        // profile_reorder_fields();
    }

    /**
     * Preprocess data from the add/edit profile field form before it is saved (from moodle core code).
     *
     * This method is a hook for the child classes to overwrite.
     *
     * @param array|stdClass $data from the add/edit profile field form
     * @return array|stdClass processed data object
     */
    public static function define_save_preprocess($data) {
        // Do nothing - overwrite if necessary.
        return $data;
    }

    // **Get the user_info_field id to use in searching user_info_data (from moodle core code).
    public static function get_fieldinfo($item_name) {
        global $DB;
        $item = $DB->get_record('user_info_field', ['shortname' => $item_name], 'id');
        return $item;
    }
}