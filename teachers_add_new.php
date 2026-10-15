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
require_once(__DIR__.'/teachers_add_new_form.php');

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
$mform = new mod_istudy_teachers_add_new_form(null, $formparams);
$mform->set_data($istudyid);

if ($mform->is_cancelled()) {
	// Cancelled forms redirect to the course main page.
	$url = new moodle_url('/course/view.php', array('id' => $course->id));
	redirect($url);
	return;	
} 
else if ($data = $mform->get_data()) {
	$teacher_count = $data->teacher_count;
	$duplicate_found = false;
	$entry_erased = false;
	// Save any changes made to the current list of teachers.
	if ($teacher_count > 0) {		
		// Get the list of teachers from the form.
		for ($i = 1; $i <= $teacher_count; $i++) {
			$this_teacher[$i] = new stdClass();
			$this_teacher[$i]->id = $data->{'id' . $i};
			$this_teacher[$i]->teacherid = $data->{'teacherid' . $i};
			$this_teacher[$i]->email = $data->{'email' . $i};
			$this_teacher[$i]->lastname = mod_istudy\utils::remove_extra_symbols(mod_istudy\utils::custom_trim($data->{'lastname' . $i}));
			$this_teacher[$i]->firstname = mod_istudy\utils::remove_extra_symbols(mod_istudy\utils::custom_trim($data->{'firstname' . $i}));
			$this_teacher[$i]->room = mod_istudy\utils::remove_extra_symbols(mod_istudy\utils::custom_trim( $data->{'roomnumber' . $i}));
			$this_teacher[$i]->extension = mod_istudy\utils::remove_extra_symbols(mod_istudy\utils::custom_trim($data->{'extension' . $i}));
			// Check any accidentally erased entries.
			if (($this_teacher[$i]->email == '') OR ($this_teacher[$i]->lastname == '') OR ($this_teacher[$i]->firstname == '') OR ($this_teacher[$i]->room == '') OR ($this_teacher[$i]->extension == '')) {
				$entry_erased = true;
			}
		}
		if (($entry_erased == false) AND ($teacher_count > 1)) {
			// Check the original list of teachers for duplicates - email, lastname, and firstname.		
			for ($i = 1; $i <= ($teacher_count - 1); $i++) {
				for ($j = ($i + 1); $j <= $teacher_count; $j++) {
					if ((strtolower($this_teacher[$i]->email) == strtolower($this_teacher[$j]->email)) OR 
							(strtolower($this_teacher[$i]->lastname) == strtolower($this_teacher[$j]->lastname)) AND 
							(strtolower($this_teacher[$i]->firstname) == strtolower($this_teacher[$j]->firstname))) {
						$duplicate_found = true;
						$USER->just_savedctv = 'DuplicateFound';
					}
				}					
			}
		}
		// If no duplicates found and no previous entries erased, save the list of teachers.
		if (($duplicate_found == false) AND ($entry_erased == false)) {
			$USER->just_savedctv = 'Yes';
			for ($i = 1; $i <= $teacher_count; $i++) {
				try {
					$transaction = $DB->start_delegated_transaction();
					$DB->update_record('istudyteachers', $this_teacher[$i]);
					// Assuming that the update worked, we get to the following line.
					$transaction->allow_commit();
					mod_istudy\utils::update_studentprofile_teachers();
				} catch(Exception $e) {
					$transaction->rollback($e);
					$course_with_error = 'The entry for section <b> ' . $submission->section . ' (' . $submission->subject . ')</b> was not saved. An error was found in the details.';
					echo $OUTPUT->notification($course_with_error);
				}
			}
		}
	}
	
	// Check for a new teacher entered.
	$new_entry_found = false;	
	$teacher_new = new stdClass();
	$teacher_new->email = mod_istudy\utils::remove_extra_symbols(mod_istudy\utils::custom_trim($data->email_new));
	$teacher_new->teacherid = mod_istudy\teacher_info::get_teacherid($teacher_new->email);
	if ($teacher_new->teacherid <> -1) {
		$teacher_new->lastname = mod_istudy\utils::remove_extra_symbols(mod_istudy\utils::custom_trim($data->lastname_new));
		$teacher_new->firstname = mod_istudy\utils::remove_extra_symbols(mod_istudy\utils::custom_trim($data->firstname_new));
		$teacher_new->room = mod_istudy\utils::remove_extra_symbols(mod_istudy\utils::custom_trim( $data->roomnumber_new));
		$teacher_new->extension = mod_istudy\utils::remove_extra_symbols(mod_istudy\utils::custom_trim($data->extensionnumber_new));
		if (($teacher_new->email <> '') AND ($teacher_new->lastname <> '') AND ($teacher_new->firstname <> '') AND ($teacher_new->room <> '') AND ($teacher_new->extension <> '')) {
			$new_entry_found = true;
		}
		// Check for duplicates.
		if ($new_entry_found == true) {
			// Compare the new teacher to the original list of teachers and check for duplicates.
			for ($i = 1; $i <= $teacher_count; $i++) {
				if ((strtolower($this_teacher[$i]->email) == strtolower($teacher_new->email)) OR 
						(strtolower($this_teacher[$i]->lastname) == strtolower($teacher_new->lastname)) AND 
						(strtolower($this_teacher[$i]->firstname) == strtolower($teacher_new->firstname))) {
					$duplicate_found = true;
					$USER->just_savedctv = 'DuplicateFound';
				}			
			}
		}
		if (($new_entry_found == true) AND ($duplicate_found == false)) {
			try {
				$transaction = $DB->start_delegated_transaction();
				$DB->insert_record('istudyteachers', $teacher_new);
				// Assuming that the update worked, we get to the following line.
				$transaction->allow_commit();
				mod_istudy\utils::update_studentprofile_teachers();				
			} 
			catch(Exception $e) {
				$transaction->rollback($e);
				$course_with_error = 'The entry for <b> ' . $teacher_new->firstname . ' ' . $teacher_new->lastname . ')</b> was not saved. An error was found in the details.';
				echo $OUTPUT->notification($course_with_error);
			}
			// mod_istudy\utils::update_teacher_status($teacher_new->teacherid, "Teacher");
			// Change the user status to Teacher.
			$field_id = mod_istudy\utils::get_fieldid('whichteacher');			
			$submission = new stdClass();
			$submission->fieldid = $field_id;
			$submission->userid = $teacher_new->teacherid;
			$submission->data = "Teacher";
			try {
				$transaction = $DB->start_delegated_transaction();
				if ($DB->record_exists('user_info_data', ['userid'=>$teacher_new->teacherid, 'fieldid'=>$field_id])) {
						$whichteacher_id = $DB->get_record('user_info_data', ['userid'=>$teacher_new->teacherid, 'fieldid'=>$field_id], '*');
						$submission->id = $whichteacher_id->id;
						$DB->update_record('user_info_data', $submission);		
				}
				else {
						$DB->insert_record('user_info_data', $submission);
				}				
				$transaction->allow_commit();
			}
			catch(Exception $e) {
				$transaction->rollback($e);
				$course_with_error = 'The entry for <b> ' . $teacher_new->firstname . ' ' . $teacher_new->lastname . ')</b> was not saved. An error was found in the details.';
				echo $OUTPUT->notification($course_with_error);
			}
		}		
	}
	// If a teacher email was entered (not null), the email entered was not valid.
	else if ($teacher_new->email <> '') {
		$USER->just_savedctv = 'EmailNotFound';
	}
	// Reload the page.
	$add_new_teacher_url = new moodle_url('/mod/istudy/teachers_add_new.php', array('id' => $cm->id));
	redirect($add_new_teacher_url, null, 0);	
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
else if ($USER->just_savedctv == 'DuplicateFound') {
	echo $OUTPUT->notification('There is already a teacher with that specific lastname-firstname or email.');	
	$USER->just_savedctv == 'No';
}
else if ($USER->just_savedctv == 'EmailNotFound') {
	echo $OUTPUT->notification('The email for the new teacher was not found.');	
	$USER->just_savedctv == 'No';
}
$mform->display();
echo $OUTPUT->footer();