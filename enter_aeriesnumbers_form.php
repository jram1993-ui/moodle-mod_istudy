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
 * @package    mod_daily_attend
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
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_istudy_enter_aeriesnumbers_form extends moodleform 
{

    public function definition() 
	{
        $mform    = $this->_form;     
		$this_advisor = new stdClass();
		$this_advisor = mod_istudy\teacher_info::get_advisors_all_info();
		$number_of_teachers = $this_advisor[0];
		if ($number_of_teachers == 0) {
			$mform->addElement('html', '<h1>There are no teachers in the system.</h1><br />');
			$mform->addElement('html', '<h3>Please return to the main menu an choose the option to adding teachers.</h3>');

			// Hidden id number for sending the count back.	
			$mform->addElement('hidden', 'teacher_count', $number_of_teachers);
			$mform->setType('teacher_count', PARAM_INT);
			
			// Hidden id number for sending the data back.
			$mform->addElement('hidden', 'id');
			$mform->setType('id', PARAM_INT);	

			// Add standard buttons, common to all modules.
			$mform->addElement('html', '<br><h3>  </h3>');
			$this->add_action_buttons($cancel = true, $submitlabel='Return to Main Menu');
		}
		else {
			$week_day[1] = 'Monday';
			$week_day[2] = 'Tuesday';
			$week_day[3] = 'Wednesday';
			$week_day[4] = 'Thursday';
			$week_day[5] = 'Friday';
			$count = 0;
		
			$mform->addElement('html', '<link rel="stylesheet" type="text/css" href="styles-enter_coursenumbers_form.css">');			
			$mform->addElement('html', '<h1>' . get_config('mod_istudy', 'schoolname') . '</h1>');
			$mform->addElement('html', '<h2>Aeries - Section Numbers</h2><br>');
			$mform->addElement('html', '<table><tr><th width="2%" class="cellborderall">#</th>' . 
				'<th width="13%" class="cellborderall">Teacher Name</th><th width="5%" class="cellborderall">Weekday</th>'. 
				'<th width="10%" class="cellborderall">Session #1</th><th width="10%" class="cellborderall">Session #2</th>'.
				'<th width="10%" class="cellborderall">Session #3</th><th width="10%" class="cellborderall">Session #4</th>' .
				'<th width="10%" class="cellborderall">Session #5</th><th width="10%" class="cellborderall">Session #6</th>'.
				'<th width="10%" class="cellborderall">Session #7</th><th width="10%" class="cellborderall">Session #8</th></tr>');		
			$advisor_count = $this_advisor[0];
			$section_number = new stdClass();
			for ($i = 1; $i <= $advisor_count; $i++) {
				// Print the column labels after the 2nd teacher and on.
				if ($i > 1) {
					$mform->addElement('html', '<tr><td class="noborder_bottom"></td><td class="noborder_bottomleft"></td>' . 
						'<th class="cellborderall">Weekday</th>'. 
						'<th class="cellborderall">Session #1</th><th class="cellborderall">Session #2</th>'.
						'<th class="cellborderall">Session #3</th><th class="cellborderall">Session #4</th>' .
						'<th class="cellborderall">Session #5</th><th class="cellborderall">Session #6</th>'.
						'<th class="cellborderall">Session #7</th><th class="cellborderall">Session #8</th></tr>');	
				}
					
				$mform->addElement('html',  '<tr><td rowspan = "5"  class="mycell"><b><center>' . $i .'</td><td rowspan = "5"  class="mycell"></center></b><b>' . $this_advisor[$i]->lastname . ', ' . $this_advisor[$i]->firstname . '</b></td>');
				$advisorid =  $this_advisor[$i]->teacherid;
				for ($d = 1; $d <= 5; $d++) {	
					// If it's not Monday (not the first row), start a new row
					if ($d <> 1) {
						$mform->addElement('html', '<tr>');
					}					
					$mform->addElement('html', '<td>' . $week_day[$d] . '</td>');
					for ($j = 1; $j <= 8; $j++) {						
						$section_info =  mod_istudy\utils::get_section_number($advisorid, $d, $j);
						$count++;
						$record_id = 'record_id' . $count;
						$teacher_id = 'teacher_id' . $count;
						$day_of_week = 'day_of_week' . $count;					
						$sessionid = 'session' . $count;
						$section_number = 'section_number' . $count;
						
						// This database record id
						$mform->addElement('hidden', $record_id, $section_info->id);
						$mform->setType($record_id, PARAM_INT);
																		
						// The teacher id
						$mform->addElement('hidden', $teacher_id, $section_info->teacher_id);
						$mform->setType($teacher_id, PARAM_INT);
						
						// The day of the week - Monday-Friday (1-5)
						$mform->addElement('hidden', $day_of_week, $section_info->day_of_week);
						$mform->setType($day_of_week, PARAM_INT);

						// The session number (1-8)
						$mform->addElement('hidden', $sessionid, $section_info->session);
						$mform->setType($sessionid, PARAM_INT);					

						// The section number
						$mform->addElement('html', '<td>');			
							$mform->addElement('textarea', $section_number, '', 'wrap="virtual" rows="1" cols="5"');
							$section_default = $section_info->section_number;
							$mform->setDefault($section_number, $section_default);
							$mform->setType($section_number, PARAM_TEXT);							
						$mform->addElement('html', '</td>');
					}		
					$mform->addElement('html', '</tr>');		
				}
			}
			$mform->addElement('html', '</table>');		
			
			// Hidden id number for sending the count back.	
			$mform->addElement('hidden', 'section_count', $count);
			$mform->setType('section_count', PARAM_INT);

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