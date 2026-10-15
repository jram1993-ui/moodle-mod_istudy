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

class mod_istudy_search_criteria_form extends \moodleform
{
    public function definition()
	{
		$mform = $this->_form;
		$mform->addElement('html', '<html><head><link rel="stylesheet" type="text/css" href="styles-search_criteria_form.css"></head><body>');
		$mform->addElement('html', '<h3>iStudy Setup - Manage Courses and Teachers</h3><br>');
		$mform->addElement('html', '<table><tr><th colspan="6"><h4>Select the Setup Option</h4></th></tr>');
		$mform->addElement('html', '<tr><td rowpan="2" class="mycell2">Courses Dropdown Menus</td><td class="mycell">');
		$mform->addElement('radio', 'whichform', '','Fall Courses', 1);
		$mform->addElement('html', '</td><td class="mycell">');
		$mform->addElement('radio', 'whichform', '','Spring Courses', 2);
		$mform->addElement('html', '</td><td class="mycell">');
		$mform->addElement('radio', 'whichform', '','Summer Courses', 3);
        $mform->addElement('html', '</td></tr>');

        $mform->addElement('html', '<tr><td rowpan="2" class="mycell2">Master Course List</td><td class="mycell">');
		$mform->addElement('radio', 'whichform', '','Assign Teachers to courses', 4);
		$mform->addElement('html', '</td><td class="mycell">');
		$mform->addElement('radio', 'whichform', '','Add / Modify Course Name or Hours', 5);
		$mform->addElement('html', '</td><td class="mycell">');
		$mform->addElement('radio', 'whichform', '','Delete Course', 6);

        $mform->addElement('html', '<tr><td rowpan="2" class="mycell2">Aeries & Teachers</td><td class="mycell">');
		$mform->addElement('radio', 'whichform', '','Aeries Section Numbers', 7);
		$mform->addElement('html', '</td><td class="mycell">');
		$mform->addElement('radio', 'whichform', '','Add New Teacher', 8);
		$mform->addElement('html', '</td><td class="mycell">');
		$mform->addElement('radio', 'whichform', '','Delete from Teacher List', 9);

		$mform->addElement('html', '</td></tr></table>');

		$mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
		$mform->addElement('html', '<br><br>');

		// Add standard buttons, common to all modules.
		$this->add_action_buttons(true, 'Submit');
		$mform->addElement('html', '</body></html>');
    }
}