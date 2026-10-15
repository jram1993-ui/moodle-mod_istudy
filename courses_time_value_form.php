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

// use stdClass;

require_once($CFG->dirroot.'/lib/formslib.php');

/**
 * Module instance settings form
 *
 * @package    mod_istudy
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_istudy_courses_time_value_form extends moodleform
{

    public function definition()
	{
        $mform    = $this->_form;

		$default_hour_value = 1;
		$this_course = mod_istudy\utils::get_course_list();
		$count = $this_course[0];

		$mform->addElement('html', '<html><header><link rel="stylesheet" type="text/css" href="styles-courses_time_value_form.css"></header><body>');

		// Add the title at the top of the page.
		$mform->addElement('html', '<h1>' . get_config('mod_istudy', 'schoolname') . '</h1>');
		$mform->addElement('html', '<h2>Add/Modify Course</h2><br>');

		// The table where user can select a period for all courses.
		$mform->addElement('html', '<table><tr><th colspan="4" class="cellborderall"><h3><center>Add/Modify Course and Add Synchronous Time Value</center></h3></th></tr>');
		$mform->addElement('html', '<tr><th class="cellborderall">#</th><th class="cellborderall">Course Number</th><th>Course Name</th><th>Synchronous hours for class</th></tr>');

		// Add a new course at the start of the table.
		$mform->addElement('html', '<tr><td><center>0</center></td><td>');
			$mform->addElement('textarea', 'course_section_new', '', 'wrap="virtual" rows="1" cols="10"');
			$mform->setDefault('course_section_new', '');
			$mform->setType('course_section_new', PARAM_TEXT);
		$mform->addElement('html', '</td><td>');
			$mform->addElement('textarea', 'course_name_new', '', 'wrap="virtual" rows="1" cols="50"');
			$mform->setDefault('course_name_new', '');
			$mform->setType('course_name_new', PARAM_TEXT);
		$mform->addElement('html', '</td><td>');

		$hour_value = 'hour_value_new';
		$radioarray = array();
		$radioarray[] = $mform->createElement('radio', $hour_value, '', '0', '0', ['class' => 'radiobuttons-black']);
		$radioarray[] = $mform->createElement('radio', $hour_value, '', '0.5', '0.5', ['class' => 'radiobuttons-black']);
		$radioarray[] = $mform->createElement('radio', $hour_value, '', '1', '1', ['class' => 'radiobuttons-blue']);
		$radioarray[] = $mform->createElement('radio', $hour_value, '', '1.5', '1.5', ['class' => 'radiobuttons-black']);
		$radioarray[] = $mform->createElement('radio', $hour_value, '', '2', '2', ['class' => 'radiobuttons-blue']);
		$radioarray[] = $mform->createElement('radio', $hour_value, '', '2.5', '2.5', ['class' => 'radiobuttons-black']);
		$radioarray[] = $mform->createElement('radio', $hour_value, '', '3', '3', ['class' => 'radiobuttons-blue']);
		$radioarray[] = $mform->createElement('radio', $hour_value, '', '3.5', '3.5', ['class' => 'radiobuttons-black']);
		$radioarray[] = $mform->createElement('radio', $hour_value, '', '4', '4', ['class' => 'radiobuttons-blue']);
		$radioarray[] = $mform->createElement('radio', $hour_value, '', '4.5', '4.5', ['class' => 'radiobuttons-black']);
		$radioarray[] = $mform->createElement('radio', $hour_value, '', '5', '5', ['class' => 'radiobuttons-blue']);
		$mform->addGroup($radioarray, 'radioar', '', array(' '), false);
		$mform->setDefault($hour_value, $default_hour_value);
		$mform->setType($hour_value, PARAM_TEXT);
		$mform->addElement('html', '</td></tr>');

		for ($i = 1; $i <= $count; $i++) {
			$mform->addElement('html', '<tr><td><center>' . $i . '</center></td><td><center>' . $this_course[$i]->section . '</center></td>');
			// The course name for this course.
			$temp_this_subject = 'subject' . $i;
			$mform->addElement('html', '<td>');
				$mform->addElement('textarea', $temp_this_subject, '', 'wrap="virtual" rows="1" cols="50"');
				$mform->setDefault($temp_this_subject, $this_course[$i]->subject);
				$mform->setType($temp_this_subject, PARAM_TEXT);
			$mform->addElement('html', '</td><td>');
			// Look for previously saved hour value.
			$hour_value = 'hour_value' . $i;
			$radioarray = array();
			$radioarray[] = $mform->createElement('radio', $hour_value, '', '0', '0', ['class' => 'radiobuttons-black']);
			$radioarray[] = $mform->createElement('radio', $hour_value, '', '0.5', '0.5', ['class' => 'radiobuttons-black']);
			$radioarray[] = $mform->createElement('radio', $hour_value, '', '1', '1', ['class' => 'radiobuttons-blue']);
			$radioarray[] = $mform->createElement('radio', $hour_value, '', '1.5', '1.5', ['class' => 'radiobuttons-black']);
			$radioarray[] = $mform->createElement('radio', $hour_value, '', '2', '2', ['class' => 'radiobuttons-blue']);
			$radioarray[] = $mform->createElement('radio', $hour_value, '', '2.5', '2.5', ['class' => 'radiobuttons-black']);
			$radioarray[] = $mform->createElement('radio', $hour_value, '', '3', '3', ['class' => 'radiobuttons-blue']);
			$radioarray[] = $mform->createElement('radio', $hour_value, '', '3.5', '3.5', ['class' => 'radiobuttons-black']);
			$radioarray[] = $mform->createElement('radio', $hour_value, '', '4', '4', ['class' => 'radiobuttons-blue']);
			$radioarray[] = $mform->createElement('radio', $hour_value, '', '4.5', '4.5', ['class' => 'radiobuttons-black']);
			$radioarray[] = $mform->createElement('radio', $hour_value, '', '5', '5', ['class' => 'radiobuttons-blue']);
			$mform->addGroup($radioarray, 'radioar', '', array(' '), false);
			$mform->setDefault($hour_value, $this_course[$i]->hour_value);
			$mform->setType($hour_value, PARAM_TEXT);
			$mform->addElement('html', '</td></tr>');

			// Hidden database id for this course.
			$temp_this_courseid = 'courseid' . $i;
			$mform->addElement('hidden', $temp_this_courseid, $this_course[$i]->id);
			$mform->setType($temp_this_courseid, PARAM_INT);
			// Hidden section number for this course.
			$temp_this_section = 'section' . $i;
			$mform->addElement('hidden', $temp_this_section, $this_course[$i]->section);
			$mform->setType($temp_this_section, PARAM_TEXT);
		}
		$mform->addElement('html', '</table><br>');
		// Hidden id number for sending the count back.
		$mform->addElement('hidden', 'count', $count);
        $mform->setType('count', PARAM_INT);

		// Hidden id number for sending the data back.
		$mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

		// Add standard buttons, common to all modules.
		$mform->addElement('html', '<br><h3>  </h3>');
        $this->add_action_buttons(true, 'Save changes');
		$mform->addElement('html', '</body></html>');
	}
}