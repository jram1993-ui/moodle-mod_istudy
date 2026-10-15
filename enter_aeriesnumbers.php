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
require_once(__DIR__.'/enter_aeriesnumbers_form.php');

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

// Output starts here.
echo $OUTPUT->header();

$formparams = array('cm' => $cm, 'modcontext' => $modulecontext);
$mform = new mod_istudy_enter_aeriesnumbers_form(null, $formparams);
$mform->set_data($istudyid);
if ($mform->is_cancelled()) {
	// Cancelled forms redirect to the course main page.
	$url = new moodle_url('/course/view.php', array('id' => $course->id));
	redirect($url);
}
else if ($data = $mform->get_data()) {
	// Get the course data
	$teacher_count = $data->teacher_count;
	if ($teacher_count > 0) {
		$submission = new stdClass();
		$submission->timemodified = time();
		$section_count = $data->section_count;
		if ($section_count > 0) {
			for ($count = 1; $count <= $section_count; $count++) {
				// Get the attendance data
				$submission->id = $data->{'record_id'.$count};
				$submission->teacher_id = $data->{'teacher_id'.$count};
				$submission->day_of_week = $data->{'day_of_week'.$count};
				$submission->session = $data->{'session'.$count};
				$submission->section_number = mod_istudy\utils::remove_extra_symbols($data->{'section_number'.$count});
				try {
					$transaction = $DB->start_delegated_transaction();
					$table = 'istudycoursenum_sections';
					$select = "teacher_id = :teacher_id AND day_of_week = :day_of_week AND session = :session AND section_number = :section_number";
					$params =['teacher_id'=>$submission->teacher_id, 'day_of_week'=>$submission->day_of_week, 'session'=>$submission->session, 'section_number'=>$submission->section_number];
					// The record already exists
					if ($submission->id > -1) {
						$DB->update_record('istudycoursenum_sections', $submission);
					}
					else if ($section_info = $DB->get_record_select($table, $select, $params)) {
						$submission->id = $section_info->id;
						$DB->update_record('istudycoursenum_sections', $submission);
					}
					// The record is new
					else {
						$DB->insert_record('istudycoursenum_sections', $submission);
					}

					// Assuming that one of the inserts worked, we get to the following line.
					$transaction->allow_commit();
				} catch(Exception $e) {
					$week_day[1] = 'Monday';
					$week_day[2] = 'Tuesday';
					$week_day[3] = 'Wednesday';
					$week_day[4] = 'Thursday';
					$week_day[5] = 'Friday';
					$transaction->rollback($e);
					$this_teacher = mod_istudy\teacher_info::get_single_teacher($submission->teacher_id);
					$teacher_with_error = 'The entry for ' . $this_teacher->firstname . ' ' . $this_teacher->lastname . ' was not saved for ' .
						$week[$submission->day_of_week] . ': session '. $submission->session;
					echo $OUTPUT->notification($teacher_with_error);
				}
			}
			echo $OUTPUT->notification('Changes saved', 'notifysuccess');
		}
		else {
			echo $OUTPUT->notification('There are no teachers entered in the database');
		}
	}
	else {
		// There are no teachers to choose from.
		$url = new moodle_url('/course/view.php', array('id' => $course->id));
		redirect($url);
	}
} else {
	// Form has not been submitted or there was an error
	// Just display the form
	$mform->set_data(array('id' => $id));
}

$mform->DISPLAY();
echo $OUTPUT->footer();