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
require_once(__DIR__.'/fall_courses_form.php');

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
$mform = new mod_istudy_fall_courses_form(null, $formparams);
$mform->set_data($istudyid);

if ($mform->is_cancelled()) {
	// Cancelled forms redirect to the course main page.
	$url = new moodle_url('/course/view.php', array('id' => $course->id));
	redirect($url);
}
else if ($data = $mform->get_data()) {
	// If there are teachers and courses, create the drop down menus for each day and period.
	$course_count = $data->course_count;
	if ($course_count > 0) {
		$week_day[1] = 'monday';
		$week_day[2] = 'tuesday';
		$week_day[3] = 'wednesday';
		$week_day[4] = 'thursday';
		$week_day[5] = 'friday';
		// Save the active courses by course, teacher, day, and period.
		$advisor_id = $data->advisorid;
		for ($this_course = 1; $this_course <= $course_count; $this_course++) {
			$course_id = $data->{'course' . $this_course . 'courseid'};
			$course_name = $data->{'course' . $this_course . 'coursename'};
			for ($d = 1; $d <= 5; $d++) {
				for ($period = 1; $period <= 8; $period++) {
					$course_day_and_session = 'course' . $this_course .  $week_day[$d] . 'session' . $period;
					$course_included = $data->{$course_day_and_session};
					//Save the data to the table.
					mod_istudy\utils::save_course_status('fall', $course_id, $advisor_id, $d, $period, $course_included, $course_name);
				}
			}
		}
		mod_istudy\utils::update_dropdown_menus('fall');
		$USER->just_savedctv = 'Yes';
		$USER->semesterctv = 1;
		$url = new moodle_url('/mod/istudy/choose_advisor.php', array('id' => $cm->id));
		redirect($url);
	}
	else {
		// There are no courses and/or teachers to choose from.
		$url = new moodle_url('/course/view.php', array('id' => $course->id));
		redirect($url);
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
$mform->DISPLAY();
echo $OUTPUT->footer();