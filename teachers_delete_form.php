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
class mod_istudy_teachers_delete_form extends moodleform 
{

    public function definition() 
	{
        $mform    = $this->_form;
		
		$this_teacher = mod_istudy\teacher_info::get_advisors_all_info();
		$count = $this_teacher[0];
		if ($count > 0) {
			$mform->addElement('html', '<html><header></header><body><link rel="stylesheet" type="text/css" href="styles-courses_time_value_form.css">');
			
			// Add the title at the top of the page.
			$mform->addElement('html', '<h1>' . get_config('mod_istudy', 'schoolname') . '</h1>');
			$mform->addElement('html', '<h2>Delete Teacher</h2><br>');

			// The table for adding a new teacher.
			$mform->addElement('html', '<table><tr><th colspan="6"><h3><center>Choose the Teacher to Delete</center></h3></th></tr>');
			$mform->addElement('html', '<tr><th width="6%">#</th><th width="34%">email</th><th width="20%">Last Name</th><th width="20%">First Name</th><th width="10%">Room #</th><th width="10%">Phone Extension</th></tr>');

			for ($i = 1; $i <= $count; $i++) {
				$mform->addElement('html', '<tr><td class="mycell6">');	
				$mform->addElement('radio', 'teacher_to_delete', '', $i, $this_teacher[$i]->id, ['class' => 'radiobuttons-delete']);
				$mform->addElement('html', '</td><td>' . $this_teacher[$i]->email . '</td><td>' . $this_teacher[$i]->lastname . 
					'</td><td>' . $this_teacher[$i]->firstname . '</td><td>' . $this_teacher[$i]->room . '</td><td>' . $this_teacher[$i]->extension . '</td></tr>');
			}
			$mform->setDefault('teacher_to_delete', $this_teacher[1]->id);
			$mform->setType('teacher_to_delete', PARAM_INT);
		
			$mform->addElement('html', '</table><br>');

			// Hidden count of the total number of courses.
			$mform->addElement('hidden', 'count', $count);
			$mform->setType('count', PARAM_INT);

			// Hidden id number for sending the data back.
			$mform->addElement('hidden', 'id');
			$mform->setType('id', PARAM_INT);		
			
			// Add standard buttons, common to all modules.
			$mform->addElement('html', '<br><h3>  </h3>');
			$this->add_action_buttons($cancel = true, $submitlabel='Delete Teacher');	
			$mform->addElement('html', '</body></html>');
		}
		else {
			$mform->addElement('html', '<html><header></header><body><link rel="stylesheet" type="text/css" href="styles-courses_time_value_form.css">');
			$mform->addElement('html', '<h1>' . get_config('mod_istudy', 'schoolname') . '</h1><br>');
			$mform->addElement('html', '<h2>There are no teachers to DELETE</h2><br>');

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