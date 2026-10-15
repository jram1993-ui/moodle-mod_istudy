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
 * Class teacher_info
 *
 * @package    mod_istudy
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class teacher_info {

    // Use the teacherid to get the teacher first name and lastname - also include if the account has been deleted or suspended.
    public static function get_single_teacher($teacherid) {
        global $DB;

        $mystudent = $DB->get_record('user', ['id' => $teacherid], 'firstname, lastname, deleted, suspended');
        return $mystudent;
    }

    // **Get a list of all advisors
    public static function get_advisors() {
        $temp_teachers = teacher_info::get_advisors_all_info();
        // This saves the number of teachers in the list.
        $teacher_list[0] = $temp_teachers[0];
        for($i=1; $i<=$teacher_list[0]; $i++) {
             $teacher_list[$i] =  $temp_teachers[$i]->lastname . '-' . $temp_teachers[$i]->firstname;
        }
        return $teacher_list;
    }

    // **Get a list of all advisors
    public static function get_advisors_with_id() {
        $temp_teachers = teacher_info::get_advisors_all_info();
        // This saves the number of teachers in the list.
        $count = $temp_teachers[0];
        for($i=1; $i<=$count; $i++) {
            $teacherid = $temp_teachers[$i]->teacherid;
            $teacher_list[$teacherid] =  $temp_teachers[$i]->lastname . '-' . $temp_teachers[$i]->firstname;
        }
        return $teacher_list;
    }

    // **Get a list of all advisors or teachers.
    public static function get_advisors_all_info() {
        global $DB;
        $sql = "SELECT * FROM {istudyteachers} ORDER By lastname ASC, firstname ASC";
        $params = null;
        $all_advisors = $DB->get_records_sql($sql, $params);
        $count = 0;
        foreach ($all_advisors as $this_advisor) {
            $count++;
            $advisor[$count] = new stdClass();
            $advisor[$count]->id = $this_advisor->id;
            $advisor[$count]->teacherid = $this_advisor->teacherid;
            $advisor[$count]->lastname = $this_advisor->lastname;
            $advisor[$count]->firstname = $this_advisor->firstname;
            $advisor[$count]->email = $this_advisor->email;
            $advisor[$count]->room = $this_advisor->room;
            $advisor[$count]->extension = $this_advisor->extension;
        }
        $advisor[0] = $count;
        return $advisor;
    }

    // Use the email to get the moodle id for the teacher.
    public static function get_teacherid($teacher_email) {
        global $DB;
        $teacherid = new stdClass();
        if ($teacherid = $DB->get_record('user', ['email' => $teacher_email])) {
            return $teacherid->id;
        }
        else{
            // The email was not found so return -1.
            return -1;
        }
    }

    // Get the advisor name.
    public static function get_teacher_name($advisorid) {
        global $DB;
        $this_teacher = new stdClass();
        $sql = "SELECT * FROM {istudyteachers} WHERE teacherid = '" . $advisorid . "'";
        $params = null;
        $this_teacher = $DB->get_record_sql($sql, $params);
        $advisor_name = $this_teacher->firstname . ' ' . $this_teacher->lastname;
        return $advisor_name;
    }

	// Only allow an administrator to modify teachers and courses.
	public static function not_an_administrator() {
		global $DB, $USER;
		$advisorid = $USER->id;
		$itemid = utils::get_fieldid('whichteacher');
		$search_results = $DB->get_record('user_info_data', ['userid'=>$advisorid, 'fieldid'=>$itemid], 'data');
		if ($search_results->data == 'Administrator') {
			return false;
		}
		else {
			return true;
		}
	}
}