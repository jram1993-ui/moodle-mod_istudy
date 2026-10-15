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
 * iStudy plugin upgrade code

 * @package    mod_istudy
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute istudy upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_istudy_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    /*
     *
     * Create the 3 new tables and fields needed to create the drop-down menus.
     */
    if ($oldversion < 20250930001) {

        // Define field id to be added to istudymenu_fall.
        $table_fall = new xmldb_table('istudymenu_fall');
        $table_fall->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        $table_fall->add_field('courseid', XMLDB_TYPE_TEXT, null, null, null, null, null, null);
        $table_fall->add_field('teacherid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, null);
        $table_fall->add_field('dayofweek', XMLDB_TYPE_INTEGER, '1', null, null, null, null, null);
        $table_fall->add_field('period', XMLDB_TYPE_INTEGER, '2', null, null, null, null, null);
        $table_fall->add_field('active', XMLDB_TYPE_BINARY, null, null, null, null, null, null);
        $table_fall->add_field('course_name', XMLDB_TYPE_TEXT, null, null, null, null, null, null);

        // Adding keys to table istudymenu_fall.
        $table_fall->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for istudymenu_fall.
        if (!$dbman->table_exists($table_fall)) {
            $dbman->create_table($table_fall);
        }

        // Define field id to be added to istudymenu_spring.
        $table_spring = new xmldb_table('istudymenu_spring');
        $table_spring->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        $table_spring->add_field('courseid', XMLDB_TYPE_TEXT, null, null, null, null, null, null);
        $table_spring->add_field('teacherid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, null);
        $table_spring->add_field('dayofweek', XMLDB_TYPE_INTEGER, '1', null, null, null, null, null);
        $table_spring->add_field('period', XMLDB_TYPE_INTEGER, '2', null, null, null, null, null);
        $table_spring->add_field('active', XMLDB_TYPE_BINARY, null, null, null, null, null, null);
        $table_spring->add_field('course_name', XMLDB_TYPE_TEXT, null, null, null, null, null, null);

        // Adding keys to table istudymenu_spring.
        $table_spring->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for istudymenu_spring.
        if (!$dbman->table_exists($table_spring)) {
            $dbman->create_table($table_spring);
        }

        // Define field id to be added to istudymenu_summer.
        $table_summer = new xmldb_table('istudymenu_summer');
        $table_summer->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        $table_summer->add_field('courseid', XMLDB_TYPE_TEXT, null, null, null, null, null, null);
        $table_summer->add_field('teacherid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, null);
        $table_summer->add_field('dayofweek', XMLDB_TYPE_INTEGER, '1', null, null, null, null, null);
        $table_summer->add_field('period', XMLDB_TYPE_INTEGER, '2', null, null, null, null, null);
        $table_summer->add_field('active', XMLDB_TYPE_BINARY, null, null, null, null, null, null);
        $table_summer->add_field('course_name', XMLDB_TYPE_TEXT, null, null, null, null, null, null);

        // Adding keys to table istudymenu_summer.
        $table_summer->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for istudymenu_summer.
        if (!$dbman->table_exists($table_summer)) {
            $dbman->create_table($table_summer);
        }

        // Istudy savepoint reached.
        upgrade_mod_savepoint(true, 20250930001, 'istudy');
    }

    /*
     *
     * Convert course numbers from type integer to type text.
     */
    if ($oldversion < 20260214001) {

        $table_monthatt = new xmldb_table('istudy_monthatten');
        $field_monthatt = new xmldb_field('course', XMLDB_TYPE_TEXT, null, null, null, null, null, null);
        $dbman->change_field_type($table_monthatt, $field_monthatt);

        $table_dailyatt = new xmldb_table('istudy_dailyattend_enter');
        $field_dailyatt = new xmldb_field('course', XMLDB_TYPE_TEXT, null, null, null, null, null, null);
        $dbman->change_field_type($table_dailyatt, $field_dailyatt);

        $table_sections = new xmldb_table('istudycoursenum_sections');
        $field_sections = new xmldb_field('section_number', XMLDB_TYPE_TEXT, null, null, null, null, null, null);
        $dbman->change_field_type($table_sections, $field_sections);

        // Istudy savepoint reached.
        upgrade_mod_savepoint(true, 20260214001, 'istudy');
    }

    return true;
}