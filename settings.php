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
 * istudy module admin settings and defaults
 *
 * @package    mod
 * @subpackage istudy
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
require_once($CFG->dirroot.'/mod/istudy/lib.php');

if ($ADMIN->fulltree) {
	// Number of sessions for this iStudy app
	$number_of_sessions = array(
		4=>4,
		5=>5,
		6=>6,
		7=>7,
		8=>8,
	);

    // Paging options.
     $options = array(
         0 => 0,
         1 => 1,
         2 => 2,
         3 => 3,
         4 => 4,
         5 => 5,
    );
	$schoolterm = array(
         'Fall' => 'Fall',
         'Spring' => 'Spring',
         'Summer' => 'Summer',
    );
	$currentyear = date('Y');
	for($i = ($currentyear-4); $i < ($currentyear+4); ++$i) {
		$schoolyear[$i] = $i; }

	// School Information
	$settings->add(new admin_setting_heading('mod_istudy/schoolinfoheading',
        get_string('schoolinfoheading', 'istudy'), get_string('schoolinfoheading_details', 'istudy')));
	$settings->add(new admin_setting_configselect('mod_istudy/number_of_sessions',
        get_string('number_of_sessions', 'istudy'), get_string('number_of_sessions_details', 'istudy'), 'Fall', $number_of_sessions));
	$settings->add(new admin_setting_configselect('mod_istudy/schoolterm',
        get_string('schoolterm', 'istudy'), get_string('schoolterm_details', 'istudy'), 'Fall', $schoolterm));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/spring_start_date',
        get_string('spring_start_date', 'istudy'), get_string('spring_start_date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/schoolname',
        get_string('schoolname', 'istudy'), get_string('schoolname_details', 'istudy'), get_string('schoolname_default', 'istudy'), PARAM_TEXT, 50, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/schoolname',
        get_string('schoolname', 'istudy'), get_string('schoolname_details', 'istudy'), get_string('schoolname_default', 'istudy'), PARAM_TEXT, 50, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/schooladdress',
        get_string('schooladdress', 'istudy'), get_string('schooladdress_details', 'istudy'), get_string('schooladdress_default', 'istudy'), PARAM_TEXT, 50, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/schoolcity',
        get_string('schoolcity', 'istudy'), get_string('schoolcity_details', 'istudy'), get_string('schoolcity_default', 'istudy'), PARAM_TEXT, 20, 20));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/schoolstate',
        get_string('schoolstate', 'istudy'), get_string('schoolstate_details', 'istudy'), get_string('schoolstate_default', 'istudy'), PARAM_TEXT, 2, 2));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/schoolzip',
        get_string('schoolzip', 'istudy'), get_string('schoolzip_details', 'istudy'), get_string('schoolzip_default', 'istudy'), PARAM_TEXT, 12, 12));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/schoolphone',
        get_string('schoolphone', 'istudy'), get_string('schoolphone_details', 'istudy'), get_string('schoolphone_default', 'istudy'), PARAM_TEXT, 15, 15));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/schoolfax',
        get_string('schoolfax', 'istudy'), get_string('schoolfax_details', 'istudy'), get_string('schoolfax_default', 'istudy'), PARAM_TEXT, 15, 15));

	// Monthly attendance points
	$settings->add(new admin_setting_heading('mod_istudy/monthpointsheading',
        get_string('monthpointsheading', 'istudy'), get_string('monthpointsheading_details', 'istudy')));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month01week01date',
        get_string('month01week01date', 'istudy'), get_string('month01week01date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month01week01',
        get_string('month01week01', 'istudy'), get_string('month01week01_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month01week02date',
        get_string('month01week02date', 'istudy'), get_string('month01week02date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month01week02',
        get_string('month01week02', 'istudy'), get_string('month01week02_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month01week03date',
        get_string('month01week03date', 'istudy'), get_string('month01week03date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month01week03',
        get_string('month01week03', 'istudy'), get_string('month01week03_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month01week04date',
        get_string('month01week04date', 'istudy'), get_string('month01week04date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month01week04',
        get_string('month01week04', 'istudy'), get_string('month01week04_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month02week01date',
        get_string('month02week01date', 'istudy'), get_string('month02week01date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month02week01',
        get_string('month02week01', 'istudy'), get_string('month02week01_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month02week02date',
        get_string('month02week02date', 'istudy'), get_string('month02week02date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month02week02',
        get_string('month02week02', 'istudy'), get_string('month02week02_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month02week03date',
        get_string('month02week03date', 'istudy'), get_string('month02week03date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month02week03',
        get_string('month02week03', 'istudy'), get_string('month02week03_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month02week04date',
        get_string('month02week04date', 'istudy'), get_string('month02week04date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month02week04',
        get_string('month02week04', 'istudy'), get_string('month02week04_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month03week01date',
        get_string('month03week01date', 'istudy'), get_string('month03week01date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month03week01',
        get_string('month03week01', 'istudy'), get_string('month03week01_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month03week02date',
        get_string('month03week02date', 'istudy'), get_string('month03week02date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month03week02',
        get_string('month03week02', 'istudy'), get_string('month03week02_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month03week03date',
        get_string('month03week03date', 'istudy'), get_string('month03week03date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month03week03',
        get_string('month03week03', 'istudy'), get_string('month03week03_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month03week04date',
        get_string('month03week04date', 'istudy'), get_string('month03week04date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month03week04',
        get_string('month03week04', 'istudy'), get_string('month03week04_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month04week01date',
        get_string('month04week01date', 'istudy'), get_string('month04week01date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month04week01',
        get_string('month04week01', 'istudy'), get_string('month04week01_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month04week02date',
        get_string('month04week02date', 'istudy'), get_string('month04week02date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month04week02',
        get_string('month04week02', 'istudy'), get_string('month04week02_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month04week03date',
        get_string('month04week03date', 'istudy'), get_string('month04week03date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month04week03',
        get_string('month04week03', 'istudy'), get_string('month04week03_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month04week04date',
        get_string('month04week04date', 'istudy'), get_string('month04week04date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month04week04',
        get_string('month04week04', 'istudy'), get_string('month04week04_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month05week01date',
        get_string('month05week01date', 'istudy'), get_string('month05week01date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month05week01',
        get_string('month05week01', 'istudy'), get_string('month05week01_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month05week02date',
        get_string('month05week02date', 'istudy'), get_string('month05week02date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month05week02',
        get_string('month05week02', 'istudy'), get_string('month05week02_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month05week03date',
        get_string('month05week03date', 'istudy'), get_string('month05week03date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month05week03',
        get_string('month05week03', 'istudy'), get_string('month05week03_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month05week04date',
        get_string('month05week04date', 'istudy'), get_string('month05week04date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month05week04',
        get_string('month05week04', 'istudy'), get_string('month05week04_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month06week01date',
        get_string('month06week01date', 'istudy'), get_string('month06week01date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month06week01',
        get_string('month06week01', 'istudy'), get_string('month06week01_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month06week02date',
        get_string('month06week02date', 'istudy'), get_string('month06week02date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month06week02',
        get_string('month06week02', 'istudy'), get_string('month06week02_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month06week03date',
        get_string('month06week03date', 'istudy'), get_string('month06week03date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month06week03',
        get_string('month06week03', 'istudy'), get_string('month06week03_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month06week04date',
        get_string('month06week04date', 'istudy'), get_string('month06week04date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month06week04',
        get_string('month06week04', 'istudy'), get_string('month06week04_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month07week01date',
        get_string('month07week01date', 'istudy'), get_string('month07week01date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month07week01',
        get_string('month07week01', 'istudy'), get_string('month07week01_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month07week02date',
        get_string('month07week02date', 'istudy'), get_string('month07week02date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month07week02',
        get_string('month07week02', 'istudy'), get_string('month07week02_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month07week03date',
        get_string('month07week03date', 'istudy'), get_string('month07week03date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month07week03',
        get_string('month07week03', 'istudy'), get_string('month07week03_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month07week04date',
        get_string('month07week04date', 'istudy'), get_string('month07week04date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month07week04',
        get_string('month07week04', 'istudy'), get_string('month07week04_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month08week01date',
        get_string('month08week01date', 'istudy'), get_string('month08week01date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month08week01',
        get_string('month08week01', 'istudy'), get_string('month08week01_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month08week02date',
        get_string('month08week02date', 'istudy'), get_string('month08week02date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month08week02',
        get_string('month08week02', 'istudy'), get_string('month08week02_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month08week03date',
        get_string('month08week03date', 'istudy'), get_string('month08week03date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month08week03',
        get_string('month08week03', 'istudy'), get_string('month08week03_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month08week04date',
        get_string('month08week04date', 'istudy'), get_string('month08week04date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month08week04',
        get_string('month08week04', 'istudy'), get_string('month08week04_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month09week01date',
        get_string('month09week01date', 'istudy'), get_string('month09week01date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month09week01',
        get_string('month09week01', 'istudy'), get_string('month09week01_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month09week02date',
        get_string('month09week02date', 'istudy'), get_string('month09week02date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month09week02',
        get_string('month09week02', 'istudy'), get_string('month09week02_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month09week03date',
        get_string('month09week03date', 'istudy'), get_string('month09week03date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month09week03',
        get_string('month09week03', 'istudy'), get_string('month09week03_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month09week04date',
        get_string('month09week04date', 'istudy'), get_string('month09week04date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month09week04',
        get_string('month09week04', 'istudy'), get_string('month09week04_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month10week01date',
        get_string('month10week01date', 'istudy'), get_string('month10week01date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month10week01',
        get_string('month10week01', 'istudy'), get_string('month10week01_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month10week02date',
        get_string('month10week02date', 'istudy'), get_string('month10week02date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month10week02',
        get_string('month10week02', 'istudy'), get_string('month10week02_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month10week03date',
        get_string('month10week03date', 'istudy'), get_string('month10week03date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month10week03',
        get_string('month10week03', 'istudy'), get_string('month10week03_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month10week04date',
        get_string('month10week04date', 'istudy'), get_string('month10week04date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month10week04',
        get_string('month10week04', 'istudy'), get_string('month10week04_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month11week01date',
        get_string('month11week01date', 'istudy'), get_string('month11week01date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month11week01',
        get_string('month11week01', 'istudy'), get_string('month11week01_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month11week02date',
        get_string('month11week02date', 'istudy'), get_string('month11week02date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month11week02',
        get_string('month11week02', 'istudy'), get_string('month11week02_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month11week03date',
        get_string('month11week03date', 'istudy'), get_string('month11week03date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month11week03',
        get_string('month11week03', 'istudy'), get_string('month11week03_details', 'istudy'), 0, $options));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/month11week04date',
        get_string('month11week04date', 'istudy'), get_string('month11week04date_details', 'istudy'), 'mm/dd/yy', PARAM_TEXT, 8, 8));
	$settings->add(new admin_setting_configselect('mod_istudy/month11week04',
        get_string('month11week04', 'istudy'), get_string('month11week04_details', 'istudy'), 0, $options));


	// Fall & Spring - daily session start and end times
	$settings->add(new admin_setting_heading('mod_istudy/sessiontimesheading',
        get_string('sessiontimesheading', 'istudy'), get_string('sessiontimesheading_details', 'istudy')));

	$no_or_yes = array(
         'No' => 'No',
         'Yes' => 'Yes',
    );
	$settings->add(new admin_setting_configselect('mod_istudy/dailyschedule',
        get_string('dailyschedule', 'istudy'), get_string('dailyschedule_details', 'istudy'), 'No', $no_or_yes));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/mondaysession1',
        get_string('mondaysession1', 'istudy'), get_string('session_details', 'istudy'), '8:47 a.m. - 9:57 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/mondaysession2',
        get_string('mondaysession2', 'istudy'), get_string('session_details', 'istudy'), '9:59 a.m. - 10:59 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/mondaysession3',
        get_string('mondaysession3', 'istudy'), get_string('session_details', 'istudy'), '11:01 a.m. - 12:01 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/mondaysession4',
        get_string('mondaysession4', 'istudy'), get_string('session_details', 'istudy'), 'Lunch', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/mondaysession5',
        get_string('mondaysession5', 'istudy'), get_string('session_details', 'istudy'), 'Early out', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/mondaysession6',
        get_string('mondaysession6', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/mondaysession7',
        get_string('mondaysession7', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/mondaysession8',
        get_string('mondaysession8', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/tuesdaysession1',
        get_string('tuesdaysession1', 'istudy'), get_string('session_details', 'istudy'), '8:47 a.m. - 9:55 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/tuesdaysession2',
        get_string('tuesdaysession2', 'istudy'), get_string('session_details', 'istudy'), '9:57 a.m. - 11:05 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/tuesdaysession3',
        get_string('tuesdaysession3', 'istudy'), get_string('session_details', 'istudy'), '11:37 a.m. - 12:45 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/tuesdaysession4',
        get_string('tuesdaysession4', 'istudy'), get_string('session_details', 'istudy'), '12:47 p.m. - 1:55 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/tuesdaysession5',
        get_string('tuesdaysession5', 'istudy'), get_string('session_details', 'istudy'), '2:07 p.m. - 3:15 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/tuesdaysession6',
        get_string('tuesdaysession6', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/tuesdaysession7',
        get_string('tuesdaysession7', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/tuesdaysession8',
        get_string('tuesdaysession8', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/wednesdaysession1',
        get_string('wednesdaysession1', 'istudy'), get_string('session_details', 'istudy'), '8:47 a.m. - 9:55 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/wednesdaysession2',
        get_string('wednesdaysession2', 'istudy'), get_string('session_details', 'istudy'), '9:57 a.m. - 11:05 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/wednesdaysession3',
        get_string('wednesdaysession3', 'istudy'), get_string('session_details', 'istudy'), '11:37 a.m. - 12:45 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/wednesdaysession4',
        get_string('wednesdaysession4', 'istudy'), get_string('session_details', 'istudy'), '12:47 p.m. - 1:55 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/wednesdaysession5',
        get_string('wednesdaysession5', 'istudy'), get_string('session_details', 'istudy'), '2:07 p.m. - 3:15 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/wednesdaysession6',
        get_string('wednesdaysession6', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/wednesdaysession7',
        get_string('wednesdaysession7', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/wednesdaysession8',
        get_string('wednesdaysession8', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/thursdaysession1',
        get_string('thursdaysession1', 'istudy'), get_string('session_details', 'istudy'), '8:47 a.m. - 9:55 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/thursdaysession2',
        get_string('thursdaysession2', 'istudy'), get_string('session_details', 'istudy'), '9:57 a.m. - 11:05 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/thursdaysession3',
        get_string('thursdaysession3', 'istudy'), get_string('session_details', 'istudy'), '11:37 a.m. - 12:45 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/thursdaysession4',
        get_string('thursdaysession4', 'istudy'), get_string('session_details', 'istudy'), '12:47 p.m. - 1:55 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/thursdaysession5',
        get_string('thursdaysession5', 'istudy'), get_string('session_details', 'istudy'), '2:07 p.m. - 3:15 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/thursdaysession6',
        get_string('thursdaysession6', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/thursdaysession7',
        get_string('thursdaysession7', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/thursdaysession8',
        get_string('thursdaysession8', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/fridaysession1',
        get_string('fridaysession1', 'istudy'), get_string('session_details', 'istudy'), '8:47 a.m. - 9:55 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/fridaysession2',
        get_string('fridaysession2', 'istudy'), get_string('session_details', 'istudy'), '9:57 a.m. - 11:05 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/fridaysession3',
        get_string('fridaysession3', 'istudy'), get_string('session_details', 'istudy'), '11:37 a.m. - 12:45 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/fridaysession4',
        get_string('fridaysession4', 'istudy'), get_string('session_details', 'istudy'), '12:47 p.m. - 1:55 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/fridaysession5',
        get_string('fridaysession5', 'istudy'), get_string('session_details', 'istudy'), '2:07 p.m. - 3:15 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/fridaysession6',
        get_string('fridaysession6', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/fridaysession7',
        get_string('fridaysession7', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/fridaysession8',
        get_string('fridaysession8', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));

	// Summer - daily session start and end times
	$settings->add(new admin_setting_heading('mod_istudy/summer_sessiontimesheading',
        get_string('summer_sessiontimesheading', 'istudy'), get_string('sessiontimesheading_details', 'istudy')));
	$summer_no_or_yes = array(
         'No' => 'No',
         'Yes' => 'Yes',
    );
	$settings->add(new admin_setting_configselect('mod_istudy/summer_dailyschedule',
        get_string('dailyschedule', 'istudy'), get_string('dailyschedule_details', 'istudy'), 'No', $summer_no_or_yes));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_mondaysession1',
        get_string('summer_mondaysession1', 'istudy'), get_string('session_details', 'istudy'), '9:57 a.m. - 11:05 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_mondaysession2',
        get_string('summer_mondaysession2', 'istudy'), get_string('session_details', 'istudy'), '9:40 a.m. - 11:00 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_mondaysession3',
        get_string('summer_mondaysession3', 'istudy'), get_string('session_details', 'istudy'), '11:37 a.m. - 12:45 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_mondaysession4',
        get_string('summer_mondaysession4', 'istudy'), get_string('session_details', 'istudy'), '12:47 p.m. - 1:55 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_mondaysession5',
        get_string('summer_mondaysession5', 'istudy'), get_string('session_details', 'istudy'), '2:07 p.m. - 3:15 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_mondaysession6',
        get_string('summer_mondaysession6', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_mondaysession7',
        get_string('summer_mondaysession7', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_mondaysession8',
        get_string('summer_mondaysession8', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_tuesdaysession1',
        get_string('summer_tuesdaysession1', 'istudy'), get_string('session_details', 'istudy'), '9:57 a.m. - 11:05 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_tuesdaysession2',
        get_string('summer_tuesdaysession2', 'istudy'), get_string('session_details', 'istudy'), '9:40 a.m. - 11:00 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_tuesdaysession3',
        get_string('summer_tuesdaysession3', 'istudy'), get_string('session_details', 'istudy'), '11:37 a.m. - 12:45 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_tuesdaysession4',
        get_string('summer_tuesdaysession4', 'istudy'), get_string('session_details', 'istudy'), '12:47 p.m. - 1:55 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_tuesdaysession5',
        get_string('summer_tuesdaysession5', 'istudy'), get_string('session_details', 'istudy'), '2:07 p.m. - 3:15 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_tuesdaysession6',
        get_string('summer_tuesdaysession6', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_tuesdaysession7',
        get_string('summer_tuesdaysession7', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_tuesdaysession8',
        get_string('summer_tuesdaysession8', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_wednesdaysession1',
        get_string('summer_wednesdaysession1', 'istudy'), get_string('session_details', 'istudy'), '9:57 a.m. - 11:05 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_wednesdaysession2',
        get_string('summer_wednesdaysession2', 'istudy'), get_string('session_details', 'istudy'), '9:40 a.m. - 11:00 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_wednesdaysession3',
        get_string('summer_wednesdaysession3', 'istudy'), get_string('session_details', 'istudy'), '11:37 a.m. - 12:45 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_wednesdaysession4',
        get_string('summer_wednesdaysession4', 'istudy'), get_string('session_details', 'istudy'), '12:47 p.m. - 1:55 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_wednesdaysession5',
        get_string('summer_wednesdaysession5', 'istudy'), get_string('session_details', 'istudy'), '2:07 p.m. - 3:15 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_wednesdaysession6',
        get_string('summer_wednesdaysession6', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_wednesdaysession7',
        get_string('summer_wednesdaysession7', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_wednesdaysession8',
        get_string('summer_wednesdaysession8', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_thursdaysession1',
        get_string('summer_thursdaysession1', 'istudy'), get_string('session_details', 'istudy'), '9:57 a.m. - 11:05 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_thursdaysession2',
        get_string('summer_thursdaysession2', 'istudy'), get_string('session_details', 'istudy'), '9:40 a.m. - 11:00 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_thursdaysession3',
        get_string('summer_thursdaysession3', 'istudy'), get_string('session_details', 'istudy'), '11:37 a.m. - 12:45 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_thursdaysession4',
        get_string('summer_thursdaysession4', 'istudy'), get_string('session_details', 'istudy'), '12:47 p.m. - 1:55 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_thursdaysession5',
        get_string('summer_thursdaysession5', 'istudy'), get_string('session_details', 'istudy'), '2:07 p.m. - 3:15 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_thursdaysession6',
        get_string('summer_thursdaysession6', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_thursdaysession7',
        get_string('summer_thursdaysession7', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_thursdaysession8',
        get_string('summer_thursdaysession8', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));

	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_fridaysession1',
        get_string('summer_fridaysession1', 'istudy'), get_string('session_details', 'istudy'), '9:57 a.m. - 11:05 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_fridaysession2',
        get_string('summer_fridaysession2', 'istudy'), get_string('session_details', 'istudy'), '9:40 a.m. - 11:00 a.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_fridaysession3',
        get_string('summer_fridaysession3', 'istudy'), get_string('session_details', 'istudy'), '11:37 a.m. - 12:45 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_fridaysession4',
        get_string('summer_fridaysession4', 'istudy'), get_string('session_details', 'istudy'), '12:47 p.m. - 1:55 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_fridaysession5',
        get_string('summer_fridaysession5', 'istudy'), get_string('session_details', 'istudy'), '2:07 p.m. - 3:15 p.m.', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_fridaysession6',
        get_string('summer_fridaysession6', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_fridaysession7',
        get_string('summer_fridaysession7', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/summer_fridaysession8',
        get_string('summer_fridaysession8', 'istudy'), get_string('session_details', 'istudy'), 'N/A', PARAM_TEXT, 30, 30));

	// Setup some of the student agreement details
	$settings->add(new admin_setting_heading('mod_istudy/agreementheading',
		get_string('agreementheading', 'istudy'), get_string('agreementheading_details', 'istudy')));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/agreementgoal1',
		get_string('agreementgoal1', 'istudy'), get_string('agreementgoal1_details', 'istudy'), get_string('agreementgoal1_default', 'istudy'), PARAM_TEXT, 40, 40));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/agreementgoal2',
		get_string('agreementgoal2', 'istudy'), get_string('agreementgoal2_details', 'istudy'), get_string('agreementgoal2_default', 'istudy'), PARAM_TEXT, 40, 40));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/agreementgoal3',
		get_string('agreementgoal3', 'istudy'), get_string('agreementgoal3_details', 'istudy'), get_string('agreementgoal3_default', 'istudy'), PARAM_TEXT, 40, 40));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/agreementeval1',
		get_string('agreementeval1', 'istudy'), get_string('agreementeval1_details', 'istudy'), get_string('agreementeval1_default', 'istudy'), PARAM_TEXT, 20, 20));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/agreementeval2',
		get_string('agreementeval2', 'istudy'), get_string('agreementeval2_details', 'istudy'), get_string('agreementeval2_default', 'istudy'), PARAM_TEXT, 20, 20));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/agreementeval3',
		get_string('agreementeval3', 'istudy'), get_string('agreementeval3_details', 'istudy'), get_string('agreementeval3_default', 'istudy'), PARAM_TEXT, 20, 20));
	$settings->add(new admin_setting_configtext_with_maxlength('mod_istudy/agreementeval4',
		get_string('agreementeval4', 'istudy'), get_string('agreementeval4_details', 'istudy'), get_string('agreementeval4_default', 'istudy'), PARAM_TEXT, 20, 20));
	$settings->add(new admin_setting_configtextarea('mod_istudy/agreementcity',
		get_string('agreementcity', 'istudy'), get_string('agreementcity_details', 'istudy'), get_string('agreementcity_default', 'istudy'), PARAM_TEXT, 30, 6));
}