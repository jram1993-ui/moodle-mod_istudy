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
class mod_istudy_courses_delete_form extends moodleform 
{

    public function definition() 
	{
        $mform    = $this->_form;
		
		$this_course = mod_istudy\utils::get_course_list();
		$count = $this_course[0];
		if ($count > 0) {
			$mform->addElement('html', '<html><header></header><body><link rel="stylesheet" type="text/css" href="styles-courses_time_value_form.css">');
			
			// Add the title at the top of the page.
			$mform->addElement('html', '<h1>' . get_config('mod_istudy', 'schoolname') . '</h1>');
			$mform->addElement('html', '<h2>Delete Courses</h2><br>');

			// The table where user can select a period for all courses.
			$mform->addElement('html', '<table style="width:60%"><tr><th colspan="4" class="cellborderall"><h3><center>Choose the course to DELETE</center></h3></th></tr>');
			$mform->addElement('html', '<tr><th class="cellborderall" width="5%">#</th><th class="cellborderall" width="25%">Course Number</th><th width="45%">Course Name</th><th width="25%">Time Value</th></tr>');
			// $radioarray = [];	
			for ($i = 1; $i <= $count; $i++) {
				$mform->addElement('html', '<tr><td class="mycell6">');	
				$mform->addElement('radio', 'course_to_delete', '', $i, $this_course[$i]->id, ['class' => 'radiobuttons-delete']);
				$mform->addElement('html', '</td><td>' . $this_course[$i]->section . '</td><td>' . $this_course[$i]->subject . '</td><td>' . $this_course[$i]->hour_value . '</td></tr>');
			}
			$mform->setDefault('course_to_delete', $this_course[1]->id);
			$mform->setType('course_to_delete', PARAM_INT);
		
			$mform->addElement('html', '</table><br>');

			// Hidden count of the total number of courses.
			$mform->addElement('hidden', 'count', $count);
			$mform->setType('count', PARAM_INT);

			// Hidden id number for sending the data back.
			$mform->addElement('hidden', 'id');
			$mform->setType('id', PARAM_INT);		
			
			// Add standard buttons, common to all modules.
			$mform->addElement('html', '<br><h3>  </h3>');
			$this->add_action_buttons($cancel = true, $submitlabel='Delete Course');	
			$mform->addElement('html', '</body></html>');
		}
		else {
			$mform->addElement('html', '<html><header></header><body><link rel="stylesheet" type="text/css" href="styles-courses_time_value_form.css">');
			$mform->addElement('html', '<h1>' . get_config('mod_istudy', 'schoolname') . '</h1><br>');
			$mform->addElement('html', '<h2>There are no courses to DELETE</h2><br>');

			// Hidden count of the total number of courses.
			$mform->addElement('hidden', 'count', 0);
			$mform->setType('count', PARAM_INT);

			// Hidden id number for sending the data back.
			$mform->addElement('hidden', 'id');
			$mform->setType('id', PARAM_INT);

			$this->add_action_buttons($cancel = true, $submitlabel='Return to Main Menu');
		}
	}
} 