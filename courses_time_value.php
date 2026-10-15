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
require_once(__DIR__.'/courses_time_value_form.php');

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
$mform = new mod_istudy_courses_time_value_form(null, $formparams);
$mform->set_data($istudyid);

if ($mform->is_cancelled()) {
	// Cancelled forms redirect to the course main page.
	$url = new moodle_url('/course/view.php', array('id' => $course->id));
	redirect($url);
	return;	
}
else if ($data = $mform->get_data()) {
	$course_count = $data->count;	
	if ($course_count > 0) {
		$submission = new stdClass();
		for ($count = 1; $count <= $course_count; $count++) {
			// Get the attendance data
			$submission->id = $data->{'courseid'.$count};
			$submission->section = $data->{'section'.$count};
			$submission->subject =  mod_istudy\utils::remove_extra_symbols(mod_istudy\utils::custom_trim($data->{'subject'.$count}));
			$submission->hour_value = $data->{'hour_value'.$count};
			// If the name of the course was deleted, don't save the course.
			$this_course = mod_istudy\utils::get_course_list();
			if (($submission->subject <> '') AND mod_istudy\utils::is_not_a_duplicate_name($submission->section, $submission->subject, $this_course)) {
				try {
					$transaction = $DB->start_delegated_transaction();
					$DB->update_record('istudycourse_hours', $submission);
					// Assuming that the update worked, we get to the following line.
					$transaction->allow_commit();
				} catch(Exception $e) {
					$transaction->rollback($e);
					$course_with_error = 'The entry for section <b> ' . $submission->section . ' (' . $submission->subject . ')</b> was not saved. An error was found in the details.';
					echo $OUTPUT->notification($course_with_error);
				}
			}
		}
		$USER->just_savedctv = 'Yes';
	}
	// If a new course is found, save the course to the database.
	$new_course_found = false;
	$submission_new = new stdClass();
	$submission_new->section =  mod_istudy\utils::remove_extra_symbols(mod_istudy\utils::custom_trim($data->course_section_new));
	$submission_new->subject =  mod_istudy\utils::remove_extra_symbols(mod_istudy\utils::custom_trim($data->course_name_new));
	$submission_new->hour_value = $data->hour_value_new;
	if (($submission_new->section<>'') AND ($submission_new->subject<>'')) {		
		$this_course = mod_istudy\utils::get_course_list();
		if (mod_istudy\utils::is_not_a_duplicate($submission_new->section, $submission_new->subject, $this_course)) {
			$new_course_found = true;
			$USER->just_savedctv = 'New';
			try {
				$transaction = $DB->start_delegated_transaction();			
				$DB->insert_record('istudycourse_hours', $submission_new);
				// Assuming that the insert worked, we get to the following line.
				$transaction->allow_commit();
			} catch(Exception $e) {
				$transaction->rollback($e);
				$course_with_error = 'The entry for section <b> ' . $submission_new->section . ' (' . $submission_new->subject . ')</b> was not saved. An error was found in the details.';
				echo $OUTPUT->notification($course_with_error);
			}
		}
		else {
			$USER->just_savedctv = 'Duplicate';
		}
	}
	if (($course_count > 0) OR $new_course_found) {
		$enter_dailyattendurl = new moodle_url('/mod/istudy/courses_time_value.php', array('id' => $cm->id));
		redirect($enter_dailyattendurl, null, 0);  
	}
	else {
		echo $OUTPUT->notification('There are no courses to save.');
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
	echo $OUTPUT->notification('Changes saved', 'notifysuccess');	
	$USER->just_savedctv == 'No';
}
else if ($USER->just_savedctv == 'New') {
	echo $OUTPUT->notification('The new course was saved', 'notifysuccess');	
	$USER->just_savedctv == 'No';
}
else if ($USER->just_savedctv == 'Duplicate') {
	echo $OUTPUT->notification('The new course was not saved. The course number and/or name is already being used.');	
	$USER->just_savedctv == 'No';
}
$mform->display();
echo $OUTPUT->footer();