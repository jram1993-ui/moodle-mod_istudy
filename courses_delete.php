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
 * Prints a particular instance of istudy
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_istudy
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once(__DIR__.'/courses_delete_form.php');

global $DB;

/**
 * Course module id.
 */
$id = optional_param('id', 0, PARAM_INT);

/**
 * Activity instance id.
 */
$i = optional_param('i', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('istudy', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('istudy', array('id' => $cm->instance), '*', MUST_EXIST);
} else {
    $moduleinstance = $DB->get_record('istudy', array('id' => $i), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('istudy', $moduleinstance->id, $course->id, false, MUST_EXIST);
}
$istudyid = $cm->instance;

require_login($course, true, $cm);

$modulecontext = \context_module::instance($cm->id);

$PAGE->set_url('/mod/istudy/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);

$formparams = array('cm' => $cm, 'modcontext' => $modulecontext);
$mform = new mod_istudy_courses_delete_form(null, $formparams);
$mform->set_data($istudyid);

if ($mform->is_cancelled()) {
	// Cancelled forms redirect to the course main page.
	$url = new moodle_url('/course/view.php', array('id' => $course->id));
	redirect($url);
	return;	
}
else if ($data = $mform->get_data()) {
	// Make sure that there are courses.
	if ($data->count > 0) {
		$submission = new stdClass();
		// Get the attendance data
		$course_moodle_id = $data->course_to_delete;
		$course_section_number = mod_istudy\utils::get_course_section($course_moodle_id);
		// Delete the course using the moodle id.
		try {
			$transaction = $DB->start_delegated_transaction();
			// Use the course number (section) assigned by the school to delete the entries in this table.
			$select = "section = :section";
			$params = ['section' => $course_section_number];
			$DB->delete_records_select('istudycourses_teachers', $select, $params);
			// Use the course number (section) assigned by the school to delete the entries in these tables.
			$select = "courseid = :courseid";
			$params = ['courseid' => $course_section_number];
			$DB->delete_records_select('istudymenu_fall', $select, $params);
			$DB->delete_records_select('istudymenu_spring', $select, $params);
			$DB->delete_records_select('istudymenu_summer', $select, $params);
			// Use the moodleid of the 1 entry in this table only.
			$DB->delete_records('istudycourse_hours', ['id' => $course_moodle_id]);
			// Assuming that the update worked, we get to the following line.
			$transaction->allow_commit();
		} catch(Exception $e) {
			$transaction->rollback($e);
			$course_with_error = 'The course was not deleted. An error was found in the details.';
			echo $OUTPUT->notification($course_with_error);
		}
		$USER->just_savedctv = 'Yes';
		$enter_dailyattendurl = new moodle_url('/mod/istudy/courses_delete.php', array('id' => $cm->id));
		redirect($enter_dailyattendurl, null, 0); 
	}
	else {
		// There are not courses so redirect to the course main page.
		$url = new moodle_url('/course/view.php', array('id' => $course->id));
		redirect($url);
	}
} 
else {
	// Form has not been submitted or there was an error
	// Just display the form
	$mform->set_data(array('id' => $id)); 
}

// Finish the page.
echo $OUTPUT->header();
if ($USER->just_savedctv == 'Yes') {
	echo $OUTPUT->notification('The course was deleted.', 'notifysuccess');	
	$USER->just_savedctv == 'No';
}
$mform->display();
echo $OUTPUT->footer();