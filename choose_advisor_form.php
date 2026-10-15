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
 * The main istudy configuration form
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
class mod_istudy_choose_advisor_form extends \moodleform
{
	/**
     * Form definition.
     */
   	public function definition()
	{
        global $USER;

		$this_term[1] = 'Fall';
		$this_term[2] = 'Spring';
		$this_term[3] = 'Summer';
		$myterm = $USER->semesterctv;
		// $this_advisor_name =  mod_istudy\teacher_info::get_whichadvisor($this_advisorid);
		$mform = $this->_form;
		// if ($this_advisor_name == 'Administrator') {
			$mform->addElement('html', '<link rel="stylesheet" type="text/css" href="styles-choose_advisor_form.css">');
			$mform->addElement('html', '<head><title>Choose Teacher</title></head><body>');
			$mform->addElement('html', '<h1>' . $this_term[$myterm] . ' Term</h1>');
			$mform->addElement('html', '<h3>Courses for Dropdown Menus</h3><br>');
			$mform->addElement('html', '<table><tr><th colspan="2"><h4>Choose the Teacher</h4></th></tr>');

			// Choose the Advisory Teacher
			$advisors = mod_istudy\teacher_info::get_advisors_with_id();
			$mform->addElement('html', '<tr><col width="50%"><col width="50%"><td>Choose the teacher for semester setup</td><td colspan="13">');
			$select = $mform->addElement('select', 'advisorid', '', $advisors);
			// Set default to None
			$select->setSelected(0);
			$mform->addElement('html', '</td></tr></table>');

			$mform->addElement('hidden', 'id');
			$mform->setType('id', PARAM_INT);
			$mform->addElement('html', '<h3>  </h3><br>');

			// Add standard buttons, common to all modules.
			$this->add_action_buttons(true, 'Submit');

			$mform->addElement('html', '</body>');
    }
}