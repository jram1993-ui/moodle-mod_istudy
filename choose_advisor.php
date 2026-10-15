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
require_once(__DIR__.'/choose_advisor_form.php');

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
$mform = new mod_istudy_choose_advisor_form(null, $formparams);
$mform->set_data($istudyid);

if ($mform->is_cancelled()) {
	// Cancelled forms redirect to the course main page.
	$url = new moodle_url('/course/view.php', array('id' => $course->id));
	redirect($url);
	return;	
} 
else if ($data = $mform->get_data()) {
	if ($data->advisorid == 'none') {
		echo $output->notification('You must choose an advisor');
	}
	else {
		// Get the advisor id from the advisor name chosen
		$USER->advisorid = $data->advisorid;
		// The monthly attendance has not been saved yet. Set to Yes after the first save
		$USER->just_saved = 'No';
		$semester_chosen = $USER->semesterctv;
		if ($semester_chosen == 1) {
			$choose_coursesurl = new \moodle_url('/mod/istudy/fall_courses.php', array('id' => $cm->id));
			redirect($choose_coursesurl);  
		}
		else if ($semester_chosen == 2) {
			$choose_coursesurl = new \moodle_url('/mod/istudy/spring_courses.php', array('id' => $cm->id));
			redirect($choose_coursesurl);  
		}
		else {
			$choose_coursesurl = new \moodle_url('/mod/istudy/summer_courses.php', array('id' => $cm->id));
			redirect($choose_coursesurl);  
		}
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