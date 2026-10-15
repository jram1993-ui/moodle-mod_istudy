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
 * The main istudy configuration form used for entering attendance credit
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_istudy
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/lib/formslib.php');

/**
 * Module instance settings form
 *
 * @package    mod_istudy
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_istudy_summer_courses_form extends moodleform
{
    public function definition()
	{
		global $USER;

        $mform    = $this->_form;
		$advisorid = $USER->advisorid;
		$advisorname = mod_istudy\teacher_info::get_teacher_name($advisorid);
		$week_day[1] = 'monday';
		$week_day[2] = 'tuesday';
		$week_day[3] = 'wednesday';
		$week_day[4] = 'thursday';
		$week_day[5] = 'friday';

		// Initialize list of courses
		$master_course_list_temp = [];
		$master_course_list_temp = mod_istudy\utils::get_teacher_course_list($advisorid);
		$count = $master_course_list_temp[0];
		// Make sure there are teachers and courses entered into the system.
		if ($count == 0) {
			$mform->addElement('html', '<h1>There are no courses in the system for this teacher.</h1><br />');
			$mform->addElement('html', '<h3>Please return to the main menu an choose the option for assigning courses to the teacher.</h3>');

			// Hidden id number for sending the count back.
			$mform->addElement('hidden', 'course_count', 0);
			$mform->setType('course_count', PARAM_INT);

			// Hidden id number for sending the data back.
			$mform->addElement('hidden', 'id');
			$mform->setType('id', PARAM_INT);

			// Add standard buttons, common to all modules.
			$mform->addElement('html', '<br><h3>  </h3>');
			$this->add_action_buttons(true, 'Return to Main Menu');
		}
		else {
			for ($i = 1; $i <= $count; $i++) {
				$course[$i] = new stdClass();
				$course[$i]->courseid = $master_course_list_temp[$i]->courseid;
				$course[$i]->name = $master_course_list_temp[$i]->name;;
				for ($d = 1; $d <= 5; $d++) {
					for ($period = 1; $period <= 8; $period++){
						$day_and_session = $week_day[$d] . $period;
						$course[$i]->$day_and_session = 0;
						if (mod_istudy\utils::course_is_active('summer', $course[$i]->courseid, $advisorid, $d, $period)) {
							$course[$i]->$day_and_session = 1;
						}
					}
				}
			}
			$mform->addElement('html', '<link rel="stylesheet" type="text/css" href="styles-fall_spring_summer_form.css">');

			// Add the title at the top of the page.
			$mform->addElement('html', '<h1>' . get_config('mod_istudy', 'schoolname') . '</h1>');
			$mform->addElement('html', '<h2>' . $advisorname . ' - Summer Courses</h2>');
			$mform->addElement('html', '<h3>Select the Day and the Session each Course is taught by the Teacher</h3><br>');

			// The table where user can select a period for all courses.
			$mform->addElement('html', '<table><tr><th colspan="8" class="cellborderall">Select a Period for All Courses</th></tr><tr>');
			for ($period = 1; $period <= 8; $period++) {
				$mform->addElement('html', '<th>Period ' . $period . '</th>');
			}
			$mform->addElement('html', '</tr><tr class="white_background">');
			for ($period = 1; $period <= 8; $period++) {
				$mform->addElement('html', '<td class="mycell0">');
				$this->add_checkbox_controller($period, 'ALL', '', 0);
				$mform->addElement('html', '</center></td>');
			}
			$mform->addElement('html', '</tr></table><br>');

			// The table with the courses, days, and sessions starts here.
			$mform->addElement('html', '<table class="table1"><tr><th width="2%">#</th><th width="13%" class="cellborderall">Course</th>' .
				'<th width="17%" colspan="8" class="cellborderall">Monday</th>' .
				'<th width="17%" colspan="8" class="cellborderall">Tuesday</th>' .
				'<th width="17%" colspan="8" class="cellborderall">Wednesday</th>' .
				'<th width="17%" colspan="8" class="cellborderall">Thursday</th>' .
				'<th width="17%" colspan="8" class="cellborderall">Friday</th></tr>');

			// Set up the rows in the table with the courses and the sessions for each week day.
			for ($this_course = 1; $this_course <= $count; $this_course++) {
				// Hidden id number for sending the course id back.
				$course_id = 'course' . $this_course . 'courseid';
				$mform->addElement('hidden', $course_id,  $course[$this_course]->courseid);
				$mform->setType($course_id, PARAM_TEXT);
				// Hidden id number for sending the course name back.
				$course_name = 'course' . $this_course . 'coursename';
				$mform->addElement('hidden', $course_name,  $course[$this_course]->name);
				$mform->setType($course_name, PARAM_TEXT);
				$temp_courseinfo = mod_istudy\utils::get_mycourse($course[$this_course]->name);
				$temp_coursename = $temp_courseinfo->subject . ' (' . $temp_courseinfo->section . ')';
				$mform->addElement('html', '<tr><td class="mycell0"><center>' . $this_course . '</center></td><td class="mycell0"><b>' . $temp_coursename . '</b></td>');
				for ($d = 1; $d <= 5; $d++) {
					for ($period = 1; $period <= 8; $period++){
						// First column assigns $count (the current number in the series) to each student
						$course_day_session = 'course' . $this_course . $week_day[$d] . 'session' . $period;
						$day_and_session = $week_day[$d] . $period;
						$checkbox_value = $course[$this_course]->{$day_and_session};
						$mycell = 'mycell' . $d;
						$mform->addElement('html', '<td class=' . $mycell . '>');
						//$this_group = $this_course . $period;
						$mform->addElement('advcheckbox', $course_day_session, null, $period,['group' => $period], 1);
						$mform->setDefault($course_day_session, $checkbox_value);
						$mform->addElement('html', '</td>');
					};
				}
				$mform->addElement('html', '</tr><tr>');
			}
			$mform->addElement('html', '</table>');

			// Hidden advisorid
			$mform->addElement('hidden', 'advisorid', $advisorid);
			$mform->setType('advisorid', PARAM_INT);

			// Hidden course count
			$mform->addElement('hidden', 'course_count', $count);
			$mform->setType('course_count', PARAM_INT);

			// Hidden id number for sending the data back.
			$mform->addElement('hidden', 'id');
			$mform->setType('id', PARAM_INT);

			// Add standard buttons, common to all modules.
			$mform->addElement('html', '<br><h3>  </h3>');
			$this->add_action_buttons($cancel = true, $submitlabel='Save changes');
		}
	}
}