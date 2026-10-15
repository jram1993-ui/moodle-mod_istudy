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
require_once(__DIR__.'/assign_teachers_to_courses_form.php');

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
$mform = new mod_istudy_assign_teachers_to_courses_form(null, $formparams);
$mform->set_data($istudyid);

if ($mform->is_cancelled()) {
	// Cancelled forms redirect to the course main page.
	$url = new moodle_url('/course/view.php', array('id' => $course->id));
	redirect($url);
	return;
}
else if ($data = $mform->get_data()) {
	$teacher_count = $data->teacher_count;
	$course_count = $data->course_count;
	$submission = new stdClass();
	$submission_new = new stdClass();
	$this_course_and_teacher[][] = array();
	if (($teacher_count > 0) AND ($course_count > 0)) {
		// Get a list of all of the teachers and courses.
		$this_teacher = mod_istudy\teacher_info::get_advisors_all_info();
		$this_course =  mod_istudy\utils::get_course_list();
		for ($c = 1; $c <= $course_count; $c++) {
			for ($t = 1; $t <= $teacher_count; $t++) {
				$teacher_teaches_course = 'course_' . $this_course[$c]->section .'_teacher_' . $t;
				// $databaseid = 'database_course_' . $this_course[$c]->section .'_teacher_' . $t;
				$this_course_and_teacher[$c][$t] = new stdClass();
				// $this_course_and_teacher[$c][$t]->id = $databaseid;
				$this_course_and_teacher[$c][$t]->section = $this_course[$c]->section;
				$this_course_and_teacher[$c][$t]->subject = $this_course[$c]->subject;
				$this_course_and_teacher[$c][$t]->teacherid = $this_teacher[$t]->teacherid;
				$this_course_and_teacher[$c][$t]->teacher_name = $this_teacher[$t]->lastname . '-' . $this_teacher[$t]->firstname;
				$this_course_and_teacher[$c][$t]->active =  $data->$teacher_teaches_course;
			}
		}
		// print_object($this_course_and_teacher);
		for ($c = 1; $c <= $course_count; $c++) {
			for ($t = 1; $t <= $teacher_count; $t++) {
				// This is true if the teacher teaches the courese.
				// $teacher_teaches_course = 'course_' . $this_course[$c]->section .'_teacher_' . $t;
				// If the course already exists, update the record.
				$this_section = $this_course_and_teacher[$c][$t]->section;
				$this_teacherid = $this_course_and_teacher[$c][$t]->teacherid;

				$table = 'istudycourses_teachers';
				$select = "section = :section AND teacherid = :teacherid";
				$params =['section'=>$this_section, 'teacherid'=>$this_teacherid];
				if ($database_id = $DB->get_record_select($table, $select, $params)) {
					// Save the data.
					$submission->id = $database_id->id;
					$submission->section = $this_course_and_teacher[$c][$t]->section;
					$submission->subject = $this_course_and_teacher[$c][$t]->subject;
					$submission->teacherid = $this_course_and_teacher[$c][$t]->teacherid;
					$submission->teacher_name = $this_course_and_teacher[$c][$t]->teacher_name;
					$submission->active = $this_course_and_teacher[$c][$t]->active;
					try {
						$transaction = $DB->start_delegated_transaction();
						$DB->update_record('istudycourses_teachers', $submission);
						// Assuming that the insert worked, we get to the following line.
						$transaction->allow_commit();
					} catch(Exception $e) {
						$transaction->rollback($e);
						$course_with_error = 'The entry for ' . $submission_new->subject . ' and ' . $submission_new->teacher_name . ' was not saved. An error was found in the details.';
						echo $OUTPUT->notification($course_with_error);
					}
				}
				// If the course is new, insert the record.
				else {
					$submission_new->section = $this_course_and_teacher[$c][$t]->section;
					$submission_new->subject = $this_course_and_teacher[$c][$t]->subject;
					$submission_new->teacherid = $this_course_and_teacher[$c][$t]->teacherid;
					$submission_new->teacher_name = $this_course_and_teacher[$c][$t]->teacher_name;
					$submission_new->active = $this_course_and_teacher[$c][$t]->active;
					try {
						$transaction = $DB->start_delegated_transaction();
						$DB->insert_record('istudycourses_teachers', $submission_new);
						// Assuming that the insert worked, we get to the following line.
						$transaction->allow_commit();
					} catch(Exception $e) {
						$transaction->rollback($e);
						$course_with_error = 'The entry for ' . $submission_new->subject . ' and ' . $submission_new->teacher_name . ' was not saved. An error was found in the details.';
						echo $OUTPUT->notification($course_with_error);
					}
				}
			}
		}
		$USER->just_savedctv = 'Yes';
		$assign_teachers_to_courses_url = new moodle_url('/mod/istudy/assign_teachers_to_courses.php', array('id' => $cm->id));
		redirect($assign_teachers_to_courses_url, null, 0);
	}
	else {
		// There are no courses and/or teachers to choose from.
		$USER->just_savedctv = 'MissingTeacherCourse';
		$assign_teachers_to_courses_url = new moodle_url('/course/view.php', array('id' => $course->id));
		redirect($assign_teachers_to_courses_url, null, 0);
	}
}
else {
	// Form has not been submitted or there was an error
	// Just display the form
	$mform->set_data(array('id' => $id));
}

// Output starts here.
echo $OUTPUT->header();
if ($USER->just_savedctv == 'Yes') {
	echo $OUTPUT->notification('Changes saved', 'notifysuccess');
	$USER->just_savedctv == 'No';
}
else if ($USER->just_savedctv == 'MissingTeacherCourse') {
	echo $OUTPUT->notification('There are no courese and/or teachers. Please return to the main menu and enter the missing items.');
	$USER->just_savedctv == 'No';
}
$mform->display();
echo $OUTPUT->footer();