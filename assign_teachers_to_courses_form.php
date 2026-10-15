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
class mod_istudy_assign_teachers_to_courses_form extends moodleform
{

    public function definition()
	{
        $mform    = $this->_form;
		// Initialize list of courses
		$this_course = mod_istudy\utils::get_course_list();
		$number_of_courses = $this_course[0];

		// $teacher_list = [];
        $teacher_list = mod_istudy\teacher_info::get_advisors_all_info();
		$number_of_teachers = $teacher_list[0];

		if ($number_of_teachers == 0 OR $number_of_courses == 0) {
			$mform->addElement('html', '<h1>There are no teachers and/or courses in the system.</h1><br />');
			$mform->addElement('html', '<h3>Please return to the main menu an choose the option to adding teachers or adding courses.</h3>');

			// Hidden teacher_count number for sending the count back.
			$mform->addElement('hidden', 'teacher_count', $number_of_teachers);
			$mform->setType('teacher_count', PARAM_INT);

			// Hidden id number for sending the count back.
			$mform->addElement('hidden', 'course_count', $number_of_courses);
			$mform->setType('course_count', PARAM_INT);

			// Hidden id number for sending the data back.
			$mform->addElement('hidden', 'id');
			$mform->setType('id', PARAM_INT);

			// Add standard buttons, common to all modules.
			$mform->addElement('html', '<br><h3>  </h3>');
			$this->add_action_buttons(true, 'Return to Main Menu');
		}
		else {
			$number_of_rows = round(($number_of_teachers + 4) / 10);

			$mform->addElement('html', '<link rel="stylesheet" type="text/css" href="styles-assign_teachers_to_courses_form.css">');

			// Add the title at the top of the page.
			$mform->addElement('html', '<h1>' . get_config('mod_istudy', 'schoolname') . '</h1>');
			$mform->addElement('html', '<h2>Course assignment by Teacher</h2><br>');
			$mform->addElement('html', '<h3>Assign Teachers to the Courses they can Teach</h3><br>');

			// This is the table and it's header.
			$mform->addElement('html', '<table><tr><th width="2%">#</th><th width="5%">Course#</th><th width="13%" >Course Name</th><th colspan="10" width="80%" >Teachers - Choose teachers that can teach this Course</th></tr>');

			// List the current courses and the teachers assigned to them.
			for ($c = 1; $c <= $number_of_courses; $c++) {
				$mform->addElement('html', '<tr><td rowspan=' . $number_of_rows. '><center>'. $c . '</center></td>');
				$mform->addElement('html', '<td rowspan=' . $number_of_rows . '><center>' . $this_course[$c]->section . '</center></td>');
				$mform->addElement('html', '<td rowspan=' . $number_of_rows . '><center>' . $this_course[$c]->subject . '</center></td>');
				// Print the teachers in rows of 10.
				$t = 0;
				for ($r = 1; $r <= $number_of_rows; $r++) {
					// If it's not the 1st row, start a new row.
					if ($r <> 1) {
						$mform->addElement('html', '<tr>');
					}
					for ($i = 1; $i <= 10; $i++) {
						$t++;
						if ($t <= $number_of_teachers)	{
							$mform->addElement('html', '<td width="8%">');
							$teacher_teaches_course = 'course_' . $this_course[$c]->section .'_teacher_' . $t;
							$teacher_full_name = $teacher_list[$t]->lastname . ',<br>' . $teacher_list[$t]->firstname;
							$mform->addElement('advcheckbox', $teacher_teaches_course, $teacher_full_name, '', ['class' => 'mycheckbox']);
							$mform->setDefault($teacher_teaches_course,  mod_istudy\utils::course_taught_by_teacher( $this_course[$c]->section, $teacher_list[$t]->teacherid));
							$mform->addElement('html', '</td>');
						}
						else {
							$mform->addElement('html', '<td></td>');
						}
					}
					$mform->addElement('html', '</tr>');
				}
			}
			$mform->addElement('html', '</table>');

			// Hidden id number for sending the count back.
			$mform->addElement('hidden', 'course_count', $number_of_courses);
			$mform->setType('course_count', PARAM_INT);

			// Hidden id number for sending the count back.
			$mform->addElement('hidden', 'teacher_count', $number_of_teachers);
			$mform->setType('teacher_count', PARAM_INT);

			// Hidden id number for sending the data back.
			$mform->addElement('hidden', 'id');
			$mform->setType('id', PARAM_INT);

			// Add standard buttons, common to all modules.
			$mform->addElement('html', '<br><h3>  </h3>');
			$this->add_action_buttons($cancel = true, $submitlabel='Save changes');
		}
	}
}