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
require_once(__DIR__.'/teachers_delete_form.php');

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
$mform = new mod_istudy_teachers_delete_form(null, $formparams);
$mform->set_data($istudyid);

if ($mform->is_cancelled()) {
	// Cancelled forms redirect to the course main page.
	$url = new moodle_url('/course/view.php', array('id' => $course->id));
	redirect($url);
	return;
}
else if ($data = $mform->get_data()) {
	// Make sure that there are teachers.
	if ($data->count > 0) {
		// Get the row id (teacher chosen) to delete from table istudyteachers.
		$teacher_to_delete = $data->teacher_to_delete;
		// Get the teacher moodle id.
		$sql = "SELECT teacherid FROM {istudyteachers} WHERE id = '" . $teacher_to_delete . "'";
        $params = null;
        $this_teacher = $DB->get_record_sql($sql, $params);
		// Delete the teacher name from the list of teachers.
		try {
			$transaction = $DB->start_delegated_transaction();
			// Use the teacher Moodle User ID to delete the teacher from the following tables.
			$DB->delete_records('istudycourses_teachers', ['teacherid' => $this_teacher->teacherid]);
			$DB->delete_records('istudymenu_fall', ['teacherid' => $this_teacher->teacherid]);
			$DB->delete_records('istudymenu_spring', ['teacherid' => $this_teacher->teacherid]);
			$DB->delete_records('istudymenu_summer', ['teacherid' => $this_teacher->teacherid]);
			// Use the moodleid of the 1 entry in this table only.
			$DB->delete_records('istudyteachers', ['id' => $teacher_to_delete]);
			// Assuming that the update worked, we get to the following line.
			$transaction->allow_commit();
			mod_istudy\utils::update_studentprofile_teachers();
		} catch(Exception $e) {
			$transaction->rollback($e);
			$course_with_error = 'The teacher was not deleted. An error was found in the details.';
			echo $OUTPUT->notification($course_with_error);
		}
		// Change the user status to Parent.
		$field_id = mod_istudy\utils::get_fieldid('whichteacher');
		$whichteacher_id = $DB->get_record('user_info_data', ['userid'=>$this_teacher->teacherid, 'fieldid'=>$field_id], '*');
		$submission = new stdClass();
		$submission->id = $whichteacher_id->id;
		$submission->userid = $this_teacher->teacherid;
		$submission->data = "Parent";
		try {
			$transaction = $DB->start_delegated_transaction();
			$DB->update_record('user_info_data', $submission, false);
			$transaction->allow_commit();
		}
		catch(Exception $e) {
			$transaction->rollback($e);
			echo $OUTPUT->notification("The user profile field is still set as TEACHER for this user.");
		}
		$USER->just_savedctv = 'Yes';
		$teacher_deleteurl = new moodle_url('/mod/istudy/teachers_delete.php', array('id' => $cm->id));
		redirect($teacher_deleteurl, null, 0);
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
	echo $OUTPUT->notification('The teacher was deleted.', 'notifysuccess');
	$USER->just_savedctv == 'No';
}
$mform->display();
echo $OUTPUT->footer();