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
require_once(__DIR__.'/search_criteria_form.php');

global $DB, $USER;

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

// The user must be an administrator.
if (mod_istudy\teacher_info::not_an_administrator()) {
	$url = new moodle_url('/course/view.php', array('id' => $course->id));
	redirect($url, 'You must be an adminstrator to access this page...', 5);
}

// Reset iStudy student profile drop down menus and dates
mod_istudy\utils::update_studentprofile_options();

// Output starts here.
echo $OUTPUT->header();

$formparams = array('cm' => $cm, 'modcontext' => $modulecontext);
$mform = new mod_istudy_search_criteria_form(null, $formparams);
$mform->set_data($istudyid);

if ($mform->is_cancelled()) {
	// Cancelled forms redirect to the course main page.
	$url = new moodle_url('/course/view.php', array('id' => $course->id));
	redirect($url);
	return;	
} else if ($data = $mform->get_data()) {
	$my_choice = $data->whichform;
	if ($my_choice == 1) {
		$USER->just_savedctv = 'No';
		$USER->semesterctv = 1;
		$url = new moodle_url('/mod/istudy/choose_advisor.php', array('id' => $cm->id));
		redirect($url);
	}
	if ($my_choice == 2) {
		$USER->just_savedctv = 'No';
		$USER->semesterctv = 2;
		$url = new moodle_url('/mod/istudy/choose_advisor.php', array('id' => $cm->id));
		redirect($url);
	}
	if ($my_choice == 3) {
		$USER->just_savedctv = 'No';
		$USER->semesterctv = 3;
		$url = new moodle_url('/mod/istudy/choose_advisor.php', array('id' => $cm->id));
		redirect($url);
	}
	if ($my_choice == 4) {
		$USER->just_savedctv = 'No';
		$url = new moodle_url('/mod/istudy/assign_teachers_to_courses.php', array('id' => $cm->id));
		redirect($url);
	}
	if ($my_choice == 5) {
		$USER->just_savedctv = 'No';
		$url = new moodle_url('/mod/istudy/courses_time_value.php', array('id' => $cm->id));
		redirect($url);
	}
	if ($my_choice == 6) {
		$USER->just_savedctv = 'No';
		$url = new moodle_url('/mod/istudy/courses_delete.php', array('id' => $cm->id));
		redirect($url);
	}
	if ($my_choice == 7) {
		$USER->just_savedctv = 'No';
		$url = new moodle_url('/mod/istudy/enter_aeriesnumbers.php', array('id' => $cm->id));
		redirect($url);
	}
	if ($my_choice == 8) {
		$USER->just_savedctv = 'No';
		$url = new moodle_url('/mod/istudy/teachers_add_new.php', array('id' => $cm->id));
		redirect($url);
	}
	if ($my_choice == 9) {
		$USER->just_savedctv = 'No';
		$url = new moodle_url('/mod/istudy/teachers_delete.php', array('id' => $cm->id));
		redirect($url);
	}	
} else {
	// Form has not been submitted or there was an error
	// Just display the form
	$mform->set_data(array('id' => $id)); 
}

$mform->DISPLAY();	
echo $OUTPUT->footer();