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
class mod_istudy_teachers_add_new_form extends moodleform 
{

    public function definition() 
	{
        $mform    = $this->_form;

		// Initialize list of courses
		$teacher_list = [];
        $teacher_list = mod_istudy\teacher_info::get_advisors_all_info();
		$number_of_teachers = $teacher_list[0];
		$school_name =get_config('mod_istudy', 'schoolname');

		$mform->addElement('html', '<link rel="stylesheet" type="text/css" href="styles-assign_teachers_to_courses_form.css">');
		
		// Add the title at the top of the page.
		$mform->addElement('html', '<h1>' . $school_name . '</h1><h2>Add / Modify Teacher</h2><br>');
		$mform->addElement('html', '<table><tr><td colspan="6"><h3>Add New Teacher / Modify Teacher</h3></td></tr><br>');

		// The table for adding a new teacher.
		$mform->addElement('html', '<tr><th width="5%">#</th><th width="30%">email</th><th width="20%">Last Name</th><th width="20%">First Name</th><th width="13%">Room #</th><th width="12%">Phone Extension</th></tr>');
		// Add a new course at the start of the table.			
		$mform->addElement('html', '<tr><td><center>0</center></td>');		
		$mform->addElement('html', '<td>');			
			$mform->addElement('textarea', 'email_new', '', 'wrap="virtual" rows="1" cols="60"');
			$mform->setDefault('email_new', '');
			$mform->setType('email_new', PARAM_TEXT);							
		$mform->addElement('html', '</td>');
		$mform->addElement('html', '<td>');			
			$mform->addElement('textarea', 'lastname_new', '', 'wrap="virtual" rows="1" cols="25"');
			$mform->setDefault('lastname_new', '');
			$mform->setType('lastname_new', PARAM_TEXT);							
		$mform->addElement('html', '</td>');
		$mform->addElement('html', '<td>');			
			$mform->addElement('textarea', 'firstname_new', '', 'wrap="virtual" rows="1" cols="25"');
			$mform->setDefault('firstname_new', '');
			$mform->setType('firstname_new', PARAM_TEXT);							
		$mform->addElement('html', '</td>');
		$mform->addElement('html', '<td>');			
			$mform->addElement('textarea', 'roomnumber_new', '', 'wrap="virtual" rows="1" cols="25"');
			$mform->setDefault('roomnumber_new', '');
			$mform->setType('roomnumber_new', PARAM_TEXT);							
		$mform->addElement('html', '</td>');
		$mform->addElement('html', '<td>');			
			$mform->addElement('textarea', 'extensionnumber_new', '', 'wrap="virtual" rows="1" cols="25"');
			$mform->setDefault('extensionnumber_new', '');
			$mform->setType('extensionnumber_new', PARAM_TEXT);							
		$mform->addElement('html', '</td></tr>');

		for ($i = 1; $i <= $number_of_teachers; $i++) {
			$mform->addElement('html', '<tr><td><center>' . $i . '</center><td><center>' . $teacher_list[$i]->email . '</center></td>');
			$mform->addElement('html', '<td>');		
				$lastname =  'lastname' . $i;
				$mform->addElement('textarea', $lastname, '', 'wrap="virtual" rows="1" cols="25"');
				$mform->setDefault($lastname, $teacher_list[$i]->lastname);
				$mform->setType($lastname, PARAM_TEXT);							
			$mform->addElement('html', '</td>');
			$mform->addElement('html', '<td>');		
				$firstname =  'firstname' . $i;	
				$mform->addElement('textarea', $firstname, '', 'wrap="virtual" rows="1" cols="25"');
				$mform->setDefault($firstname, $teacher_list[$i]->firstname);
				$mform->setType($firstname, PARAM_TEXT);							
			$mform->addElement('html', '</td>');
			$mform->addElement('html', '<td>');	
				$roomnumber =  'roomnumber' . $i;		
				$mform->addElement('textarea', $roomnumber, '', 'wrap="virtual" rows="1" cols="25"');
				$mform->setDefault($roomnumber, $teacher_list[$i]->room);
				$mform->setType($roomnumber, PARAM_TEXT);							
			$mform->addElement('html', '</td>');
			$mform->addElement('html', '<td>');	
				$extensionnumber =  'extension' . $i;		
				$mform->addElement('textarea', $extensionnumber, '', 'wrap="virtual" rows="1" cols="25"');
				$mform->setDefault($extensionnumber, $teacher_list[$i]->extension);
				$mform->setType($extensionnumber, PARAM_TEXT);							
			$mform->addElement('html', '</td></tr>');
			// Hidden number for sending the database table id.	
			$teachermoodleid = 'id' . $i;
			$mform->addElement('hidden', $teachermoodleid, $teacher_list[$i]->id);
			$mform->setType($teachermoodleid, PARAM_INT);
			// Hidden number for sending the teacher email.	
			$teacheremail = 'email' . $i;
			$mform->addElement('hidden', $teacheremail, $teacher_list[$i]->email);
			$mform->setType($teacheremail, PARAM_TEXT);
			// Hidden id number for sending the teacherid.	
			$teacherid = 'teacherid' . $i;
			$mform->addElement('hidden', $teacherid, $teacher_list[$i]->teacherid);
			$mform->setType($teacherid, PARAM_INT);
			// Hidden id number for sending the teacherid.	
			$databaseid = 'databaseid' . $i;
			$mform->addElement('hidden', $databaseid, $teacher_list[$i]->id);
			$mform->setType($databaseid, PARAM_INT);
		}
		$mform->addElement('html', '</table>');	
		
		// Hidden id number for sending the count back.	
		$mform->addElement('hidden', 'teacher_count', $number_of_teachers);
		$mform->setType('teacher_count', PARAM_INT);

		// Hidden id number for sending the data back.
		$mform->addElement('hidden', 'id');
		$mform->setType('id', PARAM_INT);		
		
		// Add standard buttons, common to all modules.
		$mform->addElement('html', '<br><h3>  </h3>');
		$this->add_action_buttons($cancel = true, $submitlabel='Save teacher');	
	}
} 